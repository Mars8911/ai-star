@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">頁面管理</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @foreach($pages as $page)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $page->title }}</h5>
                            <p class="card-text text-muted">{{ $page->description }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary">排序：{{ $page->order }}</span>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i> 編輯
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection 