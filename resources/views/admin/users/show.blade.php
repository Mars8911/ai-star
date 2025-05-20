@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- 基本資訊 -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">基本資訊</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">會員 ID</label>
                        <p class="mb-0">{{ $user->id }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">姓名</label>
                        <p class="mb-0">{{ $user->first_name }} {{ $user->last_name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">國籍</label>
                        <p class="mb-0">{{ $user->nationality }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">性別</label>
                        <p class="mb-0">{{ $user->gender }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">註冊日期</label>
                        <p class="mb-0">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">狀態</label>
                        <p class="mb-0">
                            <span class="status-badge {{ $user->is_blacklisted ? 'status-cancelled' : 'status-completed' }}">
                                {{ $user->is_blacklisted ? '黑名單' : '正常' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 銷售資訊 -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">銷售資訊</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">公版分潤比例</h6>
                                    <h3 class="mb-0">{{ $user->public_commission_rate }}%</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">總銷售金額</h6>
                                    <h3 class="mb-0">${{ number_format($user->total_sales, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 購買紀錄 -->
                    <h6 class="mb-3">購買紀錄</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>訂單編號</th>
                                    <th>AI 頭像</th>
                                    <th>金額</th>
                                    <th>狀態</th>
                                    <th>購買日期</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->aiStar->name }}</td>
                                    <td>${{ number_format($order->amount, 2) }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $order->status }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 