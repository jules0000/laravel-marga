@extends('layouts.app')

@section('title', 'Create Webpage')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Create New Webpage</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('webpages.store') }}">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="slug" class="form-label">Slug (URL-friendly name)</label>
                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Leave empty to auto-generate">
                @error('slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Page Type</label>
                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                    <option value="landing" {{ old('type') == 'landing' ? 'selected' : '' }}>Landing Page</option>
                    <option value="article" {{ old('type') == 'article' ? 'selected' : '' }}>Article</option>
                    <option value="shop" {{ old('type') == 'shop' ? 'selected' : '' }}>Shop</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="meta_description" class="form-label">Meta Description</label>
                <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description">{{ old('meta_description') }}</textarea>
                @error('meta_description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="keyword1, keyword2, keyword3">
                @error('meta_keywords')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_published" name="is_published" {{ old('is_published') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_published">Publish immediately</label>
            </div>
            <button type="submit" class="btn btn-primary">Create Webpage</button>
            <a href="{{ route('webpages.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

