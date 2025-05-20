@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- 統計卡片 -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">總會員數</h6>
                            <h3 class="mb-0">{{ number_format($stats['total_users']) }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">總訂單數</h6>
                            <h3 class="mb-0">{{ number_format($stats['total_orders']) }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-cart text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">總營收</h6>
                            <h3 class="mb-0">${{ number_format($stats['total_revenue'], 2) }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-currency-dollar text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">待處理訂單</h6>
                            <h3 class="mb-0">{{ number_format($stats['pending_orders']) }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-clock text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 最近訂單 -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">最近訂單</h5>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm">
                查看全部
            </a>
        </div>
        <div class="card-body">
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 