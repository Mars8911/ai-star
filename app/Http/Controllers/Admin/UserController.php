<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('nationality')) {
            $query->where('nationality', $request->nationality);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['orders', 'sales', 'aiStars']);
        return view('admin.users.show', compact('user'));
    }

    public function toggleBlacklist(User $user)
    {
        $user->update(['is_blacklisted' => !$user->is_blacklisted]);
        return back()->with('success', '會員黑名單狀態已更新');
    }

    public function export(User $user)
    {
        $data = [
            '基本資料' => [
                'Email' => $user->email,
                '姓名' => $user->first_name . ' ' . $user->last_name,
                '國籍' => $user->nationality,
                '性別' => $user->gender,
                '註冊日期' => $user->created_at->format('Y-m-d'),
            ],
            '訂單紀錄' => $user->orders->map(function ($order) {
                return [
                    '訂單編號' => $order->order_number,
                    '金額' => $order->amount,
                    '狀態' => $order->status,
                    '日期' => $order->created_at->format('Y-m-d'),
                ];
            }),
        ];

        $filename = 'user_' . $user->id . '_' . date('Y-m-d') . '.xlsx';
        // 這裡需要實作 Excel 匯出邏輯
        // 可以使用 Laravel Excel 套件

        return response()->download($filename);
    }
} 