<?php

namespace App\Http\Controllers;

use App\Models\ParkingLog;
use App\Models\PriceConfig;
use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ParkingController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureVehicleTypes();

        $logs = ParkingLog::with('vehicleType')
            ->when($request->filled('plate'), function ($query) use ($request) {
                $query->where('license_plate', 'like', '%' . $request->plate . '%');
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('parking.index', [
            'logs' => $logs,
            'vehicleTypes' => VehicleType::orderBy('name')->get(),
        ]);
    }

    public function recognize(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:5120'],
            'action' => ['required', 'in:in,out'],
        ]);

        try {
            $aiResult = $this->sendToAi($request->file('image'), $request->input('action'));
            $plate = $aiResult['plate_number'] ?? null;

            if (!$plate) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI chưa trả về biển số hợp lệ.',
                ], 422);
            }

            $matchedLog = null;

            if ($request->input('action') === 'out') {
                $matchedLog = ParkingLog::with('vehicleType')
                    ->where('status', 'parking')
                    ->where('license_plate', $plate)
                    ->latest()
                    ->first();
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã nhận diện biển số.',
                'data' => [
                    'recognized_plate' => $plate,
                    'box' => $aiResult['box'] ?? [],
                    'matched_log' => $matchedLog ? $this->serializeLog($matchedLog) : null,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Recognize error', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Không thể nhận diện biển số lúc này.',
            ], 500);
        }
    }

    public function checkIn(Request $request)
    {
        $data = $request->validate([
            'image' => ['required', 'image', 'max:5120'],
            'plate_number' => ['required', 'string', 'max:20'],
            'vehicle_type_id' => ['required', 'exists:vehicle_types,id'],
            'time_in' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $plateNumber = strtoupper(trim($data['plate_number']));

        $exists = ParkingLog::where('status', 'parking')
            ->where('license_plate', $plateNumber)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => "Xe $plateNumber đang ở trong bãi.",
            ], 422);
        }

        $imagePath = $this->storeParkingImage($request->file('image'));

        $log = ParkingLog::create([
            'plate_number' => $plateNumber,
            'vehicle_type_id' => $data['vehicle_type_id'],
            'time_in' => !empty($data['time_in']) ? Carbon::parse($data['time_in']) : now(),
            'status' => 'parking',
            'note' => $data['note'] ?? null,
            'image_in' => $imagePath,
            'fee' => 0,
        ]);

        $log->load('vehicleType');

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu xe vào bãi.',
            'data' => $this->serializeLog($log),
        ]);
    }

    public function checkOut(Request $request)
    {
        $data = $request->validate([
            'image' => ['required', 'image', 'max:5120'],
            'plate_number' => ['required', 'string', 'max:20'],
            'recognized_plate' => ['nullable', 'string', 'max:20'],
            'parking_log_id' => ['nullable', 'integer'],
            'time_out' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $plateNumber = strtoupper(trim($data['plate_number']));
        $recognizedPlate = strtoupper(trim($data['recognized_plate'] ?? ''));

        $query = ParkingLog::with('vehicleType')->where('status', 'parking');

        if (!empty($data['parking_log_id'])) {
            $query->whereKey($data['parking_log_id']);
        } else {
            $query->where(function ($innerQuery) use ($plateNumber, $recognizedPlate) {
                $innerQuery->where('license_plate', $plateNumber);

                if ($recognizedPlate !== '' && $recognizedPlate !== $plateNumber) {
                    $innerQuery->orWhere('license_plate', $recognizedPlate);
                }
            });
        }

        $log = $query->latest()->first();

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy xe đang gửi trong bãi để cho ra.',
            ], 404);
        }

        $imagePath = $this->storeParkingImage($request->file('image'));
        $timeOut = !empty($data['time_out']) ? Carbon::parse($data['time_out']) : now();
        $fee = $this->calculateFee($log, $timeOut);

        $log->update([
            'plate_number' => $plateNumber,
            'time_out' => $timeOut,
            'status' => 'completed',
            'image_out' => $imagePath,
            'fee' => $fee,
            'note' => $this->mergeNotes($log->note, $data['note'] ?? null),
        ]);

        $log->refresh()->load('vehicleType');

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu xe ra bãi.',
            'data' => $this->serializeLog($log),
        ]);
    }

    protected function sendToAi(UploadedFile $image, string $action): array
    {
        $endpoint = $action === 'in'
            ? 'http://localhost:8001/api/v1/parking/check-in'
            : 'http://localhost:8001/api/v1/parking/check-out';

        $response = Http::timeout(30)
            ->attach('file', file_get_contents($image->getRealPath()), $image->getClientOriginalName())
            ->post($endpoint);

        if (!$response->successful()) {
            Log::warning('AI HTTP failure', [
                'action' => $action,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException('AI request failed.');
        }

        $payload = $response->json();

        if (!($payload['success'] ?? false)) {
            throw new \RuntimeException($payload['message'] ?? 'AI response failed.');
        }

        return $payload;
    }

    protected function storeParkingImage(UploadedFile $image): string
    {
        $directory = public_path('uploads/parking');

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $extension = $image->getClientOriginalExtension() ?: ($image->extension() ?: 'jpg');
        $filename = now()->format('Ymd_His') . '_' . uniqid() . '.' . $extension;
        $image->move($directory, $filename);

        return 'uploads/parking/' . $filename;
    }

    protected function calculateFee(ParkingLog $log, Carbon $timeOut): float
    {
        if (!$log->vehicle_type_id) {
            return 5000;
        }

        $currentTime = $timeOut->format('H:i:s');

        $config = PriceConfig::where('vehicle_type_id', $log->vehicle_type_id)
            ->where(function ($query) use ($currentTime) {
                $query->where(function ($normal) use ($currentTime) {
                    $normal->whereColumn('start_time', '<=', 'end_time')
                        ->where('start_time', '<=', $currentTime)
                        ->where('end_time', '>=', $currentTime);
                })->orWhere(function ($overnight) use ($currentTime) {
                    $overnight->whereColumn('start_time', '>', 'end_time')
                        ->where(function ($wrapped) use ($currentTime) {
                            $wrapped->where('start_time', '<=', $currentTime)
                                ->orWhere('end_time', '>=', $currentTime);
                        });
                });
            })
            ->orderBy('price')
            ->first();

        return (float) ($config->price ?? 5000);
    }

    protected function mergeNotes(?string $currentNote, ?string $newNote): ?string
    {
        $currentNote = trim((string) $currentNote);
        $newNote = trim((string) $newNote);

        if ($currentNote === '') {
            return $newNote !== '' ? $newNote : null;
        }

        if ($newNote === '' || $newNote === $currentNote) {
            return $currentNote;
        }

        return $currentNote . PHP_EOL . '---' . PHP_EOL . $newNote;
    }

    protected function serializeLog(ParkingLog $log): array
    {
        return [
            'id' => $log->id,
            'plate' => $log->plate_number,
            'plate_number' => $log->plate_number,
            'vehicle_type' => $log->vehicleType?->name,
            'vehicle_type_id' => $log->vehicle_type_id,
            'time_in' => optional($log->time_in)->format('d/m/Y H:i'),
            'time_out' => optional($log->time_out)->format('d/m/Y H:i'),
            'status' => $log->status,
            'fee' => (float) $log->fee,
            'note' => $log->note,
            'image_in_url' => $log->image_in ? asset($log->image_in) : null,
            'image_out_url' => $log->image_out ? asset($log->image_out) : null,
        ];
    }

    protected function ensureVehicleTypes(): void
    {
        VehicleType::firstOrCreate(
            ['code' => 'XE_MAY'],
            ['name' => 'Xe máy']
        );

        VehicleType::firstOrCreate(
            ['code' => 'XE_DIEN'],
            ['name' => 'Xe điện']
        );

        VehicleType::firstOrCreate(
            ['code' => 'OTO_4_7'],
            ['name' => 'Ô tô 4-7 chỗ']
        );

        VehicleType::firstOrCreate(
            ['code' => 'OTO_7_PLUS'],
            ['name' => 'Ô tô trên 7 chỗ']
        );
    }
}
