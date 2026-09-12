@extends('admin.layout')
@section('title', 'Why Choose Us Section')
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
        <b>Why Choose Us Section</b>
    </div>

    <div class="header">
        <div>
            <h1>Why Choose Us Section</h1>
            <p>The heading, description, and up to 6 feature items shown in the "Why Choose Us" section on your homepage.</p>
        </div>
    </div>

    <form action="{{ route('admin.home.why-choose-us.store') }}" method="POST" enctype="multipart/form-data" id="whyForm">
        @csrf

        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-type-h1"></i></span> Main Heading<span class="req">*</span></h2>
            </div>
            <div class="field">
                <input type="text" name="heading" value="{{ old('heading', $why->heading) }}"
                       class="{{ $errors->has('heading') ? 'input-error' : '' }}"
                       placeholder="e.g. Inspection Expertise You Can Rely On">
                @error('heading')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-card-text"></i></span> Main Description<span class="req">*</span></h2>
            </div>
            <div class="field">
                <textarea name="description" rows="3"
                          class="{{ $errors->has('description') ? 'input-error' : '' }}"
                          placeholder="Specialized oil and gas inspection services backed by...">{{ old('description', $why->description) }}</textarea>
                @error('description')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-list-check"></i></span> Items (max 6)<span class="req">*</span></h2>
                <button type="button" class="btn-add-item" id="addItemBtn">
                    <i class="bi bi-plus-lg"></i> Add Item
                </button>
            </div>

            @error('items')
                <span class="field-error" style="margin-bottom:14px;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror

            <div id="itemsWrap">
                @php $existingItems = old('items', $why->items ?? []); @endphp
                @foreach ($existingItems as $index => $item)
                    <div class="item-row" data-index="{{ $index }}">
                        <div class="item-row-header">
                            <span class="item-number">{{ $index + 1 }}</span>
                            <button type="button" class="btn-remove-item"><i class="bi bi-trash"></i> Remove</button>
                        </div>

                        <div class="item-row-body">
                            <div class="item-col">
                                <div class="field">
                                    <div class="field-top"><label class="field-label">Title</label></div>
                                    <input type="text" name="items[{{ $index }}][title]"
                                           value="{{ $item['title'] ?? '' }}"
                                           class="{{ $errors->has('items.'.$index.'.title') ? 'input-error' : '' }}"
                                           placeholder="e.g. Qualification">
                                    @error('items.'.$index.'.title')
                                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="field" style="margin-top:14px;">
                                    <div class="field-top"><label class="field-label">Subheading</label></div>
                                    <input type="text" name="items[{{ $index }}][subheading]"
                                           value="{{ $item['subheading'] ?? '' }}"
                                           class="{{ $errors->has('items.'.$index.'.subheading') ? 'input-error' : '' }}"
                                           placeholder="e.g. Qualified & Certified Personnel">
                                    @error('items.'.$index.'.subheading')
                                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="field" style="margin-top:14px; margin-bottom:0;">
                                    <div class="field-top"><label class="field-label">Description</label></div>
                                    <textarea name="items[{{ $index }}][description]" rows="4"
                                              class="{{ $errors->has('items.'.$index.'.description') ? 'input-error' : '' }}"
                                              placeholder="Describe this point...">{{ $item['description'] ?? '' }}</textarea>
                                    @error('items.'.$index.'.description')
                                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="item-col item-col-image">
                                <div class="field-top"><label class="field-label">Image</label></div>

                                <div class="notice caution">
                                    <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                                    <p>JPG, PNG, WEBP &middot; up to 10MB.</p>
                                </div>

                                <div class="image-upload-box">
                                    <div class="preview-wrap">
                                        @if (!empty($item['image']))
                                            <img src="{{ Storage::url($item['image']) }}" class="preview-img" id="preview-item-{{ $index }}">
                                        @else
                                            <div class="preview-placeholder" id="preview-item-{{ $index }}">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <label class="upload-btn {{ $errors->has('items.'.$index.'.image') ? 'upload-btn-error' : '' }}">
                                        <i class="bi bi-upload"></i> Choose file
                                        <input type="file" name="items[{{ $index }}][image]" accept="image/*"
                                               onchange="previewImage(this, 'preview-item-{{ $index }}')" hidden>
                                    </label>
                                    <input type="hidden" name="items[{{ $index }}][existing_image]" value="{{ $item['image'] ?? '' }}">
                                    @error('items.'.$index.'.image')
                                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="field-hint" id="itemsLimitHint" style="display:none;">Maximum of 6 items reached.</p>
        </div>

        <div class="savebar">
            <div class="savebar-inner">
                <span class="savebar-status">All changes save to the live homepage</span>
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

<script>
    const MAX_ITEMS = 6;
    let itemIndex = {{ count($existingItems) }};

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-img';
                    img.id = previewId;
                    preview.replaceWith(img);
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function buildItemRow(index) {
        const wrap = document.createElement('div');
        wrap.className = 'item-row';
        wrap.dataset.index = index;
        wrap.innerHTML = `
            <div class="item-row-header">
                <span class="item-number">${index + 1}</span>
                <button type="button" class="btn-remove-item"><i class="bi bi-trash"></i> Remove</button>
            </div>
            <div class="item-row-body">
                <div class="item-col">
                    <div class="field">
                        <div class="field-top"><label class="field-label">Title</label></div>
                        <input type="text" name="items[${index}][title]" placeholder="e.g. Qualification">
                    </div>
                    <div class="field" style="margin-top:14px;">
                        <div class="field-top"><label class="field-label">Subheading</label></div>
                        <input type="text" name="items[${index}][subheading]" placeholder="e.g. Qualified & Certified Personnel">
                    </div>
                    <div class="field" style="margin-top:14px; margin-bottom:0;">
                        <div class="field-top"><label class="field-label">Description</label></div>
                        <textarea name="items[${index}][description]" rows="4" placeholder="Describe this point..."></textarea>
                    </div>
                </div>
                <div class="item-col item-col-image">
                    <div class="field-top"><label class="field-label">Image</label></div>
                    <div class="notice caution">
                        <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                        <p>JPG, PNG, WEBP &middot; up to 10MB.</p>
                    </div>
                    <div class="image-upload-box">
                        <div class="preview-wrap">
                            <div class="preview-placeholder" id="preview-item-${index}">
                                <i class="bi bi-image"></i>
                            </div>
                        </div>
                        <label class="upload-btn">
                            <i class="bi bi-upload"></i> Choose file
                            <input type="file" name="items[${index}][image]" accept="image/*"
                                   onchange="previewImage(this, 'preview-item-${index}')" hidden>
                        </label>
                        <input type="hidden" name="items[${index}][existing_image]" value="">
                    </div>
                </div>
            </div>
        `;
        return wrap;
    }

    function renumberItems() {
        document.querySelectorAll('#itemsWrap .item-row').forEach(function (row, i) {
            row.querySelector('.item-number').textContent = i + 1;
        });
    }

    function updateAddButtonState() {
        const count = document.querySelectorAll('#itemsWrap .item-row').length;
        const addBtn = document.getElementById('addItemBtn');
        const hint = document.getElementById('itemsLimitHint');
        addBtn.style.display = count >= MAX_ITEMS ? 'none' : 'inline-flex';
        hint.style.display = count >= MAX_ITEMS ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const wrap = document.getElementById('itemsWrap');

        if (wrap.children.length === 0) {
            wrap.appendChild(buildItemRow(itemIndex));
            itemIndex++;
        }

        updateAddButtonState();

        document.getElementById('addItemBtn').addEventListener('click', function () {
            const count = document.querySelectorAll('#itemsWrap .item-row').length;
            if (count >= MAX_ITEMS) return;
            wrap.appendChild(buildItemRow(itemIndex));
            itemIndex++;
            updateAddButtonState();
        });

        wrap.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-remove-item');
            if (!btn) return;
            const row = btn.closest('.item-row');
            if (document.querySelectorAll('#itemsWrap .item-row').length <= 1) {
                Swal.fire({ icon: 'warning', title: 'At least one item is required', confirmButtonColor: '#EF7B2E' });
                return;
            }
            row.remove();
            renumberItems();
            updateAddButtonState();
        });
    });

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
    .field-top{ margin-bottom:8px; }
    .field-label{ font-size:13px; font-weight:600; color: var(--ink,#171B2C); }
    .field-hint{ font-size:11.5px; color: var(--faint,#9AA1B2); margin:4px 0 10px; }

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

    .upload-btn-error{ border-color:#e74c3c !important; background:#fff8f8; }

    .notice{ display:flex; align-items:flex-start; gap:8px; background: var(--canvas,#F6F7FB); border-radius:10px; padding:9px 12px; margin-bottom:12px; }
    .notice.caution{ background:#FFF8E8; border:1px solid #F5E3B3; }
    .notice.caution i{ color:#B7791F; font-size:13px; }
    .notice.caution p{ color:#8A6116; margin:0; font-size:11.5px; }

    .req{ color: var(--orange, #EF7B2E); }

    /* ===== Add item button ===== */
    .btn-add-item{
        display:inline-flex; align-items:center; gap:6px; font-size:12.5px; font-weight:600; color:#fff;
        background: var(--orange,#EF7B2E); border:none; padding:9px 16px; border-radius:9px; cursor:pointer;
        transition: background .15s ease, transform .1s ease;
    }
    .btn-add-item:hover{ background: var(--orange-deep,#DA6A20); }

    /* ===== Item rows ===== */
    .item-row{
        border:1px solid var(--line,#E9EBF2); border-radius:12px; padding:20px; margin-bottom:16px;
        border-top:3px solid var(--orange,#EF7B2E); background:#fff;
    }
    .item-row-header{ display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
    .item-number{
        display:inline-flex; align-items:center; justify-content:center; width:26px; height:26px;
        border-radius:50%; background: var(--orange-tint,#FFF8F3); color: var(--orange-deep,#DA6A20);
        font-weight:700; font-size:13px;
    }
    .btn-remove-item{
        display:inline-flex; align-items:center; gap:5px; background:#FFF5F4; color:#D5392F;
        border:1px solid #F5D3D0; padding:6px 12px; border-radius:8px; font-size:12.5px; font-weight:600; cursor:pointer;
        transition: background .15s ease;
    }
    .btn-remove-item:hover{ background:#FBD5D5; }

    .item-row-body{ display:flex; gap:28px; }
    .item-col{ flex:1; min-width:0; }
    .item-col-image{ flex:0 0 240px; }

    .image-upload-box{ display:flex; flex-direction:column; align-items:flex-start; }
    .preview-wrap{ width:100%; }
    .preview-img{ width:100%; height:140px; object-fit:cover; border-radius:10px; border:1px solid var(--line,#E9EBF2); margin-bottom:10px; }
    .preview-placeholder{
        width:100%; height:140px; display:flex; align-items:center; justify-content:center;
        background: var(--canvas,#F6F7FB); border-radius:10px; border:1.5px dashed var(--input-border,#DBDFEA);
        color: var(--faint,#9AA1B2); font-size:26px; margin-bottom:10px;
    }
    .upload-btn{
        display:inline-flex; align-items:center; gap:6px; background: var(--canvas,#F6F7FB); color: var(--ink,#171B2C);
        padding:8px 15px; border-radius:9px; font-size:12.5px; font-weight:600; cursor:pointer;
        border:1px solid var(--input-border,#DBDFEA); transition:background .15s ease, border-color .15s ease;
    }
    .upload-btn:hover{ background: var(--orange-tint,#FFF8F3); border-color: var(--orange-border,#F3D8C2); color: var(--orange-deep,#DA6A20); }

    /* ===== Sticky save bar ===== */
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

    @media (max-width: 700px) {
        .item-row-body { flex-direction: column; }
        .item-col-image { flex-basis: auto; }
    }
</style>

@endsection