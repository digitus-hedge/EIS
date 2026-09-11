@extends('admin.layout')

@section('title', 'Service - Our Services Section')

@section('content')
<div class="banner-page">

    <div class="page-header">
        <h4>{{ $servicePage->exists ? 'Edit Our Services Section' : 'Add Our Services Section' }}</h4>
    </div>

    {{-- Success / error message --}}
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

    <form action="{{ route('admin.service.our-service.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="our_service_title">Title</label>
            <input type="text" name="our_service_title" id="our_service_title" class="form-control @error('our_service_title') is-invalid @enderror"
                value="{{ old('our_service_title', $servicePage->our_service_title ?? '') }}" placeholder="Enter section title">
            @error('our_service_title')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="our_service_description">Description</label>
            <textarea name="our_service_description" id="our_service_description" rows="6"
                class="form-control @error('our_service_description') is-invalid @enderror"
                placeholder="Enter section description">{{ old('our_service_description', $servicePage->our_service_description ?? '') }}</textarea>
            @error('our_service_description')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">
                {{ $servicePage->exists ? 'Update Section' : 'Save Section' }}
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