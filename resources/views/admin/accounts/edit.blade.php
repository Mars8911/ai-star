@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ isset($admin) ? '編輯帳號' : '新增帳號' }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ isset($admin) ? route('admin.accounts.update', $admin) : route('admin.accounts.store') }}" 
                  method="POST" class="needs-validation" novalidate>
                @csrf
                @if(isset($admin))
                    @method('PUT')
                @endif

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $admin->email ?? '') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 密碼 -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        密碼 {{ isset($admin) ? '(留空表示不修改)' : '' }}
                    </label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" {{ !isset($admin) ? 'required' : '' }}>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 確認密碼 -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">確認密碼</label>
                    <input type="password" class="form-control" 
                           id="password_confirmation" name="password_confirmation" 
                           {{ !isset($admin) ? 'required' : '' }}>
                </div>

                <!-- 角色 -->
                <div class="mb-3">
                    <label for="role" class="form-label">角色</label>
                    <select class="form-select @error('role') is-invalid @enderror" 
                            id="role" name="role" required>
                        <option value="">請選擇角色</option>
                        <option value="super_admin" {{ (old('role', $admin->role ?? '') === 'super_admin') ? 'selected' : '' }}>
                            最高管理者
                        </option>
                        <option value="reviewer" {{ (old('role', $admin->role ?? '') === 'reviewer') ? 'selected' : '' }}>
                            審核管理者
                        </option>
                        <option value="editor" {{ (old('role', $admin->role ?? '') === 'editor') ? 'selected' : '' }}>
                            編輯管理者
                        </option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 狀態 -->
                @if(isset($admin))
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" 
                               name="is_active" value="1" {{ $admin->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">啟用帳號</label>
                    </div>
                </div>
                @endif

                <!-- 按鈕 -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary">
                        返回列表
                    </a>
                    <button type="submit" class="btn btn-primary">
                        儲存
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// 表單驗證
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
@endpush
@endsection 