@extends('admin.layout')
@section('title', 'Client Section')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
<div class="notice caution" style="margin-bottom:20px;">
    <i class="bi bi-exclamation-triangle" style="font-size:15px;flex-shrink:0;margin-top:1px;"></i>
    <p>Please fix the highlighted fields below before submitting.</p>
</div>
@endif

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: @json(session('success')),
            confirmButtonColor: '#EF7B2E',
            timer: 2500,
            timerProgressBar: true
        });
    });
</script>
@endif

<div class="wrap">
    <div class="crumbs">
        <span onclick="window.location='{{ route('admin.dashboard') }}'">Home</span>
        <span>&rsaquo;</span>
        <b>Client Section</b>
    </div>

    <div class="header">
        <div>
            <h1>Client Section</h1>
            <p>The client logos strip shown on your homepage, with a title and description.</p>
        </div>
    </div>

    <form action="{{ route('admin.home.clients.store') }}" method="POST" enctype="multipart/form-data" id="clientForm">
        @csrf

        {{-- Title --}}
        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-type"></i></span> Title<span class="req">*</span></h2>
            </div>
            <div class="field">
                <input type="text" name="title" value="{{ old('title', $client->title) }}"
                       class="{{ $errors->has('title') ? 'input-error' : '' }}"
                       placeholder="Enter title">
                @error('title')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Description --}}
        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-text-paragraph"></i></span> Description<span class="req">*</span></h2>
            </div>
            <div class="field">
                <textarea name="description" rows="4"
                          class="{{ $errors->has('description') ? 'input-error' : '' }}"
                          placeholder="Enter description">{{ old('description', $client->description) }}</textarea>
                @error('description')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Client Logos / Images --}}
        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-images"></i></span> Client Logos / Images <span class="req">*</span></h2>
            </div>

            <div class="notice caution">
                <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                <p><b>Recommended size:</b> {{ $imageWidth ?? 90 }} &times; {{ $imageHeight ?? 60 }}px &middot; JPG, PNG, WEBP &middot; up to 10MB per image.</p>
            </div>

            {{-- Existing saved images --}}
            @if (!empty($client->images))
            <div class="slot-top" style="margin-bottom:8px;">
                <span class="slot-label">Currently saved</span>
            </div>
            <div class="images-row" style="margin-bottom:20px;">
                @foreach ($client->images as $img)
                    <div class="image-slot" style="flex:0 0 110px; min-width:110px;">
                        <div class="drop filled existing-logo">
                            <img src="{{ Storage::url($img) }}" alt="Client logo">
                            <label class="remove-img-btn" title="Remove this image">
                                <input type="checkbox" name="remove_images[]" value="{{ $img }}" onchange="toggleRemoveMark(this)">
                                <i class="bi bi-x-lg"></i>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- Dynamic new image upload rows --}}
            <div class="slot-top" style="margin-bottom:8px;">
                <span class="slot-label">Add new images</span>
            </div>
            <div class="images-row" id="imageRows"></div>

            <button type="button" id="addImageBtn" class="choose-btn inline" style="margin-top:14px;">
                <i class="bi bi-plus-circle"></i> Add Image
            </button>

            @error('images')
                <span class="field-error" style="margin-top:14px;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
            @error('images.*')
                <span class="field-error" style="margin-top:14px;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>

        <div class="savebar">
            <div class="savebar-inner">
                <span class="savebar-status">All changes save to the live Client section</span>
                <div class="btn-group">
                    <a href="{{ route('admin.dashboard') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-save">
                        <i class="bi bi-check-lg"></i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<template id="imageRowTemplate">
    <div class="image-slot" style="flex:0 0 140px; min-width:140px;">
        <div class="drop new-image-row">
            <div class="preview-placeholder">
                <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                <div class="drop-title">Click to upload</div>
            </div>
        </div>
        <label class="upload-btn-input">
            <input type="file" name="images[]" accept="image/*" hidden>
        </label>
        <button type="button" class="choose-btn row-choose-btn">Choose file</button>
        <span class="file-size-info"></span>
        <button type="button" class="btn-remove-row"><i class="bi bi-trash3"></i> Remove</button>
    </div>
</template>

<script>
    const imageRowsContainer = document.getElementById('imageRows');
    const imageTemplate = document.getElementById('imageRowTemplate');
    const maxSizeMB = 10;

    // Toggle the visual "marked for removal" state on an existing saved logo
    function toggleRemoveMark(checkbox) {
        const drop = checkbox.closest('.drop');
        if (checkbox.checked) {
            drop.classList.add('marked-remove');
        } else {
            drop.classList.remove('marked-remove');
        }
    }

    function addImageRow() {
        const clone = imageTemplate.content.cloneNode(true);
        const slot = clone.querySelector('.image-slot');
        const drop = slot.querySelector('.drop');
        const input = slot.querySelector('input[type="file"]');
        const chooseBtn = slot.querySelector('.row-choose-btn');
        const sizeInfo = slot.querySelector('.file-size-info');

        chooseBtn.addEventListener('click', () => input.click());

        input.addEventListener('change', function () {
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];
            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);

            if (sizeMB > maxSizeMB) {
                sizeInfo.innerHTML = `<i class="bi bi-exclamation-triangle"></i> ${sizeMB} MB — exceeds ${maxSizeMB}MB limit!`;
                sizeInfo.classList.add('size-error');
            } else {
                sizeInfo.innerHTML = `<i class="bi bi-check-circle"></i> ${sizeMB} MB`;
                sizeInfo.classList.remove('size-error');
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                drop.innerHTML = `<img src="${e.target.result}" alt="New image preview">`;
                drop.classList.add('filled');
                chooseBtn.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });

        slot.querySelector('.btn-remove-row').addEventListener('click', () => slot.remove());

        imageRowsContainer.appendChild(slot);
    }

    document.getElementById('addImageBtn').addEventListener('click', addImageRow);

    // Start with 1 empty upload row
    addImageRow();

    document.addEventListener('DOMContentLoaded', function () {
    // ===== Scroll to the first validation error on page load =====
    const firstErrorField = document.querySelector('.input-error, .upload-btn-error');
    const firstErrorMsg = document.querySelector('.field-error');

    if (firstErrorField) {
        firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        // Give a brief highlight so the eye lands exactly on the right field
        firstErrorField.classList.add('error-flash');
        setTimeout(() => firstErrorField.classList.remove('error-flash'), 1500);
    } else if (firstErrorMsg) {
        // Fallback: some errors (like the "at least 1 image" group error) don't
        // sit on an input directly — scroll to the message itself instead.
        firstErrorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>

<style>
    .crumbs{ display:flex; align-items:center; gap:8px; font-size:13px; color: var(--faint,#9AA1B2); margin-bottom:10px; }
    .crumbs b{ color: var(--ink,#171B2C); font-weight:600; }
    .crumbs span:first-child{ cursor:pointer; transition:color .15s; }
    .crumbs span:first-child:hover{ color: var(--orange,#EF7B2E); }

    .header{ display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:32px; gap:16px; flex-wrap:wrap; }
    .header h1{ font-size:25px; font-weight:700; letter-spacing:-0.02em; margin:0; color: var(--ink,#171B2C); }
    .header p{ font-size:13.5px; color: var(--muted,#667085); margin:7px 0 0; max-width:560px; line-height:1.55; }

    .section-title{ display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:6px; }
    .section-title h2{ display:flex; align-items:center; gap:8px; font-size:14px; font-weight:700; margin:0; color: var(--ink,#171B2C); }
    .icon{ display:inline-flex; color: var(--orange,#EF7B2E); }

    .field{ margin-bottom:0; }
    input[type=text], textarea{
        width:100%; border:1px solid var(--input-border,#DBDFEA); border-radius:10px;
        padding:11px 14px; font-size:14px; font-family:inherit; color: var(--ink,#171B2C);
        outline:none; transition:box-shadow .15s, border-color .15s; resize:vertical;
    }
    input[type=text]:focus, textarea:focus{
        border-color: var(--orange,#EF7B2E);
        box-shadow: 0 0 0 4px var(--orange-tint-strong,#FFE9D8);
    }
    .input-error{ border-color:#e74c3c !important; background:#fff8f8; }
    .field-error{ display:flex; align-items:center; gap:5px; color:#e74c3c; font-size:12.5px; margin-top:6px; }

    .notice{ display:flex; align-items:flex-start; gap:8px; background: var(--canvas,#F6F7FB); border-radius:10px; padding:10px 12px; margin-bottom:16px; }
    .notice p{ font-size:12px; color: var(--muted,#667085); margin:0; }
    .notice.caution{ background:#FFF8E8; border:1px solid #F5E3B3; }
    .notice.caution i{ color:#B7791F; }
    .notice.caution p{ color:#8A6116; }
    .notice.caution p b{ color:#6B4A0E; font-weight:700; }

    .slot-top{ display:flex; align-items:center; justify-content:space-between; }
    .slot-label{ font-size:12px; font-weight:600; color: var(--muted,#667085); }

    .images-row{ display:flex; gap:14px; flex-wrap:wrap; }

    .drop{
        position:relative; aspect-ratio:4/3; border-radius:12px;
        border:2px dashed var(--input-border,#DBDFEA); background:#FAFBFD;
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        overflow:hidden; text-align:center;
    }
    .drop.filled{ border:2px solid transparent; background:#0F1220; }
    .drop img{ width:100%; height:100%; object-fit:cover; display:block; }
    .ico-circle{ width:36px; height:36px; border-radius:999px; background:#EEF0F6; display:flex; align-items:center; justify-content:center; margin-bottom:6px; }
    .drop-title{ font-size:11px; font-weight:500; color: var(--muted,#667085); }

    /* Existing saved logos: white background (logos are usually transparent/light), not dark */
    .existing-logo{ background:#fff; border:1px solid var(--line,#E9EBF2); cursor:default; }
    .existing-logo img{ object-fit:contain; padding:8px; }
    .existing-logo.marked-remove{ opacity:0.35; }
    .existing-logo.marked-remove::after{
        content:"Will be removed"; position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
        background:rgba(231,76,60,0.85); color:#fff; font-size:10px; font-weight:700; text-align:center; padding:8px;
    }

    .remove-img-btn{
        position:absolute; top:6px; right:6px; width:24px; height:24px; border-radius:999px;
        background:rgba(0,0,0,0.6); border:none; color:#fff; display:flex; align-items:center;
        justify-content:center; cursor:pointer; transition:background .15s; z-index:3; font-size:11px;
    }
    .remove-img-btn:hover{ background:rgba(0,0,0,0.85); }
    .remove-img-btn input[type="checkbox"]{ position:absolute; opacity:0; width:100%; height:100%; margin:0; cursor:pointer; }

    .new-image-row{ cursor:default; }

    .row-choose-btn{ margin-top:6px; }
    .choose-btn{
        font-size:12px; font-weight:600; color: var(--orange,#EF7B2E);
        background:#fff; border:1px solid var(--orange-border,#F3D8C2); border-radius:8px;
        padding:7px 10px; cursor:pointer; transition:background .15s; width:100%;
    }
    .choose-btn.inline{ display:inline-flex; align-items:center; gap:6px; width:auto; padding:9px 16px; }
    .choose-btn:hover{ background: var(--orange-tint,#FFF8F3); }

    .btn-remove-row{
        display:flex; align-items:center; justify-content:center; gap:5px; width:100%; margin-top:6px;
        background:#fdecea; color:#e74c3c; border:1px solid #f5c6c2; padding:6px 10px;
        border-radius:8px; font-size:11.5px; cursor:pointer; transition:background .15s;
    }
    .btn-remove-row:hover{ background:#e74c3c; color:#fff; }

    .file-size-info{ display:block; font-size:11px; color:#1e8449; margin-top:4px; text-align:center; }
    .file-size-info.size-error{ color:#e74c3c; font-weight:600; }

    .savebar{
        position:sticky; bottom:0; border-top:1px solid var(--line,#E9EBF2);
        background:rgba(255,255,255,0.92); backdrop-filter:blur(6px);
        margin:24px -32px -32px; padding:0 32px;
        box-shadow:0 -4px 16px -8px rgba(15,21,38,0.06);
    }
    .savebar-inner{ padding:16px 0; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
    .savebar-status{ font-size:12px; color: var(--faint,#9AA1B2); }
    .btn-group{ display:flex; align-items:center; gap:12px; }
    .btn-cancel{
        font-size:13px; font-weight:600; color: var(--muted,#667085); background:none; border:none;
        padding:10px 16px; border-radius:8px; cursor:pointer; text-decoration:none; transition:color .15s, background .15s;
    }
    .btn-cancel:hover{ color: var(--ink,#171B2C); background: var(--canvas,#F6F7FB); }
    .btn-save{
        display:flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:#fff;
        background:linear-gradient(135deg, #0F1526, #1D2439); border:none;
        padding:11px 22px; border-radius:9px; cursor:pointer;
        box-shadow:0 4px 12px -4px rgba(15,21,38,0.4);
        transition:transform .12s ease, box-shadow .12s ease;
    }
    .btn-save:hover{ transform:translateY(-1px); box-shadow:0 8px 18px -6px rgba(15,21,38,0.5); }

     .req {
    color: var(--orange, #EF7B2E);
}
</style>

@endsection