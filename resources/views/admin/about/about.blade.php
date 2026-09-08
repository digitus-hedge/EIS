@extends('admin.layout')

@section('title', 'About Section')

@section('content')
<div class="banner-page">

    <div class="page-header">
        <h4>{{ $about->exists ? 'Edit About Section' : 'Add About Section' }}</h4>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.about.about.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="about_title">About Title</label>
            <input type="text" name="about_title" id="about_title"
                class="form-control @error('about_title') is-invalid @enderror"
                value="{{ old('about_title', $about->about_title ?? '') }}" placeholder="Enter about title">
            @error('about_title')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="about_desc">About Description</label>
            <textarea name="about_desc" id="about_desc" rows="6"
                class="form-control @error('about_desc') is-invalid @enderror"
                placeholder="Enter about description">{{ old('about_desc', $about->about_desc ?? '') }}</textarea>
            @error('about_desc')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">
                {{ $about->exists ? 'Update' : 'Save' }}
            </button>
        </div>
    </form>

</div>

<style>
    .page-header {
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #333;
    }

    .form-control {
        width: 100%;
        max-width: 700px;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
    }

    textarea.form-control {
        resize: vertical;
    }

    .form-control.is-invalid {
        border-color: #e74c3c;
    }

    .error-text {
        color: #e74c3c;
        font-size: 13px;
        display: block;
        margin-top: 4px;
    }

    .form-actions {
        margin-top: 20px;
    }

    .btn-save {
        background: #3b3b58;
        color: #fff;
        border: none;
        padding: 10px 22px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-save:hover {
        background: #2b2b42;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>
@endsection