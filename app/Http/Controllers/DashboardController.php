<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiStar;
use App\Models\Order;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return $user->type === 'personal' 
            ? redirect()->route('dashboard.personal')
            : redirect()->route('dashboard.business');
    }

    public function personal()
    {
        $user = auth()->user();
        $aiStars = $user->aiStars()->paginate(10);
        $orders = $user->orders()->with('aiStar')->latest()->paginate(10);
        $notifications = $user->notifications()->latest()->paginate(10);

        return view('dashboard.personal', compact('user', 'aiStars', 'orders', 'notifications'));
    }

    public function business()
    {
        $user = auth()->user();
        $businessProfile = $user->businessProfile;
        $aiStars = $user->aiStars()->paginate(10);
        $orders = $user->sales()->with('aiStar')->latest()->paginate(10);
        $notifications = $user->notifications()->latest()->paginate(10);

        return view('dashboard.business', compact('user', 'businessProfile', 'aiStars', 'orders', 'notifications'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'language' => 'required|string|max:10',
        ]);

        $user->update($validated);

        if ($user->type === 'business') {
            $businessValidated = $request->validate([
                'company_name' => 'required|string|max:255',
                'company_email' => 'required|email',
                'contact_person' => 'required|string|max:255',
                'contact_phone' => 'required|string|max:255',
                'business_address' => 'required|string|max:255',
            ]);

            $user->businessProfile->update($businessValidated);
        }

        return back()->with('success', '個人資料已更新');
    }

    public function updatePaymentInfo(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'payment_account' => 'required|string|max:255',
        ]);

        $user->update($validated);

        return back()->with('success', '付款資訊已更新');
    }
} 