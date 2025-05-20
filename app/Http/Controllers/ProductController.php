<?php

namespace App\Http\Controllers;

use App\Models\AiStar;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
    {
        $aiStar = AiStar::with('user')->findOrFail($id);
        $relatedStars = AiStar::where('id', '!=', $id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('product.show', compact('aiStar', 'relatedStars'));
    }
} 