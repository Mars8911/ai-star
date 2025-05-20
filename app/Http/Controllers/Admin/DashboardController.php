<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'completed')->sum('amount'),
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        $recentOrders = Order::with(['buyer', 'seller', 'aiStar'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
} 