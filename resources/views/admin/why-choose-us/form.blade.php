@extends('admin.layout')
@section('title', 'Why Choose Us Section')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
    <div class="alert alert-error">
        <i class="bi bi-exclamation-circle"></i>
        <div>
            Please fill below fields before submitting:
            <ul style="margin: 6px 0 0 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
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

<div class="form-header">
    <h4>
        <i class="bi bi-star"></i>
        Why Choose Us Section
    </h4>
</div>

<form action="{{ route('admin.home.why-choose-us.store') }}" method="POST" enctype="multipart/form-data" class="banner-form" id="whyForm">
    @csrf

    <div class="container-fluid px-0">
        <div class="row">

            <div class="col-md-12">
                <div class="form-card">
                    <div class="form-group">
                        <label><i class="bi bi-type-h1"></i> Main Heading</label>
                        <input type="text" name="heading" value="{{ old('heading', $why->heading) }}"
                               class="{{ $errors->has('heading') ? 'input-error' : '' }}"
                               placeholder="e.g. Inspection Expertise You Can Rely On">
                        @error('heading')
                            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" style="margin-top:16px;">
                        <label><i class="bi bi-card-text"></i> Main Description</label>
                        <textarea name="description" rows="3" placeholder="Specialized oil and gas inspection services backed by...">{{ old('description', $why->description) }}</textarea>
                        @error('description')
                            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-card">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
                        <label class="section-label" style="margin:0;"><i class="bi bi-list-check"></i> Items (max 6)</label>
                        <button type="button" class="btn-add-item" id="addItemBtn">
                            <i class="bi bi-plus-lg"></i> Add Item
                        </button>
                    </div>

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
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" name="items[{{ $index }}][title]" value="{{ $item['title'] ?? '' }}" placeholder="e.g. Qualification">
                                        </div>
                                        <div class="form-group" style="margin-top:12px;">
                                            <label>Subheading</label>
                                            <input type="text" name="items[{{ $index }}][subheading]" value="{{ $item['subheading'] ?? '' }}" placeholder="e.g. Qualified & Certified Personnel">
                                        </div>
                                        <div class="form-group" style="margin-top:12px;">
                                            <label>Description</label>
                                            <textarea name="items[{{ $index }}][description]" rows="4" placeholder="Describe this point...">{{ $item['description'] ?? '' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="item-col item-col-image">
                                        <label>Image</label>
                                            <p class="hint-text">Max <strong>10MB</strong> — JPG, PNG, WEBP</p>
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
                                            <label class="upload-btn">
                                                <i class="bi bi-upload"></i> Choose file
                                                <input type="file" name="items[{{ $index }}][image]" accept="image/*"
                                                       onchange="previewImage(this, 'preview-item-{{ $index }}')" hidden>
                                            </label>
                                            <input type="hidden" name="items[{{ $index }}][existing_image]" value="{{ $item['image'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <p class="hint-text" id="itemsLimitHint" style="display:none;">Maximum of 6 items reached.</p>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-actions">
                    <a href="{{ route('admin.dashboard') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-lg"></i>
                        Save
                    </button>
                </div>
            </div>

        </div>
    </div>
</form>

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
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="items[${index}][title]" placeholder="e.g. Qualification">
                    </div>
                    <div class="form-group" style="margin-top:12px;">
                        <label>Subheading</label>
                        <input type="text" name="items[${index}][subheading]" placeholder="e.g. Qualified & Certified Personnel">
                    </div>
                    <div class="form-group" style="margin-top:12px;">
                        <label>Description</label>
                        <textarea name="items[${index}][description]" rows="4" placeholder="Describe this point..."></textarea>
                    </div>
                </div>
                <div class="item-col item-col-image">
                    <label>Image</label>
                   <p class="hint-text">Max <strong>10MB</strong> — JPG, PNG, WEBP</p>
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
                Swal.fire({ icon: 'warning', title: 'At least one item is required', confirmButtonColor: '#3b3b58' });
                return;
            }
            row.remove();
            renumberItems();
            updateAddButtonState();
        });
    });
</script>

<style>
    .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; display:flex; align-items:center; gap:8px; }
    .alert-error { background: #fdecea; color: #c0392b; }
    .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
    .form-header h4 { display: flex; align-items: center; gap: 8px; color: #1e1e2d; }
    .banner-form { width: 100%; }
    .form-card { background: #fff; padding: 22px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 18px; }
    .form-group label { display: block; margin-bottom: 7px; font-weight: 600; font-size: 13px; color: #333; }
    .form-group input[type="text"], .form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; outline: none; font-family: inherit; resize: vertical; }
    .form-group input:focus, .form-group textarea:focus { border-color: #3b3b58; }
    .section-label { display: flex; align-items: center; gap: 6px; font-weight: 700; font-size: 15px; color: #1e1e2d; margin-bottom: 14px; }
    .hint-text { font-size: 12px; color: #888; margin: 4px 0 10px; }
    .input-error { border-color: #e74c3c !important; background: #fff8f8; }
    .field-error { display: flex; align-items: center; gap: 5px; color: #e74c3c; font-size: 12.5px; margin-top: 6px; }

    .btn-add-item { display:inline-flex; align-items:center; gap:6px; background:#3b3b58; color:#fff; border:none; padding:9px 16px; border-radius:6px; font-size:13.5px; font-weight:600; cursor:pointer; }
    .btn-add-item:hover { background:#2b2b42; }

    .item-row { border:1px solid #eee; border-radius:8px; padding:18px; margin-bottom:16px; border-top:3px solid #E8792D; }
    .item-row-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
    .item-number { display:inline-flex; align-items:center; justify-content:center; width:26px; height:26px; border-radius:50%; background:#f4f6f9; color:#3b3b58; font-weight:700; font-size:13px; }
    .btn-remove-item { display:inline-flex; align-items:center; gap:5px; background:#fdecea; color:#c0392b; border:none; padding:6px 12px; border-radius:6px; font-size:12.5px; font-weight:600; cursor:pointer; }
    .btn-remove-item:hover { background:#fbdad6; }

    .item-row-body { display:flex; gap:24px; }
    .item-col { flex:1; min-width:0; }
    .item-col-image { flex:0 0 240px; }

    .image-upload-box { display: flex; flex-direction: column; align-items: flex-start; }
    .preview-wrap { width: 100%; }
    .preview-img { width: 100%; height: 140px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; margin-bottom: 10px; }
    .preview-placeholder { width: 100%; height: 140px; display: flex; align-items: center; justify-content: center; background: #f4f6f9; border-radius: 6px; border: 1px dashed #ddd; color: #bbb; font-size: 26px; margin-bottom: 10px; }
    .upload-btn { display: inline-flex; align-items: center; gap: 6px; background: #f4f6f9; color: #3b3b58; padding: 7px 14px; border-radius: 6px; font-size: 13px; cursor: pointer; border: 1px solid #ddd; }
    .upload-btn:hover { background: #e9ecf2; }

    .form-actions { display: flex; gap: 12px; margin-top: 6px; }
    .btn-cancel { padding: 11px 22px; border-radius: 6px; border: 1px solid #ddd; color: #555; text-decoration: none; font-size: 14px; }
    .btn-cancel:hover { background: #f4f6f9; }
    .btn-submit { display: flex; align-items: center; gap: 7px; background: #3b3b58; color: #fff; border: none; padding: 11px 24px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; }
    .btn-submit:hover { background: #2b2b42; }

    @media (max-width: 700px) {
        .item-row-body { flex-direction: column; }
        .item-col-image { flex-basis: auto; }
    }
</style>

@endsection