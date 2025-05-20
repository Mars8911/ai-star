<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['buyer', 'seller', 'aiStar']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['buyer', 'seller', 'aiStar']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
            'payment_status' => $request->status === 'completed' ? 'completed' : $order->payment_status,
        ]);

        // 發送通知
        if ($request->status === 'completed') {
            $order->buyer->notify(new OrderCompleted($order));
            $order->seller->notify(new OrderCompleted($order));
        }

        return back()->with('success', '訂單狀態已更新');
    }

    public function export(Request $request)
    {
        $query = Order::with(['buyer', 'seller', 'aiStar']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $data = $orders->map(function ($order) {
            return [
                '訂單編號' => $order->order_number,
                '買方' => $order->buyer->email,
                '賣方' => $order->seller->email,
                'AI 頭像' => $order->aiStar->name,
                '金額' => $order->amount,
                '狀態' => $order->status,
                '付款狀態' => $order->payment_status,
                '建立日期' => $order->created_at->format('Y-m-d H:i:s'),
            ];
        });

        $filename = 'orders_' . date('Y-m-d') . '.xlsx';
        // 這裡需要實作 Excel 匯出邏輯
        // 可以使用 Laravel Excel 套件

        return response()->download($filename);
    }
} 