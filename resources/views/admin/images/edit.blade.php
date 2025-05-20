@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ isset($image) ? '編輯圖像' : '新增圖像' }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ isset($image) ? route('admin.images.update', $image) : route('admin.images.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($image))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">圖像名稱</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $image->name ?? '') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">圖像描述</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description', $image->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">圖像分類</label>
                    <select class="form-select @error('category') is-invalid @enderror" 
                            id="category" name="category" required>
                        <option value="">選擇分類</option>
                        <option value="birthday" {{ (old('category', $image->category ?? '') == 'birthday') ? 'selected' : '' }}>
                            生日祝賀
                        </option>
                        <option value="encouragement" {{ (old('category', $image->category ?? '') == 'encouragement') ? 'selected' : '' }}>
                            勉勵激勵
                        </option>
                        <option value="promotion" {{ (old('category', $image->category ?? '') == 'promotion') ? 'selected' : '' }}>
                            宣傳報導
                        </option>
                        <option value="broadcast" {{ (old('category', $image->category ?? '') == 'broadcast') ? 'selected' : '' }}>
                            主播播報
                        </option>
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">圖像檔案</label>
                    @if(isset($image) && $image->url)
                        <div class="mb-2">
                            <img src="{{ $image->url }}" alt="{{ $image->name }}" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    @endif
                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                           id="image" name="image" accept="image/*" {{ !isset($image) ? 'required' : '' }}>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.images.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> 返回列表
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> 儲存變更
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 