@extends('admin.layout')
@section('title', 'Certificates')
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

@if (session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error')),
            confirmButtonColor: '#D5392F'
        });
    });
</script>
@endif

<div class="wrap">
    <div class="crumbs">
        <span onclick="window.location='{{ route('admin.dashboard') }}'">Home</span>
        <span>&rsaquo;</span>
        <b>Certificates</b>
    </div>

    <div class="header">
        <div>
            <h1>Certificates</h1>
            <p>Add and manage the certificates displayed on your About page.</p>
        </div>
    </div>

    <div class="card">
    <div class="section-title">
        <h2><span class="icon"><i class="bi bi-patch-check"></i></span> <span id="certFormHeading">Add Certificate</span></h2>
    </div>

    <form action="{{ route('admin.about.certificates.store') }}" method="POST" enctype="multipart/form-data" id="certForm">
        @csrf
        <input type="hidden" name="_method" id="certFormMethod" value="POST">

        <div class="field">
            <div class="field-top"><label class="field-label">Certificate Title<span class="req">*</span></label></div>
            <input type="text" name="title" id="certTitleInput" value="{{ old('title') }}"
                   class="{{ $errors->has('title') ? 'input-error' : '' }}"
                   placeholder="e.g. ISO 9001:2015">
            @error('title')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>

        <div class="field" style="margin-top:18px;">
            <div class="field-top"><label class="field-label">Certificate Image<span class="req" id="certImageReq">*</span></label></div>

            <div class="notice caution">
                <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                <p><b>Recommended Size:304 × 405px</b>JPG, PNG, WEBP &middot; up to 10MB.</p>
            </div>

            <div class="image-slot" style="max-width:280px;">
                <div class="drop img-slot" id="certDrop" data-file-input="certImage" onclick="handleDropClick(this)">
                    <div class="preview-placeholder" id="certPreview">
                        <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                        <div class="drop-title">Click to upload</div>
                        <div class="drop-sub">or drag &amp; drop</div>
                    </div>
                </div>
                <input type="file" id="certImage" name="image" accept="image/*" hidden
                       onchange="previewImage(this, 'certPreview')">
            </div>
            <span class="field-hint" id="certImageEditHint" style="display:none; margin-top:6px;">Leave empty to keep the current image.</span>
            @error('image')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>

        <div style="display:flex; gap:10px; margin-top:20px;">
            <button type="submit" class="btn-save" id="certSubmitBtn">
                <i class="bi bi-check-lg"></i> Add Certificate
            </button>
            <button type="button" class="btn-cancel-edit" id="certCancelEditBtn" style="display:none;" onclick="cancelCertEdit()">
                Cancel
            </button>
        </div>
    </form>
</div>

    <div class="card" id="existing-certificates-section">
        <div class="section-title">
            <h2><span class="icon"><i class="bi bi-collection"></i></span> Existing Certificates</h2>
        </div>

        <div class="cert-grid">
            @forelse ($certificates as $certificate)
                <div class="cert-card">
                    <img src="{{ Storage::url($certificate->image) }}" alt="{{ $certificate->title }}">
                    <!-- <div class="cert-card-body">
                        <span class="cert-card-title">{{ $certificate->title }}</span>
                       <form action="{{ route('admin.about.certificates.destroy', $certificate->id) }}" method="POST"
      id="delete-cert-{{ $certificate->id }}">
    @csrf
    @method('DELETE')
    <button type="button" class="cert-delete-btn btn-delete-cert-trigger"
            data-form-id="delete-cert-{{ $certificate->id }}"
            data-name="{{ $certificate->title }}">
        <i class="bi bi-trash3"></i>
    </button>
</form>
                    </div> -->


                    <div class="cert-card-body">
    <span class="cert-card-title">{{ $certificate->title }}</span>
    <div style="display:flex; gap:6px;">
       <button type="button" class="cert-edit-btn"
        data-id="{{ $certificate->id }}"
        data-title="{{ $certificate->title }}"
        data-image="{{ Storage::url($certificate->image) }}"
        data-url="{{ route('admin.about.certificates.update', $certificate->id) }}"
        title="Edit">
    <i class="bi bi-pencil"></i>
</button>
        <form action="{{ route('admin.about.certificates.destroy', $certificate->id) }}" method="POST"
              id="delete-cert-{{ $certificate->id }}">
            @csrf
            @method('DELETE')
            <button type="button" class="cert-delete-btn btn-delete-cert-trigger"
                    data-form-id="delete-cert-{{ $certificate->id }}"
                    data-name="{{ $certificate->title }}">
                <i class="bi bi-trash3"></i>
            </button>
        </form>
    </div>
</div>
                </div>
            @empty
                <p class="field-hint">No certificates added yet.</p>
            @endforelse
        </div>
    </div>
</div>

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
    .req{ color: var(--orange, #EF7B2E); }

    .field{ margin-bottom:0; }
    .field-top{ margin-bottom:8px; }
    .field-label{ font-size:13px; font-weight:600; color: var(--ink,#171B2C); }
    .field-hint{ font-size:13px; color: var(--faint,#9AA1B2); }

    input[type=text]{
        width:100%; border:1px solid var(--input-border,#DBDFEA); border-radius:10px;
        padding:11px 14px; font-size:14px; font-family:inherit; color: var(--ink,#171B2C);
        outline:none; transition:box-shadow .15s, border-color .15s;
    }
    input[type=text]:focus{
        border-color: var(--orange,#EF7B2E);
        box-shadow: 0 0 0 4px var(--orange-tint-strong,#FFE9D8);
    }
    .input-error{ border-color:#e74c3c !important; background:#fff8f8; }
    .field-error{ display:flex; align-items:center; gap:5px; color:#e74c3c; font-size:12.5px; margin-top:6px; }

    .notice{ display:flex; align-items:flex-start; gap:8px; background: var(--canvas,#F6F7FB); border-radius:10px; padding:9px 12px; margin-bottom:12px; }
    .notice.caution{ background:#FFF8E8; border:1px solid #F5E3B3; }
    .notice.caution i{ color:#B7791F; font-size:13px; }
    .notice.caution p{ color:#8A6116; margin:0; font-size:11.5px; }

    /* ===== Image upload slot ===== */
    .image-slot{ width:100%; }
    .drop.img-slot{
        position:relative; width:100%; height:160px;
        border:1.5px dashed var(--input-border,#DBDFEA); border-radius:12px; background:#fff;
        display:flex; align-items:center; justify-content:center; cursor:pointer; overflow:hidden;
        transition:border-color .15s ease;
    }
    .drop.img-slot:hover{ border-color: var(--orange-border,#F3D8C2); }
    .drop.img-slot.filled{ border-style:solid; padding:0; }
    .drop.img-slot img{ width:100%; height:100%; object-fit:cover; }

    .preview-placeholder{ display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; text-align:center; }
    .ico-circle{ width:34px; height:34px; border-radius:50%; background: var(--canvas,#F6F7FB); display:flex; align-items:center; justify-content:center; margin-bottom:4px; }
    .drop-title{ font-weight:600; font-size:13.5px; color: var(--ink,#171B2C); }
    .drop-sub{ font-size:11.5px; color: var(--faint,#9AA1B2); }

    /* ===== Save button ===== */
    .btn-save{
        display:inline-flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:#fff;
        background:linear-gradient(135deg, #0F1526, #1D2439); border:none;
        padding:11px 22px; border-radius:9px; cursor:pointer;
        box-shadow:0 4px 12px -4px rgba(15,21,38,0.4);
        transition:transform .12s ease, box-shadow .12s ease;
    }
    .btn-save:hover{ transform:translateY(-1px); box-shadow:0 8px 18px -6px rgba(15,21,38,0.5); }

    /* ===== Certificate grid ===== */
    .cert-grid{
        display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:16px;
    }
    .cert-card{
        background:#fff; border:1px solid var(--line,#E9EBF2); border-radius:12px; overflow:hidden; position:relative;
        box-shadow:0 1px 2px rgba(15,21,38,0.03);
    }
    .cert-card img{ width:100%; height:200px; object-fit:cover; display:block; }
    .cert-card-body{
        padding:12px 14px; display:flex; align-items:center; justify-content:space-between; gap:8px;
    }
    .cert-card-title{ font-size:13.5px; font-weight:600; color: var(--ink,#171B2C); }
    .cert-delete-btn{
        background:#FFF5F4; color:#D5392F; border:1px solid #F5D3D0; width:30px; height:30px;
        border-radius:8px; cursor:pointer; flex-shrink:0; display:flex; align-items:center; justify-content:center;
        transition:background .15s ease;
    }
    .cert-delete-btn:hover{ background:#FBD5D5; }

    .btn-cancel-edit{
    font-size:13px; font-weight:600; color: var(--muted,#667085); background:none;
    border:1px solid var(--line,#E9EBF2); padding:10px 18px; border-radius:9px; cursor:pointer;
    transition:background .15s ease;
}
.btn-cancel-edit:hover{ background: var(--canvas,#F6F7FB); }

.remove-img-btn{
    position:absolute; top:8px; right:8px; width:28px; height:28px; border-radius:999px;
    background:rgba(0,0,0,0.6); border:none; color:#fff; display:flex; align-items:center;
    justify-content:center; cursor:pointer; transition:background .15s; z-index:3; font-size:13px;
}
.remove-img-btn:hover{ background:rgba(0,0,0,0.85); }

.cert-edit-btn{
    background:#EFF3FF; color:#2F5FDB; border:1px solid #D6E0FB; width:30px; height:30px;
    border-radius:8px; cursor:pointer; flex-shrink:0; display:flex; align-items:center; justify-content:center;
    transition:background .15s ease;
}
.cert-edit-btn:hover{ background:#DCE6FF; }
</style>

<script>
    function handleDropClick(el) {
        if (el.classList.contains('filled')) return;
        const inputId = el.getAttribute('data-file-input');
        const input = document.getElementById(inputId);
        if (input) input.click();
    }

    function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!preview) return;

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const drop = preview.closest('.drop');
            drop.innerHTML = `
                <img src="${e.target.result}" id="${previewId}" alt="Preview">
                <button type="button" class="remove-img-btn" onclick="removeCertPreview(event, '${input.id}', '${previewId}')" title="Remove image">
                    <i class="bi bi-x-lg"></i>
                </button>
            `;
            drop.classList.add('filled');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeCertPreview(event, inputId, previewId) {
    event.stopPropagation();
    document.getElementById(inputId).value = '';

    const drop = document.getElementById(inputId).previousElementSibling
        || document.querySelector(`#${previewId}`)?.closest('.drop');
    // Safer: find the drop by walking up from the file input's sibling structure
    const fileInput = document.getElementById(inputId);
    const wrapper = fileInput.closest('.image-slot');
    const dropEl = wrapper.querySelector('.drop');

    dropEl.classList.remove('filled');
    dropEl.innerHTML = `
        <div class="preview-placeholder" id="${previewId}">
            <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
            <div class="drop-title">Click to upload</div>
            <div class="drop-sub">or drag &amp; drop</div>
        </div>
    `;
}

    document.addEventListener('DOMContentLoaded', function () {
        // ===== Scroll to the first validation error on page load =====
        const firstErrorField = document.querySelector('.input-error, .upload-btn-error');
        const firstErrorMsg = document.querySelector('.field-error');

        if (firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstErrorField.classList.add('error-flash');
            setTimeout(() => firstErrorField.classList.remove('error-flash'), 1500);
        } else if (firstErrorMsg) {
            firstErrorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>

<script>
    // ---------- Delete confirmation ----------
    document.addEventListener('click', function (e) {
        const deleteBtn = e.target.closest('.btn-delete-cert-trigger');
        if (deleteBtn) {
            const formId = deleteBtn.dataset.formId;
            const name = deleteBtn.dataset.name;

            Swal.fire({
                title: 'Are you sure?',
                html: `Do you really want to delete <strong>"${name}"</strong>?<br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#E9483F',
                cancelButtonColor: '#9AA1B2',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
            return;
        }

        // ---------- Edit button: switch #certForm into edit mode ----------
        const editBtn = e.target.closest('.cert-edit-btn');
        if (editBtn) {
            enterCertEditMode(editBtn);
        }
    });

   function enterCertEditMode(btn) {
    document.getElementById('certForm').action = btn.dataset.url;
    document.getElementById('certFormMethod').value = 'PUT';
    document.getElementById('certFormHeading').textContent = 'Edit Certificate';
    document.getElementById('certTitleInput').value = btn.dataset.title;
    document.getElementById('certImageReq').style.display = 'none';
    document.getElementById('certImageEditHint').style.display = 'block';
    document.getElementById('certSubmitBtn').innerHTML = '<i class="bi bi-check-lg"></i> Save Changes';
    document.getElementById('certCancelEditBtn').style.display = 'inline-flex';

    const drop = document.getElementById('certDrop');
    drop.innerHTML = `
        <img src="${btn.dataset.image}" id="certPreview" alt="Certificate">
        <button type="button" class="remove-img-btn" onclick="removeCertPreview(event, 'certImage', 'certPreview')" title="Remove image">
            <i class="bi bi-x-lg"></i>
        </button>
    `;
    drop.classList.add('filled');
    document.getElementById('certImage').value = '';

    document.querySelectorAll('#certForm .field-error').forEach(el => el.remove());
    document.querySelectorAll('#certForm .input-error').forEach(el => el.classList.remove('input-error'));

    document.getElementById('certForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

    function cancelCertEdit() {
        const form = document.getElementById('certForm');
        form.action = "{{ route('admin.about.certificates.store') }}";
        document.getElementById('certFormMethod').value = 'POST';
        document.getElementById('certFormHeading').textContent = 'Add Certificate';
        document.getElementById('certTitleInput').value = '';
        document.getElementById('certImageReq').style.display = 'inline';
        document.getElementById('certImageEditHint').style.display = 'none';
        document.getElementById('certSubmitBtn').innerHTML = '<i class="bi bi-check-lg"></i> Add Certificate';
        document.getElementById('certCancelEditBtn').style.display = 'none';

        const drop = document.getElementById('certDrop');
        drop.innerHTML = `
            <div class="preview-placeholder" id="certPreview">
                <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                <div class="drop-title">Click to upload</div>
                <div class="drop-sub">or drag &amp; drop</div>
            </div>
        `;
        drop.classList.remove('filled');
        document.getElementById('certImage').value = '';

        document.querySelectorAll('#certForm .field-error').forEach(el => el.remove());
        document.querySelectorAll('#certForm .input-error').forEach(el => el.classList.remove('input-error'));
    }

    // ---------- Add/Edit Certificate: unified AJAX submit ----------
    document.getElementById('certForm').addEventListener('submit', function (e) {
        e.preventDefault();
        submitCertForm();
    });

    function submitCertForm() {
        const form = document.getElementById('certForm');
        const formData = new FormData(form);
        const isEditMode = document.getElementById('certFormMethod').value === 'PUT';
        const submitBtn = document.getElementById('certSubmitBtn');
        const originalBtnHtml = submitBtn.innerHTML;

        form.querySelectorAll('.field-error').forEach(el => el.remove());
        form.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';

        // Laravel reads _method spoofing from the body when the real HTTP verb is POST
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async (response) => {
            const data = await response.json().catch(() => null);

            if (response.status === 422 && data && data.errors) {
                showCertValidationErrors(data.errors);
                return;
            }

            if (!response.ok) {
                throw new Error('Request failed');
            }

            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: (data && data.message) ? data.message : (isEditMode ? 'Certificate updated successfully.' : 'Certificate added successfully.'),
                confirmButtonColor: '#EF7B2E',
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                window.location.reload();
            });
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.',
                confirmButtonColor: '#D5392F'
            });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
        });
    }

    function showCertValidationErrors(errors) {
        const form = document.getElementById('certForm');

        const fieldMap = {
            title: f => f.querySelector('[name="title"]'),
            image: f => document.getElementById('certDrop'),
        };

        Object.keys(errors).forEach(field => {
            const message = errors[field][0];
            const target = fieldMap[field] ? fieldMap[field](form) : null;
            if (!target) return;

            target.classList.add('input-error');

            const errorEl = document.createElement('span');
            errorEl.className = 'field-error';
            errorEl.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
            target.insertAdjacentElement('afterend', errorEl);
        });

        const firstErrorField = form.querySelector('.input-error');
        if (firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
</script>

@endsection