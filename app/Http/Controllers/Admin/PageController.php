<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'featured_users' => 'nullable|array',
            'featured_users.*' => 'exists:users,id',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // 刪除舊圖片
            if ($page->image) {
                Storage::delete($page->image);
            }
            $data['image'] = $request->file('image')->store('pages');
        }

        $page->update($data);

        return redirect()->route('admin.pages.index')
            ->with('success', '頁面已更新');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'pages' => 'required|array',
            'pages.*.id' => 'required|exists:pages,id',
            'pages.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->pages as $pageData) {
            Page::where('id', $pageData['id'])
                ->update(['sort_order' => $pageData['sort_order']]);
        }

        return response()->json(['message' => '排序已更新']);
    }
} 