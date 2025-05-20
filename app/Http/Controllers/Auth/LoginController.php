<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showPersonalLoginForm()
    {
        return view('auth.login.personal');
    }

    public function showBusinessLoginForm()
    {
        return view('auth.login.business');
    }

    public function loginPersonal(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->type !== 'personal') {
                Auth::logout();
                return back()->withErrors([
                    'email' => '此帳號不是個人會員帳號。',
                ]);
            }
            return redirect()->intended(route('dashboard.personal'));
        }

        return back()->withErrors([
            'email' => '提供的憑證與我們的記錄不符。',
        ]);
    }

    public function loginBusiness(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->type !== 'business') {
                Auth::logout();
                return back()->withErrors([
                    'email' => '此帳號不是企業會員帳號。',
                ]);
            }
            return redirect()->intended(route('dashboard.business'));
        }

        return back()->withErrors([
            'email' => '提供的憑證與我們的記錄不符。',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
} 