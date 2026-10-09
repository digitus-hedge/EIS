@extends('admin.layout')

@section('title', $career->exists ? 'Career - Edit' : 'Career - Add')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
<div class="notice caution" style="margin-bottom:20px;">
    <i class="bi bi-exclamation-triangle" style="font-size:15px;flex-shrink:0;margin-top:1px;"></i>
    <p>Please fix the highlighted fields below before submitting.</p>
</div>
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
        <span onclick="window.location='{{ route('admin.career') }}'">Career</span>
        <span>&rsaquo;</span>
        <b>{{ $career->exists ? 'Edit' : 'Add' }}</b>
    </div>

    <div class="header">
        <div>
            <h1>{{ $career->exists ? 'Edit Career' : 'Add Career' }}</h1>
            <p>The title, job location and description of a career shown on the Career page of your website.</p>
        </div>
    </div>

    <form action="{{ $career->exists ? route('admin.career.update', $career) : route('admin.career.store') }}" method="POST" id="career-form">
        @csrf
        @if ($career->exists)
            @method('PUT')
        @endif

        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-type"></i></span> Title<span class="req">*</span></h2>
            </div>
            <div class="field">
                <input type="text" name="title" maxlength="255"
                       class="{{ $errors->has('title') ? 'input-error' : '' }}"
                       value="{{ old('title', $career->title) }}"
                       placeholder="e.g. Senior Electrical Engineer">
                @error('title')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-geo-alt"></i></span> Job Location<span class="req">*</span></h2>
            </div>
            <div class="field">
                <input type="text" name="location" maxlength="255"
                       class="{{ $errors->has('location') ? 'input-error' : '' }}"
                       value="{{ old('location', $career->location) }}"
                       placeholder="e.g. Dubai, UAE">
                @error('location')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-card-text"></i></span> Description<span class="req">*</span></h2>
            </div>
            <div class="field">
                <div class="editor-wrap {{ $errors->has('description') ? 'input-error' : '' }}">
                    <textarea name="description" id="career-description" rows="10"
                              placeholder="Role, responsibilities, requirements, how to apply…">{{ old('description', $career->description) }}</textarea>
                </div>
                @error('description')
                    <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="savebar">
            <div class="savebar-inner">
                <span class="savebar-status">Saved careers appear in the Career list</span>
                <div class="btn-group">
                    <a href="{{ route('admin.career') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-save">
                        <i class="bi bi-check-lg"></i>
                        {{ $career->exists ? 'Update Career' : 'Save Career' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Rich text editor (CKEditor 5 classic build). If it cannot load, the plain text box still works. --}}
<script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@41.4.2/build/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ===== Rich text editor on the Description box =====
        var box = document.getElementById('career-description');
        if (box && window.ClassicEditor) {
            ClassicEditor.create(box, {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', '|', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo'],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading2', view: 'h2', title: 'Heading', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Sub heading', class: 'ck-heading_heading3' }
                    ]
                },
                // text only: no images, media or tables
                removePlugins: ['CKBox', 'CKFinder', 'CKFinderUploadAdapter', 'CloudServices', 'EasyImage', 'Image', 'ImageCaption',
                                'ImageStyle', 'ImageToolbar', 'ImageUpload', 'PictureEditing', 'MediaEmbed', 'Table', 'TableToolbar', 'Indent']
            }).then(function (editor) {
                window.careerEditor = editor;
            }).catch(function (error) {
                console.error(error);
            });
        }

        // ===== Scroll to the first validation error on page load =====
        var firstErrorField = document.querySelector('.input-error');
        var firstErrorMsg = document.querySelector('.field-error');

        if (firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstErrorField.classList.add('error-flash');
            setTimeout(function () { firstErrorField.classList.remove('error-flash'); }, 1500);
        } else if (firstErrorMsg) {
            firstErrorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>

<style>
    .crumbs{ display:flex; align-items:center; gap:8px; font-size:13px; color: var(--faint,#9AA1B2); margin-bottom:10px; flex-wrap:wrap; }
    .crumbs b{ color: var(--ink,#171B2C); font-weight:600; }
    .crumbs span[onclick]{ cursor:pointer; transition:color .15s; }
    .crumbs span[onclick]:hover{ color: var(--orange,#EF7B2E); }

    .header{ display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:32px; gap:16px; flex-wrap:wrap; }
    .header h1{ font-size:25px; font-weight:700; letter-spacing:-0.02em; margin:0; color: var(--ink,#171B2C); }
    .header p{ font-size:13.5px; color: var(--muted,#667085); margin:7px 0 0; max-width:560px; line-height:1.55; }

    .section-title{ display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:6px; }
    .section-title h2{ display:flex; align-items:center; gap:8px; font-size:14px; font-weight:700; margin:0; color: var(--ink,#171B2C); }
    .icon{ display:inline-flex; color: var(--orange,#EF7B2E); }
    .req{ color: var(--orange, #EF7B2E); }

    .field{ margin-bottom:0; }

    .field > input[type=text], .field textarea{
        width:100%; border:1px solid var(--input-border,#DBDFEA); border-radius:10px;
        padding:11px 14px; font-size:14px; font-family:inherit; color: var(--ink,#171B2C);
        outline:none; transition:box-shadow .15s, border-color .15s; resize:vertical;
    }
    .field > input[type=text]:focus, .field textarea:focus{
        border-color: var(--orange,#EF7B2E);
        box-shadow: 0 0 0 4px var(--orange-tint-strong,#FFE9D8);
    }
    .input-error{ border-color:#e74c3c !important; background:#fff8f8; }
    .field-error{ display:flex; align-items:center; gap:5px; color:#e74c3c; font-size:12.5px; margin-top:6px; }
    .error-flash{ box-shadow:0 0 0 4px rgba(231,76,60,.18) !important; }

    /* rich text editor, matched to the other fields */
    .editor-wrap{ border-radius:10px; }
    .editor-wrap.input-error{ background:none; }
    .editor-wrap .ck.ck-editor__top .ck-sticky-panel .ck-toolbar{ border-color: var(--input-border,#DBDFEA); border-radius:10px 10px 0 0 !important; background:#FAFBFD; }
    .editor-wrap .ck.ck-editor__main > .ck-editor__editable{
        min-height:260px; max-height:560px; padding:6px 16px; font-size:14px; line-height:1.6; color: var(--ink,#171B2C);
        border-color: var(--input-border,#DBDFEA); border-radius:0 0 10px 10px !important; box-shadow:none;
    }
    .editor-wrap .ck.ck-editor__main > .ck-editor__editable.ck-focused{
        border-color: var(--orange,#EF7B2E); box-shadow:0 0 0 4px var(--orange-tint-strong,#FFE9D8);
    }
    .editor-wrap.input-error .ck.ck-editor__top .ck-sticky-panel .ck-toolbar,
    .editor-wrap.input-error .ck.ck-editor__main > .ck-editor__editable{ border-color:#e74c3c; }
    .editor-wrap .ck-content h2{ font-size:19px; margin:14px 0 6px; }
    .editor-wrap .ck-content h3{ font-size:16px; margin:12px 0 6px; }
    .editor-wrap .ck-content ul, .editor-wrap .ck-content ol{ padding-left:22px; }
    .editor-wrap .ck-content blockquote{ border-left:3px solid var(--orange,#EF7B2E); margin:10px 0; padding:2px 14px; color: var(--muted,#667085); font-style:normal; }

    .notice{ display:flex; align-items:flex-start; gap:8px; background: var(--canvas,#F6F7FB); border-radius:10px; padding:10px 12px; }
    .notice.caution{ background:#FFF8E8; border:1px solid #F5E3B3; }
    .notice.caution i{ color:#B7791F; }
    .notice.caution p{ color:#8A6116; margin:0; font-size:12px; }

    .savebar{
        position:sticky; bottom:0; border-top:1px solid var(--line,#E9EBF2);
        background:rgba(255,255,255,0.92); backdrop-filter:blur(6px);
        margin:24px -32px -32px; padding:0 32px; z-index:3;
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

    /* the save bar follows the page padding of the layout */
    @media (max-width:700px){ .savebar{ margin:20px -20px -20px; padding:0 20px; } .header{ margin-bottom:22px; } .header h1{ font-size:21px; } }
    @media (max-width:560px){ .savebar{ margin:14px -14px -14px; padding:0 14px; } .savebar-status{ display:none; } .savebar-inner{ justify-content:flex-end; } }
</style>

@endsection
