<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return view('admin.accounts.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:admins',
            'password' => ['required', Password::defaults()],
            'role' => 'required|in:super_admin,reviewer,editor',
        ]);

        Admin::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.accounts.index')
            ->with('success', '管理員帳號已建立');
    }

    public function edit(Admin $admin)
    {
        return view('admin.accounts.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => ['nullable', Password::defaults()],
            'role' => 'required|in:super_admin,reviewer,editor',
        ]);

        $data = [
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.accounts.index')
            ->with('success', '管理員帳號已更新');
    }

    public function toggleStatus(Admin $admin)
    {
        $admin->update(['is_active' => !$admin->is_active]);
        return back()->with('success', '管理員狀態已更新');
    }

    public function destroy(Admin $admin)
    {
        if ($admin->id === auth()->id()) {
            return back()->with('error', '無法刪除自己的帳號');
        }

        $admin->delete();
        return back()->with('success', '管理員帳號已刪除');
    }
} 