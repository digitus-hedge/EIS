@extends('admin.layout')

@section('title', 'About - Banner Section')

@section('content')
<div class="banner-page">

    <div class="page-header">
        <h4>{{ $about->exists ? 'Edit About Banner' : 'Add About Banner' }}</h4>
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

    <form action="{{ route('admin.about.banner.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $about->title ?? '') }}" placeholder="Enter banner title">
            @error('title')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="banner">Banner Image</label>
            <input type="file" name="banner" id="banner" accept="image/*"
                class="form-control @error('banner') is-invalid @enderror" onchange="previewImage(event)">
            @error('banner')
                <span class="error-text">{{ $message }}</span>
            @enderror

            <div class="image-preview-wrap">
                @if ($about->banner)
                    <img id="imagePreview" src="{{ asset('storage/' . $about->banner) }}" alt="About Banner">
                @else
                    <img id="imagePreview" src="#" alt="Image Preview" style="display:none;">
                @endif
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">
                {{ $about->exists ? 'Update Banner' : 'Save Banner' }}
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
        max-width: 500px;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
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

    .image-preview-wrap {
        margin-top: 12px;
    }

    .image-preview-wrap img {
        max-width: 300px;
        max-height: 180px;
        border-radius: 8px;
        border: 1px solid #ddd;
        object-fit: cover;
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

<script>
    function previewImage(event) {
        const preview = document.getElementById('imagePreview');
        const file = event.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    }
</script>
@endsection