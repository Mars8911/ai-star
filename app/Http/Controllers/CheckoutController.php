<?php

namespace App\Http\Controllers;

use App\Models\AiStar;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index($id)
    {
        $aiStar = AiStar::with('user')->findOrFail($id);
        return view('checkout.index', compact('aiStar'));
    }

    public function confirm(Request $request, $id)
    {
        $aiStar = AiStar::findOrFail($id);
        $validated = $request->validate([
            'type' => 'required|in:public,custom',
            'scene_type' => 'required|string',
            'language' => 'required|string',
            'custom_content' => 'required_if:type,custom|nullable|string',
        ]);

        $amount = $validated['type'] === 'public' 
            ? $aiStar->public_price 
            : $aiStar->business_price;

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'buyer_id' => auth()->id(),
            'seller_id' => $aiStar->user_id,
            'ai_star_id' => $aiStar->id,
            'amount' => $amount,
            'type' => $validated['type'],
            'scene_type' => $validated['scene_type'],
            'language' => $validated['language'],
            'custom_content' => $validated['custom_content'] ?? null,
        ]);

        return view('checkout.payment', compact('order', 'aiStar'));
    }

    public function payment(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'payment_method' => 'required|in:credit_card,apple_pay,paypal',
        ]);

        $order->update([
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'completed',
            'status' => 'processing',
        ]);

        // 發送通知
        $order->seller->notifications()->create([
            'title' => '新訂單通知',
            'content' => "您有一個新的訂單 #{$order->order_number}",
            'type' => 'order',
        ]);

        $order->buyer->notifications()->create([
            'title' => '訂單成立通知',
            'content' => "您的訂單 #{$order->order_number} 已成立",
            'type' => 'order',
        ]);

        return redirect()->route('notifications')->with('success', '訂單已成功建立！');
    }
} 