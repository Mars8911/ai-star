<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::with('category')->get();
        return view('admin.images.index', compact('images'));
    }

    public function create()
    {
        return view('admin.images.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:image_categories,id',
            'image' => 'required|image|max:2048',
        ]);

        $data = $request->except('image');
        $data['image'] = $request->file('image')->store('images');

        Image::create($data);

        return redirect()->route('admin.images.index')
            ->with('success', '圖像已建立');
    }

    public function edit(Image $image)
    {
        return view('admin.images.edit', compact('image'));
    }

    public function update(Request $request, Image $image)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:image_categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // 刪除舊圖片
            if ($image->image) {
                Storage::delete($image->image);
            }
            $data['image'] = $request->file('image')->store('images');
        }

        $image->update($data);

        return redirect()->route('admin.images.index')
            ->with('success', '圖像已更新');
    }

    public function destroy(Image $image)
    {
        if ($image->image) {
            Storage::delete($image->image);
        }
        $image->delete();

        return back()->with('success', '圖像已刪除');
    }
} 