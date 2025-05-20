@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">帳號管理</h5>
            <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> 新增帳號
            </a>
        </div>
        <div class="card-body">
            <!-- 帳號列表 -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>角色</th>
                            <th>建立時間</th>
                            <th>狀態</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                        <tr>
                            <td>{{ $admin->email }}</td>
                            <td>
                                <span class="badge bg-{{ $admin->role === 'super_admin' ? 'danger' : ($admin->role === 'reviewer' ? 'warning' : 'info') }}">
                                    {{ $admin->role === 'super_admin' ? '最高管理者' : ($admin->role === 'reviewer' ? '審核管理者' : '編輯管理者') }}
                                </span>
                            </td>
                            <td>{{ $admin->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="status-badge {{ $admin->is_active ? 'status-completed' : 'status-cancelled' }}">
                                    {{ $admin->is_active ? '啟用' : '停用' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.accounts.edit', $admin) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm {{ $admin->is_active ? 'btn-danger' : 'btn-success' }}"
                                            onclick="toggleStatus({{ $admin->id }})">
                                        <i class="bi {{ $admin->is_active ? 'bi-person-x' : 'bi-person-check' }}"></i>
                                    </button>
                                    @if($admin->role !== 'super_admin')
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="deleteAccount({{ $admin->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- 分頁 -->
            <div class="d-flex justify-content-end mt-4">
                {{ $admins->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleStatus(adminId) {
    if (confirm('確定要更改此帳號的狀態嗎？')) {
        fetch(`/admin/accounts/${adminId}/toggle-status`, {
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

function deleteAccount(adminId) {
    if (confirm('確定要刪除此帳號嗎？此操作無法復原。')) {
        fetch(`/admin/accounts/${adminId}`, {
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