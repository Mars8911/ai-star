<?php

namespace App\Http\Controllers;

use App\Models\AiStar;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $aiStars = AiStar::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('home', compact('aiStars'));
    }
} 