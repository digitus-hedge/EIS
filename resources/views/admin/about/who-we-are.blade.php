@extends('admin.layout')

@section('title', 'Who We Are Section')

@section('content')
<div class="banner-page">

    <div class="page-header">
        <h4>{{ $about->exists ? 'Edit Who We Are Section' : 'Add Who We Are Section' }}</h4>
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

    <form action="{{ route('admin.about.who-we-are.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="who_we_are_desc">Who We Are Description</label>
            <textarea name="who_we_are_desc" id="who_we_are_desc" rows="6"
                class="form-control @error('who_we_are_desc') is-invalid @enderror"
                placeholder="Enter who we are description">{{ old('who_we_are_desc', $about->who_we_are_desc ?? '') }}</textarea>
            @error('who_we_are_desc')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" accept="image/*"
                class="form-control @error('image') is-invalid @enderror" onchange="previewImage(event)">
            @error('image')
                <span class="error-text">{{ $message }}</span>
            @enderror

            <div class="image-preview-wrap">
                @if ($about->image)
                    <img id="imagePreview" src="{{ asset('storage/' . $about->image) }}" alt="Who We Are Image">
                @else
                    <img id="imagePreview" src="#" alt="Image Preview" style="display:none;">
                @endif
            </div>
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