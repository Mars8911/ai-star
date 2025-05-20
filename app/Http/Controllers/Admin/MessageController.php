<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $query = Message::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $messages = $query->latest()->paginate(20);

        return view('admin.messages.index', compact('messages'));
    }

    public function show(Message $message)
    {
        $message->load('user');
        return view('admin.messages.show', compact('message'));
    }

    public function updateStatus(Request $request, Message $message)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed',
        ]);

        $message->update(['status' => $request->status]);

        // 發送通知給用戶
        $message->user->notify(new MessageStatusUpdated($message));

        return back()->with('success', '訊息狀態已更新');
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return back()->with('success', '訊息已刪除');
    }

    public function export(Request $request)
    {
        $query = Message::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $messages = $query->get();

        $data = $messages->map(function ($message) {
            return [
                'ID' => $message->id,
                '用戶' => $message->user->email,
                '主旨' => $message->subject,
                '內容' => $message->content,
                '狀態' => $message->status,
                '建立日期' => $message->created_at->format('Y-m-d H:i:s'),
            ];
        });

        $filename = 'messages_' . date('Y-m-d') . '.xlsx';
        // 這裡需要實作 Excel 匯出邏輯
        // 可以使用 Laravel Excel 套件

        return response()->download($filename);
    }
} 