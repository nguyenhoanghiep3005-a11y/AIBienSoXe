<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User; // PHẢI CÓ DÒNG NÀY Ở ĐÂY
use Illuminate\Support\Facades\Hash; // Để mã hóa mật khẩu

class EmployeeController extends Controller
{
   public function index()
{
    $employees = Employee::all();
    $users = \App\Models\User::all(); // Lấy tất cả tài khoản từ DB
    
    // Gửi cả 2 biến sang View
    return view('employee.index', compact('employees', 'users'));
}

    public function storeAccount(Request $request)
    {
        // 1. Kiểm tra dữ liệu
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // 2. Tạo tài khoản
        User::create([
            'name' => 'Nhân viên', 
            'email' => $request->email,
            'password' => Hash::make($request->password), // Mã hóa mật khẩu
            'role' => $request->role,
        ]);

        return back()->with('success', 'Tạo tài khoản thành công!');
    }
}