@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">會員管理</h5>
        </div>
        <div class="card-body">
            <!-- 搜尋表單 -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="email" class="form-control" placeholder="Email" value="{{ request('email') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="id" class="form-control" placeholder="會員 ID" value="{{ request('id') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="nationality" class="form-select">
                            <option value="">選擇國籍</option>
                            @foreach($nationalities as $code => $name)
                                <option value="{{ $code }}" {{ request('nationality') == $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="gender" class="form-select">
                            <option value="">選擇性別</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>男</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>女</option>
                            <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>其他</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">搜尋</button>
                    </div>
                </div>
            </form>

            <!-- 會員列表 -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>姓名</th>
                            <th>國籍</th>
                            <th>性別</th>
                            <th>註冊日期</th>
                            <th>狀態</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td>{{ $user->nationality }}</td>
                            <td>{{ $user->gender }}</td>
                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            <td>
                                <span class="status-badge {{ $user->is_blacklisted ? 'status-cancelled' : 'status-completed' }}">
                                    {{ $user->is_blacklisted ? '黑名單' : '正常' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm {{ $user->is_blacklisted ? 'btn-success' : 'btn-danger' }}"
                                            onclick="toggleBlacklist({{ $user->id }})">
                                        <i class="bi {{ $user->is_blacklisted ? 'bi-person-check' : 'bi-person-x' }}"></i>
                                    </button>
                                    <a href="{{ route('admin.users.export', $user) }}" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- 分頁 -->
            <div class="d-flex justify-content-end mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleBlacklist(userId) {
    if (confirm('確定要更改此會員的黑名單狀態嗎？')) {
        fetch(`/admin/users/${userId}/toggle-blacklist`, {
            method: 'POST',
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