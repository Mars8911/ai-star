@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">訂單管理</h5>
            <a href="{{ route('admin.orders.export') }}" class="btn btn-primary">
                <i class="bi bi-download"></i> 匯出訂單
            </a>
        </div>
        <div class="card-body">
            <!-- 搜尋表單 -->
            <form action="{{ route('admin.orders.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="order_number" class="form-control" placeholder="訂單編號" value="{{ request('order_number') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="buyer_email" class="form-control" placeholder="買方 Email" value="{{ request('buyer_email') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">訂單狀態</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>待處理</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>處理中</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>已完成</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>已取消</option>
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

            <!-- 訂單列表 -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>訂單編號</th>
                            <th>買方</th>
                            <th>賣方</th>
                            <th>AI 頭像</th>
                            <th>金額</th>
                            <th>狀態</th>
                            <th>建立時間</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->buyer->email }}</td>
                            <td>{{ $order->seller->email }}</td>
                            <td>{{ $order->aiStar->name }}</td>
                            <td>${{ number_format($order->amount, 2) }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            onclick="updateStatus({{ $order->id }})">
                                        <i class="bi bi-arrow-clockwise"></i>
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
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateStatus(orderId) {
    const statuses = ['pending', 'processing', 'completed', 'cancelled'];
    const currentStatus = document.querySelector(`tr[data-order-id="${orderId}"] .status-badge`).textContent.trim();
    const currentIndex = statuses.indexOf(currentStatus);
    const nextStatus = statuses[(currentIndex + 1) % statuses.length];

    if (confirm(`確定要將訂單狀態更改為 ${nextStatus} 嗎？`)) {
        fetch(`/admin/orders/${orderId}/update-status`, {
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
</script>
@endpush
@endsection 