@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">訊息管理</h5>
            <a href="{{ route('admin.messages.export') }}" class="btn btn-primary">
                <i class="bi bi-download"></i> 匯出訊息
            </a>
        </div>
        <div class="card-body">
            <!-- 搜尋表單 -->
            <form action="{{ route('admin.messages.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="name" class="form-control" placeholder="姓名" value="{{ request('name') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ request('email') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">處理狀態</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>待處理</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>處理中</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>已完成</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">搜尋</button>
                    </div>
                </div>
            </form>

            <!-- 訊息列表 -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>姓名</th>
                            <th>Email</th>
                            <th>訊息內容</th>
                            <th>狀態</th>
                            <th>建立時間</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $message)
                        <tr>
                            <td>{{ $message->name }}</td>
                            <td>{{ $message->email }}</td>
                            <td>{{ Str::limit($message->content, 50) }}</td>
                            <td>
                                <span class="status-badge status-{{ $message->status }}">
                                    {{ $message->status }}
                                </span>
                            </td>
                            <td>{{ $message->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            onclick="updateStatus({{ $message->id }})">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="deleteMessage({{ $message->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- 分頁 -->
            <div class="d-flex justify-content-end mt-4">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateStatus(messageId) {
    const statuses = ['pending', 'processing', 'completed'];
    const currentStatus = document.querySelector(`tr[data-message-id="${messageId}"] .status-badge`).textContent.trim();
    const currentIndex = statuses.indexOf(currentStatus);
    const nextStatus = statuses[(currentIndex + 1) % statuses.length];

    if (confirm(`確定要將訊息狀態更改為 ${nextStatus} 嗎？`)) {
        fetch(`/admin/messages/${messageId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: nextStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('操作失敗：' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('操作失敗，請稍後再試');
        });
    }
}

function deleteMessage(messageId) {
    if (confirm('確定要刪除此訊息嗎？')) {
        fetch(`/admin/messages/${messageId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('操作失敗：' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('操作失敗，請稍後再試');
        });
    }
}
</script>
@endpush
@endsection 