<?php

namespace App\Http\Controllers;

use App\Models\PriceConfig;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureDefaults();

        $vehicleTypes = VehicleType::orderBy('name')->get();

        $configs = PriceConfig::with('vehicleType')
            ->when($request->filled('vehicle_type_id'), function ($query) use ($request) {
                $query->where('vehicle_type_id', $request->integer('vehicle_type_id'));
            })
            ->orderBy('vehicle_type_id')
            ->orderBy('start_time')
            ->get();

        return view('price.index', [
            'configs' => $configs,
            'vehicleTypes' => $vehicleTypes,
            'selectedVehicleTypeId' => $request->input('vehicle_type_id'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateConfig($request);

        PriceConfig::create($data);

        return redirect()
            ->route('price.index')
            ->with('status', 'Đã thêm cấu hình giá mới.');
    }

    public function update(Request $request, PriceConfig $priceConfig)
    {
        $data = $this->validateConfig($request);

        $priceConfig->update($data);

        return redirect()
            ->route('price.index')
            ->with('status', 'Đã cập nhật cấu hình giá.');
    }

    public function destroy(PriceConfig $priceConfig)
    {
        $priceConfig->delete();

        return redirect()
            ->route('price.index')
            ->with('status', 'Đã xóa cấu hình giá.');
    }

    protected function validateConfig(Request $request): array
    {
        return $request->validate([
            'vehicle_type_id' => ['required', 'exists:vehicle_types,id'],
            'time_block_name' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);
    }

    protected function ensureDefaults(): void
    {
        $motorbike = VehicleType::firstOrCreate(
            ['code' => 'XE_MAY'],
            ['name' => 'Xe máy']
        );

        $electricBike = VehicleType::firstOrCreate(
            ['code' => 'XE_DIEN'],
            ['name' => 'Xe điện']
        );

        $car = VehicleType::firstOrCreate(
            ['code' => 'OTO_4_7'],
            ['name' => 'Ô tô 4-7 chỗ']
        );

        VehicleType::firstOrCreate(
            ['code' => 'OTO_7_PLUS'],
            ['name' => 'Ô tô trên 7 chỗ']
        );

        $defaultConfigs = [
            [$motorbike->id, 'Sáng', '05:00', '10:59', 3000],
            [$motorbike->id, 'Ban ngày', '11:00', '17:59', 5000],
            [$motorbike->id, 'Buổi tối', '18:00', '21:59', 7000],
            [$motorbike->id, 'Qua đêm', '22:00', '04:59', 10000],

            [$electricBike->id, 'Sáng', '05:00', '10:59', 4000],
            [$electricBike->id, 'Ban ngày', '11:00', '17:59', 6000],
            [$electricBike->id, 'Buổi tối', '18:00', '21:59', 8000],
            [$electricBike->id, 'Qua đêm', '22:00', '04:59', 12000],

            [$car->id, 'Sáng', '05:00', '10:59', 15000],
            [$car->id, 'Ban ngày', '11:00', '17:59', 20000],
            [$car->id, 'Buổi tối', '18:00', '21:59', 25000],
            [$car->id, 'Qua đêm', '22:00', '04:59', 30000],
        ];

        foreach ($defaultConfigs as [$vehicleTypeId, $timeBlockName, $startTime, $endTime, $price]) {
            PriceConfig::firstOrCreate(
                [
                    'vehicle_type_id' => $vehicleTypeId,
                    'time_block_name' => $timeBlockName,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ],
                ['price' => $price]
            );
        }
    }
}
