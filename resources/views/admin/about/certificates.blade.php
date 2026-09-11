@extends('admin.layout')
@section('title', 'Certificates')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .form-card {
        background: #fff;
        padding: 22px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        margin-bottom: 24px;
    }

    .form-group { margin-bottom: 16px; }

    .form-group label {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 7px;
        font-weight: 600;
        font-size: 14px;
        color: #333;
    }

    .form-group input[type="text"] {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        outline: none;
    }

    .field-error {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #e74c3c;
        font-size: 12.5px;
        margin-top: 6px;
    }

    .upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f4f6f9;
        color: #3b3b58;
        padding: 7px 14px;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        border: 1px solid #ddd;
    }

    .upload-btn:hover { background: #e9ecf2; }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #3b3b58;
        color: #fff;
        border: none;
        padding: 11px 24px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 6px;
    }

    .btn-submit:hover { background: #2b2b42; }

    .cert-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 18px;
    }

    .cert-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        position: relative;
    }

    .cert-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
    }

    .cert-card-body {
        padding: 12px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .cert-card-title {
        font-size: 13.5px;
        font-weight: 600;
        color: #1e1e2d;
    }

    .cert-delete-btn {
        background: #fdecea;
        color: #c0392b;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 6px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .cert-delete-btn:hover { background: #c0392b; color: #fff; }
</style>

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: @json(session('success')),
            confirmButtonColor: '#3b3b58',
            timer: 2500,
            timerProgressBar: true
        });
    });
</script>
@endif
@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: @json(session('success')),
            confirmButtonColor: '#3b3b58',
            timer: 2500,
            timerProgressBar: true
        });
    });
</script>
@endif

@if ($errors->any())
<div class="alert alert-danger" style="background:#fdecea;color:#c0392b;padding:12px 16px;border-radius:6px;margin-bottom:16px;">
    <ul style="margin:0;padding-left:18px;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="form-card">
    <h4 style="margin-bottom:16px;">Add Certificate</h4>
    <form action="{{ route('admin.about.certificates.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Certificate Title</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. ISO 9001:2015">
            @error('title')
            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
    <label>Certificate Image</label><br>
    <label class="upload-btn">
        <i class="bi bi-upload"></i> Choose file
        <input type="file" name="image" id="certImage" accept="image/*" hidden onchange="updateCertFileName(this)">
    </label>
    <span class="file-name" id="certFileName" style="display:block; font-size:13px; color:#666; margin-top:6px;"></span>
    @error('image')
    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
    @enderror
</div>

        <button type="submit" class="btn-submit">
            <i class="bi bi-check-lg"></i> Add Certificate
        </button>
    </form>
</div>

<div class="cert-grid">
    @forelse ($certificates as $certificate)
        <div class="cert-card">
            <img src="{{ Storage::url($certificate->image) }}" alt="{{ $certificate->title }}">
            <div class="cert-card-body">
                <span class="cert-card-title">{{ $certificate->title }}</span>
                <form action="{{ route('admin.about.certificates.destroy', $certificate->id) }}" method="POST" onsubmit="return confirm('Delete this certificate?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cert-delete-btn"><i class="bi bi-trash3"></i></button>
                </form>
            </div>
        </div>
    @empty
        <p style="color:#888;">No certificates added yet.</p>
    @endforelse
</div>

@endsection
<script>
    function updateCertFileName(input) {
        const label = document.getElementById('certFileName');
        if (input.files && input.files[0]) {
            label.textContent = input.files[0].name;
        } else {
            label.textContent = '';
        }
    }

    document.querySelector('form[action="{{ route('admin.about.certificates.store') }}"]').addEventListener('submit', function (e) {
        const fileInput = document.getElementById('certImage');
        if (!fileInput.files || !fileInput.files.length) {
            e.preventDefault();
            alert('Please choose a certificate image before submitting.');
        }
    });
</script>