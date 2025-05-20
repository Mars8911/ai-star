@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">圖像管理</h5>
            <a href="{{ route('admin.images.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> 新增圖像
            </a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @foreach($images as $image)
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="{{ $image->url }}" class="card-img-top" alt="{{ $image->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $image->name }}</h5>
                            <p class="card-text text-muted">{{ $image->description }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary">{{ $image->category }}</span>
                                <div class="btn-group">
                                    <a href="{{ route('admin.images.edit', $image) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="deleteImage({{ $image->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- 分頁 -->
            <div class="d-flex justify-content-end mt-4">
                {{ $images->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteImage(imageId) {
    if (confirm('確定要刪除此圖像嗎？')) {
        fetch(`/admin/images/${imageId}`, {
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