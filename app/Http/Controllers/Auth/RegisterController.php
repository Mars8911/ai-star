<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BusinessProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function showPersonalRegistrationForm()
    {
        return view('auth.register.personal');
    }

    public function showBusinessRegistrationForm()
    {
        return view('auth.register.business');
    }

    public function registerPersonal(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'national_id' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'birth_date' => 'required|date',
            'occupation' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'nationality' => $validated['nationality'],
            'national_id' => $validated['national_id'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'],
            'occupation' => $validated['occupation'],
            'type' => 'personal',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard.personal');
    }

    public function registerBusiness(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:255',
            'company_id' => 'required|string|max:255',
            'business_address' => 'required|string|max:255',
            'establishment_date' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'first_name' => $validated['contact_person'],
            'last_name' => '',
            'type' => 'business',
        ]);

        $user->businessProfile()->create([
            'company_name' => $validated['company_name'],
            'company_email' => $validated['company_email'],
            'contact_person' => $validated['contact_person'],
            'contact_phone' => $validated['contact_phone'],
            'company_id' => $validated['company_id'],
            'business_address' => $validated['business_address'],
            'establishment_date' => $validated['establishment_date'],
        ]);

        Auth::login($user);

        return redirect()->route('dashboard.business');
    }
} 