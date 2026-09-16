@extends('admin.layout')

@section('title', 'Regional Footprint')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Leaflet CSS/JS (free, no API key) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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
        <b>Regional Footprint</b>
    </div>

    <div class="header">
        <div>
            <h1>Regional Footprint</h1>
            <p>Add office locations on the map and manage the offices shown under each one.</p>
        </div>
    </div>

    <div class="card">
        <div class="section-title">
            <h2><span class="icon"><i class="bi bi-geo-alt"></i></span> Add New Location   <span class="req">*</span></h2> 
        </div>

        <div class="rf-search-wrapper">
            <input id="rf-search" type="text" placeholder="Search a place (e.g. Erbil, Iraq)" autocomplete="off">
            <div id="rf-search-results" class="rf-search-results"></div>
        </div>

        <div id="rf-map"></div>

        <form id="rf-add-location-form" action="{{ route('admin.about.regional-footprint.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="title" id="rf-title">
            <input type="hidden" name="address" id="rf-address">
            <input type="hidden" name="latitude" id="rf-latitude">
            <input type="hidden" name="longitude" id="rf-longitude">
            <input type="hidden" name="place_id" id="rf-place-id">

            <div id="rf-selected-preview" class="rf-selected-preview" style="display:none;">
                <i class="bi bi-geo-alt-fill"></i>
                <span><strong>Selected location:</strong> <span id="rf-selected-label"></span></span>
            </div>

            <div id="rf-offices-wrapper"></div>

            <div style="margin-bottom:16px;">
                <button type="button" class="btn-secondary-pill" id="rf-add-office-btn">
                    <i class="bi bi-plus-lg"></i> Add Office
                </button>
            </div>

            <button type="submit" class="btn-save" id="rf-submit-location-btn" disabled>
                <i class="bi bi-check-lg"></i> Save Location
            </button>
        </form>
    </div>

    <div class="card" id="existing-locations-section">
        <div class="section-title">
            <h2><span class="icon"><i class="bi bi-buildings"></i></span> Existing Locations</h2>
        </div>

        @forelse ($locations as $location)
            <div class="rf-location-card">
                <h4>{{ $location->title }} <small>({{ $location->latitude }}, {{ $location->longitude }})</small></h4>
                @if ($location->address)
                    <p class="rf-address">{{ $location->address }}</p>
                @endif

                <div class="rf-office-grid">
                    @foreach ($location->offices as $office)
              <div class="rf-office-card">
    @if ($office->image)
        <img src="{{ Storage::url($office->image) }}" alt="{{ $office->title }}">
    @endif
    <div class="body">
        <strong>{{ $office->title }}</strong>
        <p>{{ $office->description }}</p>
    </div>
    <div class="rf-office-actions">
        <form action="{{ route('admin.about.regional-footprint.offices.destroy', $office) }}" method="POST"
              id="delete-office-{{ $office->id }}">
            @csrf @method('DELETE')
            <button type="button" class="btn-delete-office-trigger" data-form-id="delete-office-{{ $office->id }}" data-name="{{ $office->title }}">
                <i class="bi bi-x-lg"></i>
            </button>
        </form>
        <button type="button" class="rf-edit-office-trigger"
                data-id="{{ $office->id }}"
                data-title="{{ $office->title }}"
                data-description="{{ $office->description }}"
                data-image="{{ $office->image ? Storage::url($office->image) : '' }}"
                data-url="{{ route('admin.about.regional-footprint.offices.update', $office) }}"
                title="Edit">
            <i class="bi bi-pencil"></i>
        </button>
    </div>
</div>
                    @endforeach
                </div>

                <button type="button" class="rf-add-office-toggle" onclick="rfToggleMiniForm({{ $location->id }})">
                    <i class="bi bi-plus-lg"></i> Add another office here
                </button>

               <div class="rf-mini-form" id="rf-mini-form-{{ $location->id }}">
    <form action="{{ route('admin.about.regional-footprint.offices.store', $location) }}" method="POST" enctype="multipart/form-data" class="rf-mini-office-form">
        @csrf
        <div class="field">
            <div class="field-top"><label class="field-label">Office Title<span class="req">*</span></label></div>
            <input type="text" name="title">
            <span class="field-error mini-title-error" style="display:none;"><i class="bi bi-exclamation-circle"></i> <span></span></span>
        </div>
        <div class="field" style="margin-top:12px;">
            <div class="field-top"><label class="field-label">Description<span class="req">*</span></label></div>
            <textarea name="description" rows="2"></textarea>
            <span class="field-error mini-description-error" style="display:none;"><i class="bi bi-exclamation-circle"></i> <span></span></span>
        </div>

        <div class="field" style="margin-top:12px;">
            <div class="field-top"><label class="field-label">Image<span class="req">*</span></label></div>
            <div class="image-slot" style="max-width:220px;">
                <div class="drop img-slot" data-file-input="rf-mini-image-{{ $location->id }}" onclick="handleDropClick(this)">
                    <div class="preview-placeholder" id="rf-mini-preview-{{ $location->id }}">
                        <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                        <div class="drop-title">Click to upload</div>
                        <div class="drop-sub">or drag &amp; drop</div>
                    </div>
                </div>
                <input type="file" id="rf-mini-image-{{ $location->id }}" name="image" accept="image/*" hidden
                       onchange="previewImage(this, 'rf-mini-preview-{{ $location->id }}')">
            </div>
            <span class="field-error mini-image-error" style="display:none;"><i class="bi bi-exclamation-circle"></i> <span></span></span>
        </div>
        <button type="submit" class="btn-secondary-pill" style="margin-top:14px;">
            <i class="bi bi-plus-lg"></i> Add Office
        </button>
    </form>
</div>

             <form action="{{ route('admin.about.regional-footprint.destroy', $location) }}" method="POST"
      id="delete-location-{{ $location->id }}" style="margin-top:14px;">
    @csrf @method('DELETE')
    <button type="button" class="btn-danger-pill btn-delete-location-trigger" data-form-id="delete-location-{{ $location->id }}" data-name="{{ $location->title }}">
        <i class="bi bi-trash3"></i> Remove Location
    </button>
</form>
            </div>
        @empty
            <p class="field-hint">No locations added yet.</p>
        @endforelse
    </div>


 <div class="rf-edit-office-overlay" id="rfEditOfficeOverlay" style="display:none;">
    <div class="rf-edit-office-box">
        <div class="rf-edit-office-header">
            <h3>Edit Office</h3>
            <button type="button" class="modal-close" onclick="closeRfOfficeEdit()"><i class="bi bi-x-lg"></i></button>
        </div>

        <form id="rfEditOfficeForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="field">
                <div class="field-top"><label class="field-label">Office Title<span class="req">*</span></label></div>
                <input type="text" id="rfEditOfficeTitle" name="title">
                <span class="field-error" id="rfEditOfficeTitleError" style="display:none;"><i class="bi bi-exclamation-circle"></i> <span></span></span>
            </div>

            <div class="field" style="margin-top:12px;">
                <div class="field-top"><label class="field-label">Description<span class="req">*</span></label></div>
                <textarea id="rfEditOfficeDesc" name="description" rows="2"></textarea>
                <span class="field-error" id="rfEditOfficeDescError" style="display:none;"><i class="bi bi-exclamation-circle"></i> <span></span></span>
            </div>

               <div class="notice caution">
                <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                <p><b>Recommended size:</b> {{ $imageWidth ?? 320 }} &times; {{ $imageHeight ?? 220 }}px &middot; JPG, PNG, WEBP &middot; up to 10MB.</p>
            </div>

            <div class="field" style="margin-top:12px;">
                <div class="field-top"><label class="field-label">Image<span class="req">*</span></label></div>
               
            
                <div class="image-slot" style="max-width:220px;">
                    <div class="drop img-slot" id="rfEditOfficeDrop" data-file-input="rfEditOfficeImage" onclick="handleDropClick(this)">
                        <div class="preview-placeholder" id="rfEditOfficePreview">
                            <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                            <div class="drop-title">Click to upload</div>
                            <div class="drop-sub">or drag &amp; drop</div>
                        </div>
                    </div>
                    <input type="file" id="rfEditOfficeImage" name="image" accept="image/*" hidden
                           onchange="previewImage(this, 'rfEditOfficePreview')">
                </div>
                <span class="field-hint" style="display:block; margin-top:6px;">Leave empty to keep the current image.</span>
                <span class="field-error" id="rfEditOfficeImageError" style="display:none;"><i class="bi bi-exclamation-circle"></i> <span></span></span>
            </div>

            <div style="display:flex; gap:10px; margin-top:16px;">
                <button type="button" class="btn-secondary-pill" id="rfEditOfficeSaveBtn" onclick="submitRfOfficeEdit()">
                    <i class="bi bi-check-lg"></i> Save Changes
                </button>
                <button type="button" class="btn-cancel-edit" onclick="closeRfOfficeEdit()">Cancel</button>
            </div>
        </form>
    </div>
</div>

</div>

<!-- Hidden template for a new office row in the "add location" form -->
<template id="rf-office-template">
    <div class="rf-office-row">
        <button type="button" class="rf-remove-office" title="Remove"><i class="bi bi-trash3"></i></button>
        <div class="field">
            <div class="field-top"><label class="field-label">Office Title</label> <span class="req">*</span></div>
            <input type="text" name="offices[__INDEX__][title]">
        </div>
        <div class="field" style="margin-top:12px;">
            <div class="field-top"><label class="field-label">Description </label> <span class="req">*</span></div>
            <textarea name="offices[__INDEX__][description]" rows="2"></textarea>
        </div>
        <!-- <div class="field" style="margin-top:12px; margin-bottom:0;">
            <div class="field-top"><label class="field-label">Image</label></div>
            <input type="file" name="offices[__INDEX__][image]" accept="image/*">
        </div> -->


        <div class="field" style="margin-top:12px; margin-bottom:0;">
              <div class="notice caution">
                <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                <p><b>Recommended size:</b> {{ $imageWidth ?? 320 }} &times; {{ $imageHeight ?? 220 }}px &middot; JPG, PNG, WEBP &middot; up to 10MB.</p>
            </div>
    <div class="field-top"><label class="field-label">Image</label> <span class="req">*</span></div>
    <div class="image-slot" style="max-width:220px;">
        <div class="drop img-slot" data-file-input="rf-office-image-__INDEX__" onclick="handleDropClick(this)">
            <div class="preview-placeholder" id="rf-office-preview-__INDEX__">
                <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                <div class="drop-title">Click to upload</div>
                <div class="drop-sub">or drag &amp; drop</div>
            </div>
        </div>
        <input type="file" id="rf-office-image-__INDEX__" name="offices[__INDEX__][image]" accept="image/*" hidden
               onchange="previewImage(this, 'rf-office-preview-__INDEX__')">
    </div>
</div>
    </div>
</template>

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
    .field-hint{ font-size:13px; color: var(--faint,#9AA1B2); }

    input[type=text], textarea, input[type=file]{
        width:100%; border:1px solid var(--input-border,#DBDFEA); border-radius:10px;
        padding:10px 13px; font-size:14px; font-family:inherit; color: var(--ink,#171B2C);
        outline:none; transition:box-shadow .15s, border-color .15s; resize:vertical;
    }
    input[type=text]:focus, textarea:focus{
        border-color: var(--orange,#EF7B2E);
        box-shadow: 0 0 0 4px var(--orange-tint-strong,#FFE9D8);
    }

    .notice{ display:flex; align-items:flex-start; gap:8px; background: var(--canvas,#F6F7FB); border-radius:10px; padding:10px 12px; }
    .notice.caution{ background:#FFF8E8; border:1px solid #F5E3B3;margin-bottom: 15px;margin-top:10px; }
    .notice.caution i{ color:#B7791F; }
    .notice.caution p{ color:#8A6116; margin:0; font-size:12px; }

    /* ===== Search box ===== */
    .rf-search-wrapper{ position:relative; margin-bottom:14px; }
    .rf-search-wrapper input{ width:100%; }
    .rf-search-results{
        position:absolute; top:calc(100% + 4px); left:0; right:0; background:#fff;
        border:1px solid var(--line,#E9EBF2); border-radius:10px; max-height:220px; overflow-y:auto;
        z-index:1000; display:none; box-shadow:0 8px 24px -8px rgba(15,21,38,0.15);
    }
    .rf-search-results div{ padding:9px 13px; font-size:13px; cursor:pointer; border-bottom:1px solid var(--canvas,#F6F7FB); color: var(--ink,#171B2C); }
    .rf-search-results div:last-child{ border-bottom:none; }
    .rf-search-results div:hover{ background: var(--orange-tint,#FFF8F3); }

    #rf-map{ width:100%; height:420px; border-radius:12px; margin-bottom:20px; z-index:1; border:1px solid var(--line,#E9EBF2); }

    .rf-selected-preview{
        display:flex; align-items:center; gap:8px; background: var(--orange-tint,#FFF8F3);
        border:1px solid var(--orange-border,#F3D8C2); color: var(--orange-deep,#DA6A20);
        padding:10px 14px; border-radius:10px; font-size:13px; margin-bottom:16px;
    }
    .rf-selected-preview i{ font-size:15px; flex-shrink:0; }

    /* ===== Buttons ===== */
    .btn-save{
        display:inline-flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:#fff;
        background:linear-gradient(135deg, #0F1526, #1D2439); border:none;
        padding:11px 22px; border-radius:9px; cursor:pointer;
        box-shadow:0 4px 12px -4px rgba(15,21,38,0.4);
        transition:transform .12s ease, box-shadow .12s ease, opacity .15s ease;
    }
    .btn-save:hover{ transform:translateY(-1px); box-shadow:0 8px 18px -6px rgba(15,21,38,0.5); }
    .btn-save:disabled{ opacity:.45; cursor:not-allowed; transform:none; box-shadow:none; }

    .btn-secondary-pill{
        display:inline-flex; align-items:center; gap:6px; font-size:12.5px; font-weight:600;
        background: var(--canvas,#F6F7FB); color: var(--ink,#171B2C); border:1px solid var(--input-border,#DBDFEA);
        padding:9px 16px; border-radius:9px; cursor:pointer; transition:background .15s ease, border-color .15s ease;
    }

    .field-error{
    display:flex;
    align-items:center;
    gap:5px;
    color:#D5392F;
    font-size:12.5px;
    margin-top:6px;
}

.field-error i{
    font-size:13px;
    flex-shrink:0;
}

.input-error{
    border-color:#E9483F !important;
    background:#FFF5F4;
}

.drop.img-slot.input-error{
    border-color:#E9483F;
    background:#FFF5F4;
}

    .btn-secondary-pill:hover{ background: var(--orange-tint,#FFF8F3); border-color: var(--orange-border,#F3D8C2); color: var(--orange-deep,#DA6A20); }

    .btn-danger-pill{
        display:inline-flex; align-items:center; gap:6px; font-size:12.5px; font-weight:600;
        background:#FFF5F4; color:#D5392F; border:1px solid #F5D3D0;
        padding:9px 16px; border-radius:9px; cursor:pointer; transition:background .15s ease;
    }
    .btn-danger-pill:hover{ background:#FBD5D5; }

    /* ===== Office row (new-location form) ===== */
    .rf-office-row{
        border:1px solid var(--line,#E9EBF2); border-radius:12px; padding:18px; margin-bottom:14px;
        position:relative; background: var(--canvas,#F6F7FB);
    }
    .rf-remove-office{
        position:absolute; top:12px; right:12px; background:#FFF5F4; color:#D5392F;
        border:1px solid #F5D3D0; border-radius:8px; width:30px; height:30px;
        display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:12px;
        transition:background .15s ease;
    }
    .rf-remove-office:hover{ background:#FBD5D5; }

    /* ===== Existing location cards ===== */
    .rf-location-card{
        border:1px solid var(--line,#E9EBF2); border-radius:12px; padding:20px; margin-bottom:16px; background:#fff;
    }
    .rf-location-card:last-child{ margin-bottom:0; }
    .rf-location-card h4{ margin:0 0 4px; font-size:15px; font-weight:700; color: var(--ink,#171B2C); }
    .rf-location-card h4 small{ font-weight:500; color: var(--faint,#9AA1B2); font-size:12.5px; }
    .rf-address{ color: var(--muted,#667085); font-size:13px; margin:0 0 14px; }

    .rf-office-grid{ display:flex; flex-wrap:wrap; gap:14px; margin-top:6px; }
    .rf-office-card{
        width:220px; border:1px solid var(--line,#E9EBF2); border-radius:12px; overflow:hidden; position:relative; background:#fff;
    }
    .rf-office-card img{ width:100%; height:120px; object-fit:cover; }
    .rf-office-card .body{ padding:12px; }
    .rf-office-card .body strong{ display:block; font-size:13.5px; margin-bottom:4px; color: var(--ink,#171B2C); }
    .rf-office-card .body p{ font-size:12px; color: var(--muted,#667085); margin:0; }
    .rf-office-card form{ position:absolute; top:8px; right:8px; }
    .rf-office-card form button{
        width:26px; height:26px; display:flex; align-items:center; justify-content:center;
        background:rgba(0,0,0,0.55); color:#fff; border:none; border-radius:8px; font-size:11px; cursor:pointer;
        transition:background .15s ease;
    }
    .rf-office-card form button:hover{ background:rgba(0,0,0,0.75); }

    .rf-add-office-toggle{
        margin-top:14px; display:inline-flex; align-items:center; gap:6px; font-size:12.5px; font-weight:600;
        color: var(--orange,#EF7B2E); cursor:pointer; background:none; border:none; padding:0;
    }
    .rf-add-office-toggle:hover{ color: var(--orange-deep,#DA6A20); }

    .rf-mini-form{
        margin-top:14px; border-top:1px dashed var(--line,#E9EBF2); padding-top:14px; display:none;
    }


    .image-slot{ position:relative; }
.drop.img-slot{
    position:relative; width:100%; height:140px;
    border:1.5px dashed var(--input-border,#DBDFEA); border-radius:12px; background:#fff;
    display:flex; align-items:center; justify-content:center; cursor:pointer; overflow:hidden;
    transition:border-color .15s ease;
}
.drop.img-slot:hover{ border-color: var(--orange-border,#F3D8C2); }
.drop.img-slot.filled{ border-style:solid; padding:0; }
.drop.img-slot img{ width:100%; height:100%; object-fit:cover; }
.drop.img-slot.input-error{ border-color:#E9483F; background:#FFF5F4; }

.preview-placeholder{ display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; text-align:center; }
.ico-circle{ width:34px; height:34px; border-radius:50%; background: var(--canvas,#F6F7FB); display:flex; align-items:center; justify-content:center; margin-bottom:4px; }
.drop-title{ font-weight:600; font-size:13px; color: var(--ink,#171B2C); }
.drop-sub{ font-size:11px; color: var(--faint,#9AA1B2); }

.remove-img-btn{
    position:absolute; top:8px; right:8px; width:26px; height:26px; border-radius:8px;
    background:rgba(0,0,0,0.55); color:#fff; border:none; display:flex; align-items:center;
    justify-content:center; cursor:pointer; font-size:12px; z-index:2; transition:background .15s ease;
}
.remove-img-btn:hover{ background:rgba(0,0,0,0.75); }

    .req{ color: var(--orange, #EF7B2E); }
.rf-office-actions{
    position:absolute; top:8px; right:8px; display:flex; gap:6px;
}
.rf-office-actions form{ position:static; }
.rf-office-actions button{
    width:26px; height:26px; display:flex; align-items:center; justify-content:center;
    background:rgba(0,0,0,0.55); color:#fff; border:none; border-radius:8px; font-size:11px; cursor:pointer;
    transition:background .15s ease;
}
.rf-office-actions button:hover{ background:rgba(0,0,0,0.75); }

.rf-edit-office-overlay{
    position:fixed; inset:0; background:rgba(15,21,38,0.5);
    display:flex; align-items:center; justify-content:center; z-index:1000;
}
.rf-edit-office-box{
    background:#fff; border-radius:14px; padding:24px; width:100%; max-width:420px;
    max-height:90vh; overflow-y:auto;
}
.rf-edit-office-header{ display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; }
.rf-edit-office-header h3{ margin:0; font-size:16px; font-weight:700; color: var(--ink,#171B2C); }
.modal-close{ background:none; border:none; cursor:pointer; color: var(--muted,#667085); font-size:16px; }
.btn-cancel-edit{
    font-size:13px; font-weight:600; color: var(--muted,#667085); background:none;
    border:1px solid var(--line,#E9EBF2); padding:9px 16px; border-radius:9px; cursor:pointer;
}
.btn-cancel-edit:hover{ background: var(--canvas,#F6F7FB); }
</style>

<script>
    // ---------- Office row management (Add Location form) ----------
    let rfOfficeIndex = 0;
    const rfWrapper = document.getElementById('rf-offices-wrapper');
    const rfTemplate = document.getElementById('rf-office-template').innerHTML;

    function rfAddOfficeRow() {
        const html = rfTemplate.replaceAll('__INDEX__', rfOfficeIndex);
        const div = document.createElement('div');
        div.innerHTML = html;
        const rowEl = div.firstElementChild;
        rowEl.querySelector('.rf-remove-office').addEventListener('click', () => rowEl.remove());
        rfWrapper.appendChild(rowEl);
        rfOfficeIndex++;
    }

    document.getElementById('rf-add-office-btn').addEventListener('click', rfAddOfficeRow);

    function rfToggleMiniForm(id) {
        const el = document.getElementById('rf-mini-form-' + id);
        el.style.display = el.style.display === 'block' ? 'none' : 'block';
    }

    // ---------- Map (Leaflet + OpenStreetMap, free) ----------
    let rfMap, rfMarker;

    function rfInitMap() {
        rfMap = L.map('rf-map').setView([25.276987, 55.296249], 5); // default: Dubai

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19,
        }).addTo(rfMap);

        // Click anywhere on the map to drop a pin + reverse geocode
        rfMap.on('click', (e) => {
            rfPlaceMarker(e.latlng.lat, e.latlng.lng);
            rfReverseGeocode(e.latlng.lat, e.latlng.lng);
        });
    }

    function rfPlaceMarker(lat, lng) {
        if (rfMarker) rfMap.removeLayer(rfMarker);
        rfMarker = L.marker([lat, lng]).addTo(rfMap);
        rfMap.setView([lat, lng], 12);
    }

    function rfFillFields(title, address, lat, lng, placeId) {
        document.getElementById('rf-title').value = title || address || '';
        document.getElementById('rf-address').value = address || '';
        document.getElementById('rf-latitude').value = lat;
        document.getElementById('rf-longitude').value = lng;
        document.getElementById('rf-place-id').value = placeId || '';

        document.getElementById('rf-selected-preview').style.display = 'flex';
        document.getElementById('rf-selected-label').innerText =
            (title || address) + ' (' + parseFloat(lat).toFixed(5) + ', ' + parseFloat(lng).toFixed(5) + ')';
        document.getElementById('rf-submit-location-btn').disabled = false;
    }

    // Reverse geocode: clicked point -> address (Nominatim)
    function rfReverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                const name = data.name || (data.display_name ? data.display_name.split(',')[0] : '');
                rfFillFields(name, data.display_name, lat, lng, data.place_id);
            })
            .catch(() => rfFillFields('', '', lat, lng, ''));
    }

    // Forward search: typed text -> list of matching places (Nominatim)
    let rfSearchTimeout;
    const rfSearchInput = document.getElementById('rf-search');
    const rfResultsBox = document.getElementById('rf-search-results');

    rfSearchInput.addEventListener('input', () => {
        clearTimeout(rfSearchTimeout);
        const query = rfSearchInput.value.trim();

        if (query.length < 3) {
            rfResultsBox.style.display = 'none';
            return;
        }

        // Nominatim fair-use: debounce requests
        rfSearchTimeout = setTimeout(() => {
            fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(query)}&limit=6`)
                .then(res => res.json())
                .then(results => {
                    rfResultsBox.innerHTML = '';
                    if (!results.length) {
                        rfResultsBox.style.display = 'none';
                        return;
                    }
                    results.forEach(place => {
                        const item = document.createElement('div');
                        item.innerText = place.display_name;
                        item.addEventListener('click', () => {
                            const lat = parseFloat(place.lat);
                            const lng = parseFloat(place.lon);
                            rfPlaceMarker(lat, lng);
                            rfFillFields(place.display_name.split(',')[0], place.display_name, lat, lng, place.place_id);
                            rfResultsBox.style.display = 'none';
                            rfSearchInput.value = place.display_name;
                        });
                        rfResultsBox.appendChild(item);
                    });
                    rfResultsBox.style.display = 'block';
                })
                .catch(() => { rfResultsBox.style.display = 'none'; });
        }, 500);
    });

    // Hide dropdown when clicking elsewhere
    document.addEventListener('click', (e) => {
        if (!rfSearchInput.contains(e.target) && !rfResultsBox.contains(e.target)) {
            rfResultsBox.style.display = 'none';
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        rfInitMap();
        rfAddOfficeRow(); // one office row visible by default
    });
</script>


<script>
document.addEventListener('submit', function (e) {
    const form = e.target.closest('.rf-mini-office-form');
    if (!form) return;
    e.preventDefault();
    submitRfMiniOfficeForm(form);
});

function submitRfMiniOfficeForm(form) {
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnHtml = submitBtn.innerHTML;

    form.querySelectorAll('.field-error').forEach(el => el.style.display = 'none');
    form.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';

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
            showRfMiniOfficeErrors(form, data.errors);
            return;
        }

        if (!response.ok) {
            throw new Error('Request failed');
        }

        Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: (data && data.message) ? data.message : 'Office added successfully.',
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

function showRfMiniOfficeErrors(form, errors) {
    const fieldMap = {
        title: { input: form.querySelector('[name="title"]'), error: form.querySelector('.mini-title-error') },
        description: { input: form.querySelector('[name="description"]'), error: form.querySelector('.mini-description-error') },
        image: { input: form.querySelector('.drop.img-slot'), error: form.querySelector('.mini-image-error') },
    };

    Object.keys(errors).forEach(field => {
        const message = errors[field][0];
        const target = fieldMap[field];
        if (!target || !target.input) return;

        target.input.classList.add('input-error');
        if (target.error) {
            target.error.querySelector('span').textContent = message;
            target.error.style.display = 'flex';
        }
    });

    const firstError = form.querySelector('.input-error');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}
</script>

<script>
    document.addEventListener('click', function (e) {
        const officeBtn = e.target.closest('.btn-delete-office-trigger');
        if (officeBtn) {
            const formId = officeBtn.dataset.formId;
            const name = officeBtn.dataset.name;
            Swal.fire({
                title: 'Are you sure?',
                html: `Do you really want to remove <strong>"${name}"</strong>?<br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#E9483F',
                cancelButtonColor: '#9AA1B2',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
            return;
        }

        const locBtn = e.target.closest('.btn-delete-location-trigger');
        if (locBtn) {
            const formId = locBtn.dataset.formId;
            const name = locBtn.dataset.name;
            Swal.fire({
                title: 'Are you sure?',
                html: `Remove <strong>"${name}"</strong> and all its offices?<br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#E9483F',
                cancelButtonColor: '#9AA1B2',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
        }
    });
</script>
<script>
document.getElementById('rf-add-location-form').addEventListener('submit', function (e) {
    e.preventDefault();
    submitRfLocationForm();
});

function submitRfLocationForm() {
    const form = document.getElementById('rf-add-location-form');
    const formData = new FormData(form);
    const submitBtn = document.getElementById('rf-submit-location-btn');
    const originalBtnHtml = submitBtn.innerHTML;

    form.querySelectorAll('.field-error').forEach(el => el.remove());
    form.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';

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
            showRfValidationErrors(data.errors);
            submitBtn.innerHTML = originalBtnHtml;
            submitBtn.disabled = false; // location is still selected, keep it usable
            return;
        }

        if (!response.ok) {
            throw new Error('Request failed');
        }

        Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: 'Location added successfully.',
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
        submitBtn.innerHTML = originalBtnHtml;
        submitBtn.disabled = false;
    });
}

function showRfValidationErrors(errors) {
    const form = document.getElementById('rf-add-location-form');

    Object.keys(errors).forEach(field => {
        const message = errors[field][0];

        // top-level location fields (title/address/latitude/longitude come from the map pick)
        if (['title', 'address', 'latitude', 'longitude'].includes(field)) {
            const preview = document.getElementById('rf-selected-preview');
            const errorEl = document.createElement('span');
            errorEl.className = 'field-error';
            errorEl.style.marginBottom = '14px';
            errorEl.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
            preview.insertAdjacentElement('afterend', errorEl);
            return;
        }

        // offices.{index}.{subfield} — matched by exact name attribute, not row position,
        // since removed rows can leave index gaps
        const m = field.match(/^offices\.(\d+)\.(\w+)$/);
        if (m) {
            const [, idx, subfield] = m;
            const input = form.querySelector(`[name="offices[${idx}][${subfield}]"]`);
            if (!input) return;

            input.classList.add('input-error');
            const errorEl = document.createElement('span');
            errorEl.className = 'field-error';
            errorEl.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
            input.insertAdjacentElement('afterend', errorEl);
            return;
        }

        // general "offices" array error (e.g. "at least 1 office required")
        if (field === 'offices') {
            const wrapper = document.getElementById('rf-offices-wrapper');
            const errorEl = document.createElement('span');
            errorEl.className = 'field-error';
            errorEl.style.marginBottom = '10px';
            errorEl.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
            wrapper.insertAdjacentElement('beforebegin', errorEl);
        }
    });

    const firstError = form.querySelector('.input-error, .field-error');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

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
                <img src="${e.target.result}" alt="Preview">
                <button type="button" class="remove-img-btn" title="Remove image"><i class="bi bi-x-lg"></i></button>
            `;
            drop.classList.add('filled');

            drop.querySelector('.remove-img-btn').onclick = function (ev) {
                ev.stopPropagation();
                input.value = '';
                drop.classList.remove('filled', 'input-error');
                drop.innerHTML = `
                    <div class="preview-placeholder" id="${previewId}">
                        <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                        <div class="drop-title">Click to upload</div>
                        <div class="drop-sub">or drag &amp; drop</div>
                    </div>
                `;
            };
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>


<script>
document.addEventListener('click', function (e) {
    const editBtn = e.target.closest('.rf-edit-office-trigger');
    if (editBtn) openRfOfficeEdit(editBtn);
});

function openRfOfficeEdit(btn) {
    document.getElementById('rfEditOfficeForm').dataset.action = btn.dataset.url;
    document.getElementById('rfEditOfficeTitle').value = btn.dataset.title;
    document.getElementById('rfEditOfficeDesc').value = btn.dataset.description;
    document.getElementById('rfEditOfficeImage').value = '';

    const drop = document.getElementById('rfEditOfficeDrop');
    if (btn.dataset.image) {
        drop.innerHTML = `
            <img src="${btn.dataset.image}" id="rfEditOfficePreview" alt="Office">
            <button type="button" class="remove-img-btn" onclick="removeRfEditOfficeImage(event)" title="Remove image">
                <i class="bi bi-x-lg"></i>
            </button>
        `;
        drop.classList.add('filled');
    } else {
        drop.innerHTML = `
            <div class="preview-placeholder" id="rfEditOfficePreview">
                <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                <div class="drop-title">Click to upload</div>
                <div class="drop-sub">or drag &amp; drop</div>
            </div>
        `;
        drop.classList.remove('filled');
    }

    // clear all previous error states
    ['rfEditOfficeTitleError', 'rfEditOfficeDescError', 'rfEditOfficeImageError'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });
    document.getElementById('rfEditOfficeTitle').classList.remove('input-error');
    document.getElementById('rfEditOfficeDesc').classList.remove('input-error');
    drop.classList.remove('input-error');

    document.getElementById('rfEditOfficeOverlay').style.display = 'flex';
}

function removeRfEditOfficeImage(event) {
    event.stopPropagation();
    document.getElementById('rfEditOfficeImage').value = '';
    const drop = document.getElementById('rfEditOfficeDrop');
    drop.classList.remove('filled', 'input-error');
    drop.innerHTML = `
        <div class="preview-placeholder" id="rfEditOfficePreview">
            <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
            <div class="drop-title">Click to upload</div>
            <div class="drop-sub">or drag &amp; drop</div>
        </div>
    `;
}

function closeRfOfficeEdit() {
    document.getElementById('rfEditOfficeOverlay').style.display = 'none';
}

function submitRfOfficeEdit() {
    const form = document.getElementById('rfEditOfficeForm');
    const url = form.dataset.action;
    const formData = new FormData(form);
    const saveBtn = document.getElementById('rfEditOfficeSaveBtn');
    const originalBtnHtml = saveBtn.innerHTML;

    ['rfEditOfficeTitleError', 'rfEditOfficeDescError', 'rfEditOfficeImageError'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });
    document.getElementById('rfEditOfficeTitle').classList.remove('input-error');
    document.getElementById('rfEditOfficeDesc').classList.remove('input-error');
    document.getElementById('rfEditOfficeDrop').classList.remove('input-error');

    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';

    fetch(url, {
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
            if (data.errors.title) {
                const el = document.getElementById('rfEditOfficeTitleError');
                el.querySelector('span').textContent = data.errors.title[0];
                el.style.display = 'flex';
                document.getElementById('rfEditOfficeTitle').classList.add('input-error');
            }
            if (data.errors.description) {
                const el = document.getElementById('rfEditOfficeDescError');
                el.querySelector('span').textContent = data.errors.description[0];
                el.style.display = 'flex';
                document.getElementById('rfEditOfficeDesc').classList.add('input-error');
            }
            if (data.errors.image) {
                const el = document.getElementById('rfEditOfficeImageError');
                el.querySelector('span').textContent = data.errors.image[0];
                el.style.display = 'flex';
                document.getElementById('rfEditOfficeDrop').classList.add('input-error');
            }
            return;
        }

        if (!response.ok) {
            throw new Error('Request failed');
        }

        Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: (data && data.message) ? data.message : 'Regional Locations Updated Successfully.',
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
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalBtnHtml;
    });
}
</script>

@endsection