@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- 訂單資訊 -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">訂單資訊</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">訂單編號</label>
                        <p class="mb-0">{{ $order->order_number }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">訂單狀態</label>
                        <p class="mb-0">
                            <span class="status-badge status-{{ $order->status }}">
                                {{ $order->status }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">訂單金額</label>
                        <p class="mb-0">${{ number_format($order->amount, 2) }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">建立時間</label>
                        <p class="mb-0">{{ $order->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">最後更新</label>
                        <p class="mb-0">{{ $order->updated_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 買賣方資訊 -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">交易資訊</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">買方資訊</h6>
                            <div class="mb-3">
                                <label class="form-label text-muted">Email</label>
                                <p class="mb-0">{{ $order->buyer->email }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">姓名</label>
                                <p class="mb-0">{{ $order->buyer->first_name }} {{ $order->buyer->last_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">賣方資訊</h6>
                            <div class="mb-3">
                                <label class="form-label text-muted">Email</label>
                                <p class="mb-0">{{ $order->seller->email }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">姓名</label>
                                <p class="mb-0">{{ $order->seller->first_name }} {{ $order->seller->last_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI 頭像資訊 -->
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">AI 頭像資訊</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{ $order->aiStar->avatar_url }}" alt="{{ $order->aiStar->name }}" class="img-fluid rounded">
                        </div>
                        <div class="col-md-9">
                            <div class="mb-3">
                                <label class="form-label text-muted">名稱</label>
                                <p class="mb-0">{{ $order->aiStar->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">ID</label>
                                <p class="mb-0">{{ $order->aiStar->id }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">描述</label>
                                <p class="mb-0">{{ $order->aiStar->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 操作按鈕 -->
        <div class="col-md-12">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> 返回列表
                </a>
                <button type="button" class="btn btn-primary" onclick="updateStatus({{ $order->id }})">
                    <i class="bi bi-arrow-clockwise"></i> 更新狀態
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateStatus(orderId) {
    const statuses = ['pending', 'processing', 'completed', 'cancelled'];
    const currentStatus = document.querySelector('.status-badge').textContent.trim();
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