@extends('admin.layout')
@section('title', $service->exists ? 'Edit Service' : 'Add Service')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
<div class="notice caution" style="margin-bottom:20px;">
    <i class="bi bi-exclamation-triangle" style="font-size:15px;flex-shrink:0;margin-top:1px;"></i>
    <p>Please fill below fields before submitting.</p>
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

<div class="notice caution" id="totalSizeError" style="display:none;">
    <i class="bi bi-exclamation-circle" style="margin-top:1px;"></i>
    <span id="totalSizeErrorText"></span>
</div>

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        <span onclick="window.location='{{ route('admin.home.services') }}'">Services</span>
        <span>&rsaquo;</span>
        <b>{{ $service->exists ? 'Edit Service' : 'Add Service' }}</b>
    </div>

    <div class="header">
        <div>
            <h1>
                <i class="bi bi-{{ $service->exists ? 'pencil-square' : 'plus-circle' }}"></i>
                {{ $service->exists ? 'Edit Service' : 'Add Service' }}
            </h1>
            <p>Build the banner, overview, process steps, and feature highlights for this service page.</p>
        </div>
        <a href="{{ route('admin.home.services') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Back to list
        </a>
    </div>

    <form action="{{ $service->exists ? route('admin.home.services.update', $service->id) : route('admin.home.services.store') }}"
        method="POST" enctype="multipart/form-data" id="serviceForm">
        @csrf
        @if ($service->exists)
        @method('PUT')
        @endif

        {{-- ================= BANNER SECTION ================= --}}
     
            <div class="card">
            <div class="two-col">
                <div class="field" style="margin-bottom:0;">
                    <div class="field-top">
                        <label class="field-label">Banner Title <span class="req">*</span></label>
                    </div>
               


                       <div class="field">
                <input type="text" name="banner_title" value="{{ old('banner_title', $service->banner_title) }}"
                    class="{{ $errors->has('banner_title') ? 'input-error' : '' }}"
                    placeholder="e.g. API Threading Services">
                @error('banner_title')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

                </div>

                <div class="toggle-field">
                    <div class="field-top">
                        <label class="field-label">Show on Home Page</label>
                    </div>
                    <label class="switch-toggle">
                        <input type="checkbox" name="show_on_home" value="1"
                               {{ old('show_on_home', $service->show_on_home) ? 'checked' : '' }}>
                        <span class="switch-slider"></span>
                    </label>
                    <span class="field-hint" style="display:block; margin-top:6px;">Enable to display this service on the homepage.</span>
                    @error('show_on_home')
                        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-card-text"></i></span> Banner Description<span class="req">*</span></h2>
            </div>
            <p class="field-hint">Shown on the homepage banner and listing page under the title.</p>
            <div class="field">
                <textarea name="banner_description" rows="3"
                    class="{{ $errors->has('banner_description') ? 'input-error' : '' }}"
                    placeholder="e.g. API threading and machining solutions for critical oilfield connections.">{{ old('banner_description', $service->banner_description) }}</textarea>
                @error('banner_description')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-image"></i></span> Banner Image<span class="req">*</span></h2>
            </div>

            <div class="notice caution">
                <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                <p><b>Recommended size:</b> 1200 &times; 600px &middot; JPG, PNG, WEBP &middot; up to 10MB.</p>
            </div>

            <div class="image-slot" style="max-width:400px;">
                <div class="drop img-slot {{ $service->banner_image ? 'filled' : '' }} {{ $errors->has('banner_image') ? 'input-error' : '' }}"
                     data-file-input="file-banner-image"    id="drop-banner-image" onclick="handleDropClick(this)">
                    @if ($service->banner_image)
                        <img src="{{ Storage::url($service->banner_image) }}" id="preview-banner-image" alt="Banner image">
                        <button type="button" class="remove-img-btn" onclick="removeUploadedImage(event, this, 'banner-image', 'preview-banner-image')" title="Remove image">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <div class="uploaded-tag"><i class="bi bi-check-circle"></i> Uploaded</div>
                    @else
                        <div class="preview-placeholder" id="preview-banner-image">
                            <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                            <div class="drop-title">Click to upload</div>
                            <div class="drop-sub">or drag &amp; drop</div>
                        </div>
                    @endif
                </div>
                <input type="file" id="file-banner-image" name="banner_image" accept="image/*" hidden
                       onchange="handleImageChange(this, 'preview-banner-image', MAX_IMAGE_BYTES, 'Banner image', 'banner-image')">
                <input type="hidden" name="remove_banner_image" id="remove-banner-image" value="0">
                @if (!$service->banner_image)
                    <button type="button" class="choose-btn" onclick="document.getElementById('file-banner-image').click()">Choose file</button>
                @endif
            </div>
            @error('banner_image')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div> -->



        <div class="card">
    <div class="section-title">
        <h2><span class="icon"><i class="bi bi-image"></i></span> Banner Image or Video<span class="req">*</span></h2>
    </div>

    <div class="notice caution">
        <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
        <p><b>Image:</b> 1200 &times; 600px &middot; JPG, PNG, WEBP &middot; up to 10MB.
           <b>Video:</b> MP4, MOV, WEBM &middot; up to 20MB.</p>
    </div>

    <div class="images-row">
        {{-- Image slot --}}
        <div class="image-slot" style="max-width:400px;">
            <div class="slot-top"><span class="slot-label">Image</span></div>
            <div class="drop img-slot {{ $service->banner_image ? 'filled' : '' }} {{ $errors->has('banner_image') ? 'input-error' : '' }}"
                 data-file-input="file-banner-image" id="drop-banner-image" onclick="handleDropClick(this)">
                @if ($service->banner_image)
                    <img src="{{ Storage::url($service->banner_image) }}" id="preview-banner-image" alt="Banner image">
                    <button type="button" class="remove-img-btn" onclick="removeUploadedImage(event, this, 'banner-image', 'preview-banner-image')" title="Remove image">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <div class="uploaded-tag"><i class="bi bi-check-circle"></i> Uploaded</div>
                @else
                    <div class="preview-placeholder" id="preview-banner-image">
                        <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                        <div class="drop-title">Click to upload</div>
                        <div class="drop-sub">or drag &amp; drop</div>
                    </div>
                @endif
            </div>
            <input type="file" id="file-banner-image" name="banner_image" accept="image/*" hidden
                   onchange="handleImageChange(this, 'preview-banner-image', MAX_IMAGE_BYTES, 'Banner image', 'banner-image')">
            <input type="hidden" name="remove_banner_image" id="remove-banner-image" value="0">
            @if (!$service->banner_image)
                <button type="button" class="choose-btn" onclick="document.getElementById('file-banner-image').click()">Choose file</button>
            @endif
            @error('banner_image')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>

        {{-- Video slot --}}
        <div class="image-slot" style="max-width:400px;">
            <div class="slot-top"><span class="slot-label">Video</span></div>
            <div class="video-drop {{ $service->banner_video ? 'has-file filled' : '' }} {{ $errors->has('banner_video') ? 'input-error' : '' }}"
                 id="drop-banner-video" onclick="handleVideoDropClick(this)">
                @if ($service->banner_video)
                    <video src="{{ Storage::url($service->banner_video) }}" muted playsinline preload="metadata"></video>
                    <button type="button" class="remove-img-btn" onclick="removeUploadedVideo(event)" title="Remove video">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <div class="uploaded-tag"><i class="bi bi-camera-video-fill"></i> Uploaded</div>
                @else
                    <div class="preview-placeholder" id="preview-banner-video">
                        <div class="ico-circle"><i class="bi bi-camera-video" style="color:#AEB4C4;font-size:18px;"></i></div>
                        <div class="drop-title">Click to upload</div>
                        <div class="drop-sub">or drag &amp; drop</div>
                    </div>
                @endif
            </div>
            <input type="file" id="file-banner-video" name="banner_video" accept="video/*" hidden
                   onchange="showBannerVideoFileName(this)">
            <input type="hidden" name="remove_banner_video" id="remove-banner_video" value="0">
            @error('banner_video')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

        {{-- ================= OVERVIEW SECTION ================= --}}
        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-type"></i></span> Overview Title <span class="req">*</span></h2>
            </div>
            <div class="field">
                <input type="text" name="overview_title" value="{{ old('overview_title', $service->overview_title) }}"
                    class="{{ $errors->has('overview_title') ? 'input-error' : '' }}"
                    placeholder="e.g. Service Overview">
                @error('overview_title')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-card-text"></i></span> Overview Description <span class="req">*</span></h2>
            </div>
            <div class="field">
                <textarea name="overview_description" rows="4"
                    class="{{ $errors->has('overview_description') ? 'input-error' : '' }}"
                    placeholder="Describe the service overview...">{{ old('overview_description', $service->overview_description) }}</textarea>
                @error('overview_description')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-image"></i></span> Overview Image <span class="req">*</span></h2>
            </div>

            <div class="notice caution">
                <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
                <p><b>Recommended size:</b> 1100 &times; 340px &middot; JPG, PNG, WEBP &middot; up to 10MB.</p>
            </div>

            <div class="image-slot" style="max-width:400px;">
                <div class="drop img-slot {{ $service->overview_image ? 'filled' : '' }} {{ $errors->has('overview_image') ? 'input-error' : '' }}"
                     data-file-input="file-overview-image"      id="drop-overview-image"  onclick="handleDropClick(this)">
                    @if ($service->overview_image)
                        <img src="{{ Storage::url($service->overview_image) }}" id="preview-overview-image" alt="Overview image">
                        <button type="button" class="remove-img-btn" onclick="removeUploadedImage(event, this, 'overview-image', 'preview-overview-image')" title="Remove image">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <div class="uploaded-tag"><i class="bi bi-check-circle"></i> Uploaded</div>
                    @else
                        <div class="preview-placeholder" id="preview-overview-image">
                            <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                            <div class="drop-title">Click to upload</div>
                            <div class="drop-sub">or drag &amp; drop</div>
                        </div>
                    @endif
                </div>
                <input type="file" id="file-overview-image" name="overview_image" accept="image/*" hidden
                       onchange="handleImageChange(this, 'preview-overview-image', MAX_IMAGE_BYTES, 'Overview image', 'overview-image')">
                <input type="hidden" name="remove_overview_image" id="remove-overview-image" value="0">
                @if (!$service->overview_image)
                    <button type="button" class="choose-btn" onclick="document.getElementById('file-overview-image').click()">Choose file</button>
                @endif
            </div>
            @error('overview_image')
            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>

        {{-- ================= PROCESS SECTION ================= --}}
        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-camera-reels"></i></span> Process <span class="req">*</span></h2>
            </div>
<p class="field-hint">Detail page &mdash; step-by-step process, each with a description and a video file OR a YouTube link. Add as many as needed.</p>
            <div id="processRows"></div>
            <button type="button" id="addProcessBtn" class="btn-add-row">
                <i class="bi bi-plus-lg"></i> Add Row
            </button>

            @error('process')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
            @if ($errors->has('process.*.description') || $errors->has('process.*.video'))
                <span class="field-error">
                    <i class="bi bi-exclamation-circle"></i> Please check the description/video for each process step.
                </span>
            @endif
        </div>

        {{-- ================= FEATURES SECTION ================= --}}
        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-type"></i></span> Features Heading <span class="req">*</span></h2>
            </div>
            <div class="field">
                <input type="text" name="features_heading" value="{{ old('features_heading', $service->features_heading) }}"
                    class="{{ $errors->has('features_heading') ? 'input-error' : '' }}"
                    placeholder="e.g. Why Choose Us">
                @error('features_heading')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-grid-3x3-gap"></i></span> Features (max 4) <span class="req">*</span></h2>
            </div>
            <p class="field-hint">Each feature has an icon, a title, and a short description. Maximum 4 features.</p>

            <div id="featureRows"></div>
            <button type="button" id="addFeatureBtn" class="btn-add-row">
                <i class="bi bi-plus-lg"></i> Add Feature
            </button>
            <p class="field-hint limit-msg" id="featureLimitMsg" style="display:none;">
                You can add a maximum of 4 features.
            </p>

            @error('features')
                <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
            @if ($errors->has('features.*.title') || $errors->has('features.*.description') || $errors->has('features.*.icon'))
                <span class="field-error">
                    <i class="bi bi-exclamation-circle"></i> Please check the icon/title/description for each feature.
                </span>
            @endif
        </div>

        {{-- ================= GALLERY SECTION ================= --}}
<div class="card">
    <div class="section-title">
        <h2><span class="icon"><i class="bi bi-images"></i></span> Gallery</h2>
    </div>
    <p class="field-hint">Add extra images to showcase for this service. Click a tile to upload, use the × to remove.</p>

    <div class="notice caution">
        <i class="bi bi-exclamation-triangle" style="margin-top:1px;"></i>
        <p><b>Recommended size:</b> 800 &times; 800px &middot; JPG, PNG, WEBP &middot; up to 10MB each.</p>
    </div>

    <div class="gallery-grid" id="galleryGrid"></div>
    <button type="button" id="addGalleryBtn" class="btn-add-row">
        <i class="bi bi-plus-lg"></i> Add Image
    </button>

    @error('gallery')
        <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
    @enderror
    @if ($errors->has('gallery.*.image'))
        <span class="field-error">
            <i class="bi bi-exclamation-circle"></i> Please check each gallery image.
        </span>
    @endif
</div>

        {{-- ================= META TITLE ================= --}}
<div class="card">
    <div class="section-title">
        <h2><span class="icon"><i class="bi bi-type"></i></span> Meta Title</h2>
    </div>
 
    <div class="field">
        <input type="text" name="meta_title"
               value="{{ old('meta_title', $service->meta_title) }}"
               class="{{ $errors->has('meta_title') ? 'input-error' : '' }}"
               placeholder="e.g. API Threading Services | Company Name" maxlength="255">
        @error('meta_title')
            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>
</div>

{{-- ================= META DESCRIPTION ================= --}}
<div class="card">
    <div class="section-title">
        <h2><span class="icon"><i class="bi bi-card-text"></i></span> Meta Description</h2>
    </div>
    <div class="field">
        <textarea name="meta_description" rows="3"
                  class="{{ $errors->has('meta_description') ? 'input-error' : '' }}"
                  placeholder="Enter meta description for search engines">{{ old('meta_description', $service->meta_description) }}</textarea>
        @error('meta_description')
            <span class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>
</div>

        <div class="savebar">
            <div class="savebar-inner">
                <span class="savebar-status">All changes save to the live Services page</span>
                <div class="btn-group">
                    <a href="{{ route('admin.home.services') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-save">
                        <i class="bi bi-check-lg"></i>
                        {{ $service->exists ? 'Update' : 'Save' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- ===== Templates ===== --}}

<template id="processRowTemplate">
    <div class="inspection-row">
        <div class="inspection-row-fields">
            <div class="field">
                <div class="field-top"><label class="field-label">Description</label></div>
                <textarea name="process[__INDEX__][description]" rows="3" placeholder="Describe this process step..."></textarea>
            </div>

            <div class="field">
                <div class="field-top"><label class="field-label">Thumbnail</label></div>
                <div class="image-slot row-slot">
                    <div class="drop img-slot" data-row-slot="thumbnail">
                        <div class="preview-placeholder process-thumb-preview">
                            <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:16px;"></i></div>
                            <div class="drop-title">Click to upload</div>
                        </div>
                    </div>
                    <input type="file" name="process[__INDEX__][thumbnail]" accept="image/*" hidden
                           onchange="previewProcessThumbnail(this)">
                    <input type="hidden" name="process[__INDEX__][existing_thumbnail]" class="existing-thumbnail-input" value="">
                </div>
            </div>
<div class="field">
                <div class="field-top"><label class="field-label">Video</label></div>
                <div class="video-slot" id="video-drop-wrap">
                    <div class="video-drop" data-row-slot="video">
                        <div class="ico-circle"><i class="bi bi-camera-video" style="color:#AEB4C4;font-size:16px;"></i></div>
                        <div class="drop-title">Click to upload</div>
                    </div>
                    <input type="file" name="process[__INDEX__][video]" accept="video/*" hidden
                           onchange="previewProcessVideo(this)">
                    <input type="hidden" name="process[__INDEX__][existing_video]" class="existing-video-input" value="">
                </div>
                <div class="field" style="margin-top:8px;">
                    <input type="text" name="process[__INDEX__][vedio_link]" class="process-vedio-link-input"
                           placeholder="Or paste a YouTube link" oninput="onProcessLinkInput(this)">
                </div>
            </div>
        </div>
        <button type="button" class="btn-remove-row inspection-remove"><i class="bi bi-trash3"></i></button>
    </div>
</template>

<template id="featureRowTemplate">
    <div class="inspection-row">
        <div class="inspection-row-fields feature-row-fields">
            <div class="field">
                <div class="field-top"><label class="field-label">Icon</label></div>
                <div class="image-slot row-slot">
                    <div class="drop img-slot" data-row-slot="icon">
                        <div class="preview-placeholder feature-preview">
                            <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:16px;"></i></div>
                            <div class="drop-title">Click to upload</div>
                        </div>
                    </div>
                    <input type="file" name="features[__INDEX__][icon]" accept="image/*" hidden
                           onchange="previewFeatureIcon(this)">
                    <input type="hidden" name="features[__INDEX__][existing_icon]" class="existing-icon-input" value="">
                </div>
            </div>
            <div class="field">
                <div class="field-top"><label class="field-label">Title</label></div>
                <input type="text" name="features[__INDEX__][title]" placeholder="e.g. Certified Personnel">
            </div>
            <div class="field">
                <div class="field-top"><label class="field-label">Description</label></div>
                <textarea name="features[__INDEX__][description]" rows="3" placeholder="Short description..."></textarea>
            </div>
        </div>
        <button type="button" class="btn-remove-row inspection-remove"><i class="bi bi-trash3"></i></button>
    </div>
</template>
<template id="galleryRowTemplate">
    <div class="gallery-tile">
        <div class="drop img-slot" data-row-slot="gallery-image">
            <div class="preview-placeholder gallery-preview">
                <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:16px;"></i></div>
                <div class="drop-title">Click to upload</div>
            </div>
        </div>
        <input type="file" name="gallery[__INDEX__][image]" accept="image/*" hidden
               onchange="previewGalleryImage(this)">
        <input type="hidden" name="gallery[__INDEX__][existing_image]" class="existing-gallery-input" value="">
    </div>
</template>
<style>
    .crumbs{ display:flex; align-items:center; gap:8px; font-size:13px; color: var(--faint,#9AA1B2); margin-bottom:10px; }
    .crumbs b{ color: var(--ink,#171B2C); font-weight:600; }
    .crumbs span:first-child{ cursor:pointer; transition:color .15s; }
    .crumbs span:first-child:hover{ color: var(--orange,#EF7B2E); }

    .header{ display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:28px; gap:16px; flex-wrap:wrap; }
    .header h1{ display:flex; align-items:center; gap:9px; font-size:22px; font-weight:700; letter-spacing:-0.02em; margin:0; color: var(--ink,#171B2C); }
    .header p{ font-size:13.5px; color: var(--muted,#667085); margin:7px 0 0; max-width:560px; line-height:1.55; }

    .btn-back{
        display:flex; align-items:center; gap:6px; color: var(--muted,#667085); text-decoration:none;
        font-size:13.5px; font-weight:600; padding:9px 14px; border-radius:9px; transition:background .15s, color .15s;
    }
    .btn-back:hover{ background: var(--canvas,#F6F7FB); color: var(--ink,#171B2C); }

    .section-title{ display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; flex-wrap:wrap; gap:6px; }
    .section-title h2{ display:flex; align-items:center; gap:8px; font-size:14px; font-weight:700; margin:0; color: var(--ink,#171B2C); }
    .icon{ display:inline-flex; color: var(--orange,#EF7B2E); }
    .req{ color: var(--orange, #EF7B2E); }

    .field{ margin-bottom:0; }
    .field-top{ margin-bottom:8px; }
    .field-label{ font-size:13px; font-weight:600; color: var(--ink,#171B2C); }
    .field-hint{ font-size:12.5px; color: var(--faint,#9AA1B2); margin:0 0 14px; }

    input[type=text], textarea{
        width:100%; border:1px solid var(--input-border,#DBDFEA); border-radius:10px;
        padding:11px 14px; font-size:14px; font-family:inherit; color: var(--ink,#171B2C);
        outline:none; transition:box-shadow .15s, border-color .15s; resize:vertical; background:#fff;
    }
    input[type=text]:focus, textarea:focus{
        border-color: var(--orange,#EF7B2E);
        box-shadow: 0 0 0 4px var(--orange-tint-strong,#FFE9D8);
    }
    .input-error{ border-color:#E9483F !important; background:#FFF5F4; }
    .field-error{ display:flex; align-items:center; gap:5px; color:#D5392F; font-size:12.5px; margin-top:6px; }

    .notice{ display:flex; align-items:flex-start; gap:8px; background: var(--canvas,#F6F7FB); border-radius:10px; padding:10px 12px; margin-bottom:16px; }
    .notice.caution{ background:#FFF8E8; border:1px solid #F5E3B3; }
    .notice.caution i{ color:#B7791F; }
    .notice.caution p{ color:#8A6116; margin:0; font-size:12.5px; }
    .notice.caution p b{ color:#6B4A0E; font-weight:700; }

    /* ===== Image upload slot (drag-and-drop style, matches About Section) ===== */
    .image-slot{ width:100%; }
    .drop.img-slot{
        position:relative; aspect-ratio:4/3; width:100%; border-radius:12px;
        border:2px dashed var(--input-border,#DBDFEA); background:#FAFBFD;
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        cursor:pointer; overflow:hidden; transition:border-color .15s, background .15s; text-align:center;
    }
    .drop.img-slot:hover{ border-color: var(--orange,#EF7B2E); background: var(--orange-tint,#FFF8F3); }
    .drop.img-slot.filled{ border:2px solid transparent; background:#0F1220; cursor:default; }
    .drop.img-slot img{ width:100%; height:100%; object-fit:cover; display:block; }
    .drop.img-slot.input-error{ border-color:#E9483F; background:#FFF5F4; }

    .ico-circle{ width:40px; height:40px; border-radius:999px; background:#EEF0F6; display:flex; align-items:center; justify-content:center; margin-bottom:8px; }
    .drop-title{ font-size:12px; font-weight:500; color: var(--muted,#667085); }
    .drop-sub{ font-size:11px; color:#B0B5C4; margin-top:2px; }
    .uploaded-tag{
        position:absolute; left:0; right:0; bottom:0; padding:8px 12px;
        background:linear-gradient(to top, rgba(0,0,0,0.55), transparent);
        color:rgba(255,255,255,0.9); font-size:11px; display:flex; align-items:center; gap:4px;
    }
    .choose-btn{
        margin-top:8px; width:100%; font-size:12px; font-weight:600; color: var(--orange,#EF7B2E);
        background:#fff; border:1px solid var(--orange-border,#F3D8C2); border-radius:8px;
        padding:7px 0; cursor:pointer; transition:background .15s;
    }
    .choose-btn:hover{ background: var(--orange-tint,#FFF8F3); }
    .remove-img-btn{
        position:absolute; top:8px; right:8px; width:28px; height:28px; border-radius:999px;
        background:rgba(0,0,0,0.6); border:none; color:#fff; display:flex; align-items:center;
        justify-content:center; cursor:pointer; transition:background .15s; z-index:3; font-size:13px;
    }
    .remove-img-btn:hover{ background:rgba(0,0,0,0.85); }

    /* ===== Small row-based image/video slots (Process thumbnail/video, Feature icon) ===== */
  .row-slot .drop.img-slot{ aspect-ratio:1/1; height:110px; }
.feature-row-fields .row-slot .drop.img-slot{ height:90px; }
.row-slot .drop-title{ font-size:11px; }
.row-slot .ico-circle{ width:30px; height:30px; margin-bottom:4px; }

.row-slot .drop.img-slot img{
    width:100% !important;
    height:100% !important;
    object-fit:cover !important;
    max-width:none !important;
    display:block;
}

    .video-slot{ position:relative; width:100%; }
    .video-drop{
        position:relative; border-radius:12px;
        border:2px dashed var(--input-border,#DBDFEA); background:#FAFBFD;
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        cursor:pointer; overflow:hidden; transition:border-color .15s, background .15s; text-align:center;
    }
    .video-drop:hover{ border-color: var(--orange,#EF7B2E); background: var(--orange-tint,#FFF8F3); }
    .video-drop.has-file{ border-style:solid; border-color: var(--green,#12875A); background: var(--green-tint,#E9F8EF); }
    .video-drop.has-file .drop-title{ color: var(--green,#12875A); font-weight:600; word-break:break-all; padding:0 8px; }
    .video-drop .remove-img-btn{ top:4px; right:4px; width:22px; height:22px; font-size:10px; }

    /* ===== Add / remove row buttons ===== */
    .btn-add-row{
        display:inline-flex; align-items:center; gap:6px; background: var(--orange,#EF7B2E); color:#fff;
        border:none; padding:9px 16px; border-radius:9px; font-size:12.5px; font-weight:600; cursor:pointer;
        margin-top:4px; transition:background .15s ease;
    }
    .btn-add-row:hover{ background: var(--orange-deep,#DA6A20); }
    .btn-add-row[style*="pointer-events: none"]{ opacity:.5; }

    .limit-msg{ color:#D5392F !important; margin-top:8px; }

    .btn-remove-row{
        display:flex; align-items:center; justify-content:center; width:36px; height:36px;
        border-radius:9px; border:1px solid #F5D3D0; background:#FFF5F4; color:#D5392F; cursor:pointer;
        transition:background .15s ease;
    }
    .btn-remove-row:hover{ background:#FBD5D5; }

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

    /* ===== Dynamic rows (process / feature) ===== */
    .inspection-row{
        display:flex; gap:14px; align-items:stretch; background: var(--canvas,#F6F7FB);
        border:1px solid var(--line,#E9EBF2); border-radius:12px; padding:18px; margin-bottom:14px;
        position:relative; padding-right:56px;
    }

    /* .inspection-row-fields{ flex:1; display:grid; grid-template-columns:1fr 200px 220px; gap:16px; align-items:start; } */

    .inspection-row-fields{ flex:1; display:grid; grid-template-columns:1fr 200px 220px; gap:16px; align-items:start; }

    .feature-row-fields{ grid-template-columns:140px 1fr 1fr; }

    .inspection-row-fields textarea{ min-height:110px; }

    .inspection-row .btn-remove-row{
        position:absolute; top:18px; right:14px; width:34px; height:34px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center; padding:0; line-height:1;
    }
    .inspection-row .btn-remove-row i{ display:block; font-size:15px; line-height:1; }

    @media (max-width:900px){
        .inspection-row-fields, .feature-row-fields{ grid-template-columns:1fr; }
        .inspection-row{ padding-right:18px; }
        .inspection-row .btn-remove-row{ position:static; margin-top:12px; }
    }

        .two-col{ display:grid; grid-template-columns:1fr 220px; gap:24px; align-items:start; }
    @media (max-width:700px){ .two-col{ grid-template-columns:1fr; } }

        .toggle-field{ padding-top:2px; }

           /* ===== Toggle switch (orange, matching this design system) ===== */
    .switch-toggle{ position:relative; display:inline-block; width:46px; height:26px; }
    .switch-toggle input{ opacity:0; width:0; height:0; }
    .switch-slider{
        position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0;
        background:#DBDFEA; border-radius:26px; transition:.25s;
    }
    .switch-slider::before{
        position:absolute; content:""; height:20px; width:20px; left:3px; bottom:3px;
        background:#fff; border-radius:50%; transition:.25s; box-shadow:0 1px 2px rgba(0,0,0,0.15);
    }
    .switch-toggle input:checked + .switch-slider{ background: var(--orange,#EF7B2E); }
    .switch-toggle input:checked + .switch-slider::before{ transform:translateX(20px); }

    .video-drop{
    position:relative; aspect-ratio:4/3; width:100%; border-radius:12px;
    border:2px dashed var(--input-border,#DBDFEA); background:#FAFBFD;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    cursor:pointer; overflow:hidden; transition:border-color .15s, background .15s; text-align:center;
}
.video-drop:hover{ border-color: var(--orange,#EF7B2E); background: var(--orange-tint,#FFF8F3); }
.video-drop.filled{ border:2px solid transparent; cursor:default; }
.video-drop.input-error{ border-color:#E9483F; background:#FFF5F4; }
.video-drop video{ width:100%; height:100%; object-fit:cover; display:block; background:#0F1220; }
.video-drop .uploaded-tag{
    position:absolute; left:0; right:0; bottom:0; padding:8px 12px;
    background:linear-gradient(to top, rgba(0,0,0,0.55), transparent);
    color:rgba(255,255,255,0.9); font-size:11px; display:flex; align-items:center; gap:4px; pointer-events:none;
}

.images-row{ display:flex; gap:16px; flex-wrap:wrap; align-items:flex-start; }
.slot-top{ display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; }
.slot-label{ font-size:12px; font-weight:500; color: var(--muted,#667085); }

.images-row > .image-slot {
    flex: 1 1 400px;
    max-width: 400px;
    width: 100%;
}

.video-slot .video-drop.filled,
.video-slot .video-drop.has-file {
    border-style: solid;
    border-color: var(--green,#12875A);
    cursor: default;
}

.video-slot .video-drop video {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    display: block;
}
.gallery-grid{
    display:flex; flex-wrap:wrap; gap:14px; margin-bottom:12px;
}
.gallery-tile{
    position:relative; width:140px;
}
.gallery-tile .drop.img-slot{
    aspect-ratio:1/1; height:140px; width:140px; border-radius:12px;
}
.gallery-tile .remove-img-btn{
    position:absolute; top:6px; right:6px;
}
</style>

@php
    $processForJs = collect(old('process', $service->process ?? []))
    ->values()
    ->map(function ($row, $i) use ($errors) {
        $row = is_array($row) ? $row : [];

        if (!empty($row['video'])) {
            $row['video_url'] = Storage::url($row['video']);
        }

        if (!empty($row['thumbnail'])) {
            $row['thumbnail_url'] = Storage::url($row['thumbnail']);
        }

        $row['errors'] = [
            'description' => $errors->first("process.$i.description"),
            'video' => $errors->first("process.$i.video"),
        ];

        return $row;
    })
    ->values()
    ->toArray();

    $featuresForJs = collect(old('features', $service->features ?? []))
        ->values()
        ->map(function ($row, $i) use ($errors) {
            $row = is_array($row) ? $row : [];

            if (!empty($row['icon'])) {
                $row['icon_url'] = Storage::url($row['icon']);
            }

            $row['errors'] = [
                'icon' => $errors->first("features.$i.icon"),
                'title' => $errors->first("features.$i.title"),
                'description' => $errors->first("features.$i.description"),
            ];

            return $row;
        })
        ->values()
        ->toArray();

        $galleryForJs = collect(old('gallery', $service->gallery ?? []))
    ->values()
    ->map(function ($row, $i) use ($errors) {
        $row = is_array($row) ? $row : [];

        if (!empty($row['image'])) {
            $row['image_url'] = Storage::url($row['image']);
        }

        $row['errors'] = [
            'image' => $errors->first("gallery.$i.image"),
        ];

        return $row;
    })
    ->values()
    ->toArray();
@endphp
<script>
    // ===== Size limits (must match ServiceRequest validation rules) =====
    const MAX_IMAGE_BYTES = 10 * 1024 * 1024;
    const MAX_ICON_BYTES  = 5 * 1024 * 1024;
    const MAX_VIDEO_BYTES = 20 * 1024 * 1024;
    const MAX_TOTAL_BYTES = 95 * 1024 * 1024;

    function formatBytes(bytes) {
        return (bytes / (1024 * 1024)).toFixed(1) + 'MB';
    }

    function handleDropClick(el) {
        if (el.classList.contains('filled')) return;
        const inputId = el.getAttribute('data-file-input');
        const input = document.getElementById(inputId);
        if (input) input.click();
    }

    function setFieldError(anchor, message, isClientError = true) {
        if (!anchor) return;
        clearFieldError(anchor);
        if (!message) return;
        anchor.classList.add('input-error');
        const span = document.createElement('span');
        span.className = 'field-error' + (isClientError ? ' client-error' : '');
        span.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
        anchor.insertAdjacentElement('afterend', span);
    }

    function clearFieldError(anchor) {
        if (!anchor) return;
        anchor.classList.remove('input-error');
        const next = anchor.nextElementSibling;
        if (next && next.classList.contains('field-error') && next.classList.contains('client-error')) {
            next.remove();
        }
    }

    function validateFileSize(input, maxBytes, label) {
        if (input.files && input.files[0] && input.files[0].size > maxBytes) {
            Swal.fire({
                icon: 'warning',
                title: 'File too large',
                text: `${label} must be smaller than ${formatBytes(maxBytes)} (selected file is ${formatBytes(input.files[0].size)}).`,
                confirmButtonColor: '#EF7B2E'
            });
            input.value = '';
            return false;
        }
        return true;
    }

    function handleImageChange(input, previewId, maxBytes, label, fieldName) {
        if (!validateFileSize(input, maxBytes, label)) return;

        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.id = previewId;
                preview.replaceWith(img);

                const drop = img.closest('.drop');
                if (drop) {
                    drop.classList.add('filled');
                    drop.classList.remove('input-error');
                    const chooseBtn = drop.parentElement.querySelector('.choose-btn');
                    if (chooseBtn) chooseBtn.style.display = 'none';

                    const removeInput = document.getElementById(`remove-${fieldName}`);
                    if (removeInput) removeInput.value = '0';

                    if (!drop.querySelector('.remove-img-btn')) {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'remove-img-btn';
                        btn.title = 'Remove image';
                        btn.innerHTML = '<i class="bi bi-x-lg"></i>';
                        btn.onclick = (ev) => removeUploadedImage(ev, btn, fieldName, previewId);
                        drop.appendChild(btn);
                    }
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeUploadedImage(event, btn, fieldName, previewId) {
        event.stopPropagation();
        const drop = btn.closest('.drop');
        const wrapper = drop.parentElement;
        const fileInput = wrapper.querySelector('input[type="file"]');
        const removeInput = document.getElementById(`remove-${fieldName}`);

        if (removeInput) removeInput.value = '1';
        if (fileInput) fileInput.value = '';

        drop.classList.remove('filled');
        drop.innerHTML = `
            <div class="preview-placeholder" id="${previewId}">
                <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:18px;"></i></div>
                <div class="drop-title">Click to upload</div>
                <div class="drop-sub">or drag &amp; drop</div>
            </div>
        `;

        let chooseBtn = wrapper.querySelector('.choose-btn');
        if (!chooseBtn && fileInput) {
            chooseBtn = document.createElement('button');
            chooseBtn.type = 'button';
            chooseBtn.className = 'choose-btn';
            chooseBtn.textContent = 'Choose file';
            chooseBtn.onclick = () => fileInput.click();
            wrapper.appendChild(chooseBtn);
        } else if (chooseBtn) {
            chooseBtn.style.display = 'block';
        }
    }

    // ===== Process rows =====
    const existingProcess = @json($processForJs);
    const processContainer = document.getElementById('processRows');
    const processTemplate = document.getElementById('processRowTemplate');
    let processIndex = 0;

    function addProcessRow(data = {}) {
        const clone = processTemplate.content.cloneNode(true);
        const row = clone.querySelector('.inspection-row');

        row.querySelectorAll('input, textarea').forEach(field => {
            field.name = field.name.replace('__INDEX__', processIndex);
        });

        const descField = row.querySelector('textarea[name$="[description]"]');
        descField.value = data.description ?? '';

        const thumbDrop = row.querySelector('[data-row-slot="thumbnail"]');
        const thumbInput = row.querySelector('input[type="file"][name$="[thumbnail]"]');
        thumbDrop.addEventListener('click', () => thumbInput.click());

        if (data.thumbnail) {
            thumbDrop.classList.add('filled');
            thumbDrop.innerHTML = `
                <img src="${data.thumbnail_url ?? data.thumbnail}" alt="Thumbnail">
                <button type="button" class="remove-img-btn" title="Remove"><i class="bi bi-x-lg"></i></button>
            `;
            row.querySelector('.existing-thumbnail-input').value = data.thumbnail;
            wireRowRemove(thumbDrop, row.querySelector('.existing-thumbnail-input'), 'process-thumb-preview');
        }

        const videoDrop = row.querySelector('[data-row-slot="video"]');
        const videoInput = row.querySelector('input[type="file"][name$="[video]"]');
        const linkInput = row.querySelector('.process-vedio-link-input');

        videoDrop.addEventListener('click', () => {
            if (!videoDrop.classList.contains('filled')) videoInput.click();
        });

        if (data.video) {
            videoDrop.classList.add('has-file', 'filled');
            videoDrop.innerHTML = `
                <video src="${data.video_url ?? data.video}" controls muted style="width:100%;height:100%;object-fit:cover;"></video>
                <button type="button" class="remove-img-btn" title="Remove"><i class="bi bi-x-lg"></i></button>
            `;
            row.querySelector('.existing-video-input').value = data.video;
            wireRowRemove(videoDrop, row.querySelector('.existing-video-input'), null, true);
        } else if (data.vedio_link) {
            linkInput.value = data.vedio_link;
            markVideoDropAsLinked(videoDrop, videoInput, linkInput);
        }

        if (data.errors) {
            if (data.errors.description) setFieldError(descField, data.errors.description, false);
            if (data.errors.video) setFieldError(videoDrop, data.errors.video, false);
            if (data.errors.vedio_link) setFieldError(linkInput, data.errors.vedio_link, false);
        }

        row.querySelector('.inspection-remove').addEventListener('click', () => row.remove());

        processContainer.appendChild(row);
        processIndex++;
    }

    // function markVideoDropAsLinked(videoDrop, videoInput, linkInput) {
    //     videoDrop.classList.add('has-file', 'filled');
    //     videoDrop.innerHTML = `
    //         <div class="drop-title" style="padding:10px; display:flex; align-items:center; gap:6px;">
    //             <i class="bi bi-youtube"></i> YouTube link set
    //         </div>
    //         <button type="button" class="remove-img-btn" title="Remove link"><i class="bi bi-x-lg"></i></button>
    //     `;
    //     videoDrop.querySelector('.remove-img-btn').onclick = function (ev) {
    //         ev.stopPropagation();
    //         linkInput.value = '';
    //         videoInput.value = '';
    //         videoDrop.classList.remove('has-file', 'filled');
    //         videoDrop.innerHTML = `
    //             <div class="ico-circle"><i class="bi bi-camera-video" style="color:#AEB4C4;font-size:16px;"></i></div>
    //             <div class="drop-title">Click to upload</div>
    //         `;
    //     };
    // }

    function toYoutubeEmbedUrl(url) {
        const match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/);
        return match ? `https://www.youtube.com/embed/${match[1]}` : url;
    }

    function markVideoDropAsLinked(videoDrop, videoInput, linkInput) {
        const embedUrl = toYoutubeEmbedUrl(linkInput.value.trim());

        videoDrop.classList.add('has-file', 'filled');
        videoDrop.innerHTML = `
            <iframe src="${embedUrl}" style="width:100%; height:100%; border:0;" allowfullscreen></iframe>
            <button type="button" class="remove-img-btn" title="Remove link"><i class="bi bi-x-lg"></i></button>
        `;
        videoDrop.querySelector('.remove-img-btn').onclick = function (ev) {
            ev.stopPropagation();
            linkInput.value = '';
            videoInput.value = '';
            videoDrop.classList.remove('has-file', 'filled');
            videoDrop.innerHTML = `
                <div class="ico-circle"><i class="bi bi-camera-video" style="color:#AEB4C4;font-size:16px;"></i></div>
                <div class="drop-title">Click to upload</div>
            `;
        };
    }

    function onProcessLinkInput(linkInput) {
        const wrapper = linkInput.closest('.inspection-row-fields') || linkInput.closest('.field').parentElement;
        const videoDrop = wrapper.querySelector('[data-row-slot="video"]');
        const videoInput = wrapper.querySelector('input[type="file"][name$="[video]"]');
        const existingVideoInput = wrapper.querySelector('.existing-video-input');

        if (linkInput.value.trim() !== '') {
            videoInput.value = '';
            if (existingVideoInput) existingVideoInput.value = '';
            markVideoDropAsLinked(videoDrop, videoInput, linkInput);
        } else {
            videoDrop.classList.remove('has-file', 'filled');
            videoDrop.innerHTML = `
                <div class="ico-circle"><i class="bi bi-camera-video" style="color:#AEB4C4;font-size:16px;"></i></div>
                <div class="drop-title">Click to upload</div>
            `;
        }
    }

    function wireRowRemove(dropEl, existingInput, placeholderClass, isVideo = false) {
        const btn = dropEl.querySelector('.remove-img-btn');
        if (!btn) return;
        btn.onclick = function (ev) {
            ev.stopPropagation();
            const fileInput = dropEl.parentElement.querySelector('input[type="file"]');
            fileInput.value = '';
            if (existingInput) existingInput.value = '';

            if (isVideo) {
                const videoEl = dropEl.querySelector('video');
                if (videoEl && videoEl.src.startsWith('blob:')) {
                    URL.revokeObjectURL(videoEl.src);
                }
                dropEl.classList.remove('has-file', 'filled');
                dropEl.innerHTML = `
                    <div class="ico-circle"><i class="bi bi-camera-video" style="color:#AEB4C4;font-size:16px;"></i></div>
                    <div class="drop-title">Click to upload</div>
                `;
            } else {
                dropEl.classList.remove('filled');
                dropEl.innerHTML = `
                    <div class="preview-placeholder ${placeholderClass}">
                        <div class="ico-circle"><i class="bi bi-image" style="color:#AEB4C4;font-size:16px;"></i></div>
                        <div class="drop-title">Click to upload</div>
                    </div>
                `;
            }
        };
    }

    function previewProcessThumbnail(input) {
        if (!validateFileSize(input, MAX_IMAGE_BYTES, 'Process thumbnail')) return;

        const wrapper = input.closest('.image-slot');
        const dropEl = wrapper.querySelector('[data-row-slot="thumbnail"]');
        const oldError = wrapper.querySelector('.field-error');
        if (oldError) oldError.remove();

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                dropEl.classList.add('filled');
                dropEl.classList.remove('input-error');
                dropEl.innerHTML = `
                    <img src="${e.target.result}" alt="Thumbnail">
                    <button type="button" class="remove-img-btn" title="Remove"><i class="bi bi-x-lg"></i></button>
                `;
                const existingInput = wrapper.querySelector('.existing-thumbnail-input');
                wireRowRemove(dropEl, existingInput, 'process-thumb-preview');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewProcessVideo(input) {
        if (!validateFileSize(input, MAX_VIDEO_BYTES, 'Process video')) return;

        const wrapper = input.closest('.video-slot');
        const dropEl = wrapper.querySelector('[data-row-slot="video"]');
        const oldError = wrapper.querySelector('.field-error');
        if (oldError) oldError.remove();

        const row = wrapper.closest('.inspection-row');
        const linkInput = row ? row.querySelector('.process-vedio-link-input') : null;
        if (linkInput) linkInput.value = '';

        if (input.files && input.files[0]) {
            const videoURL = URL.createObjectURL(input.files[0]);

            dropEl.classList.add('has-file', 'filled');
            dropEl.classList.remove('input-error');
            dropEl.innerHTML = `
                <video src="${videoURL}" controls muted style="width:100%;height:100%;object-fit:cover;"></video>
                <button type="button" class="remove-img-btn" title="Remove"><i class="bi bi-x-lg"></i></button>
            `;
            const existingInput = wrapper.querySelector('.existing-video-input');
            wireRowRemove(dropEl, existingInput, null, true);
        }
    }

    document.getElementById('addProcessBtn').addEventListener('click', () => addProcessRow());

    if (existingProcess.length > 0) {
        existingProcess.forEach(row => addProcessRow(row));
    } else {
        addProcessRow();
    }

    // ===== Feature rows =====
    const MAX_FEATURES = 4;
    const existingFeatures = @json($featuresForJs);
    const featureContainer = document.getElementById('featureRows');
    const featureTemplate = document.getElementById('featureRowTemplate');
    const addFeatureBtn = document.getElementById('addFeatureBtn');
    const featureLimitMsg = document.getElementById('featureLimitMsg');
    let featureIndex = 0;

    function updateFeatureButtonState() {
        const count = featureContainer.querySelectorAll('.inspection-row').length;
        const atLimit = count >= MAX_FEATURES;
        addFeatureBtn.style.opacity = atLimit ? '0.5' : '1';
        addFeatureBtn.style.pointerEvents = atLimit ? 'none' : 'auto';
        featureLimitMsg.style.display = atLimit ? 'block' : 'none';
    }

    function addFeatureRow(data = {}) {
        if (featureContainer.querySelectorAll('.inspection-row').length >= MAX_FEATURES) {
            updateFeatureButtonState();
            return;
        }

        const clone = featureTemplate.content.cloneNode(true);
        const row = clone.querySelector('.inspection-row');

        row.querySelectorAll('input, textarea').forEach(field => {
            field.name = field.name.replace('__INDEX__', featureIndex);
        });

        const titleField = row.querySelector('input[name$="[title]"]');
        const descField = row.querySelector('textarea[name$="[description]"]');
        titleField.value = data.title ?? '';
        descField.value = data.description ?? '';

        const iconDrop = row.querySelector('[data-row-slot="icon"]');
        const iconInput = row.querySelector('input[type="file"][name$="[icon]"]');
        const existingIconInput = row.querySelector('.existing-icon-input');

        iconDrop.addEventListener('click', () => iconInput.click());

        if (data.icon) {
            iconDrop.classList.add('filled');
            iconDrop.innerHTML = `
                <img src="${data.icon_url ?? data.icon}" alt="Icon">
                <button type="button" class="remove-img-btn" title="Remove"><i class="bi bi-x-lg"></i></button>
            `;
            existingIconInput.value = data.icon;
            wireRowRemove(iconDrop, existingIconInput, 'feature-preview');
        }

        if (data.errors) {
            if (data.errors.title) setFieldError(titleField, data.errors.title, false);
            if (data.errors.description) setFieldError(descField, data.errors.description, false);
            if (data.errors.icon) setFieldError(iconDrop, data.errors.icon, false);
        }

        row.querySelector('.inspection-remove').addEventListener('click', () => {
            row.remove();
            updateFeatureButtonState();
        });

        featureContainer.appendChild(row);
        featureIndex++;
        updateFeatureButtonState();
    }

    addFeatureBtn.addEventListener('click', () => addFeatureRow());

    if (existingFeatures.length > 0) {
        existingFeatures.slice(0, MAX_FEATURES).forEach(row => addFeatureRow(row));
    } else {
        addFeatureRow();
    }

    function previewFeatureIcon(input) {
        if (!validateFileSize(input, MAX_ICON_BYTES, 'Feature icon')) return;

        const wrapper = input.closest('.image-slot');
        const dropEl = wrapper.querySelector('[data-row-slot="icon"]');
        const oldError = wrapper.querySelector('.field-error');
        if (oldError) oldError.remove();

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                dropEl.classList.add('filled');
                dropEl.classList.remove('input-error');
                dropEl.innerHTML = `
                    <img src="${e.target.result}" alt="Icon">
                    <button type="button" class="remove-img-btn" title="Remove"><i class="bi bi-x-lg"></i></button>
                `;
                const existingInput = wrapper.querySelector('.existing-icon-input');
                wireRowRemove(dropEl, existingInput, 'feature-preview');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // ===== Banner video =====
    function handleVideoDropClick(el) {
        if (el.classList.contains('filled')) return;
        document.getElementById('file-banner-video').click();
    }

    function showBannerVideoFileName(input) {
        if (!validateFileSize(input, MAX_VIDEO_BYTES, 'Banner video')) return;

        const drop = document.getElementById('drop-banner-video');
        const file = input.files && input.files[0];
        if (!file) return;

        const videoURL = URL.createObjectURL(file);

        drop.classList.add('has-file', 'filled');
        drop.classList.remove('input-error');
        drop.innerHTML = `
            <video src="${videoURL}" muted playsinline preload="metadata"></video>
            <button type="button" class="remove-img-btn" onclick="removeUploadedVideo(event)" title="Remove video">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="uploaded-tag"><i class="bi bi-camera-video-fill"></i> Uploaded</div>
        `;
        document.getElementById('remove-banner_video').value = '0';
    }

    function removeUploadedVideo(event) {
        event.stopPropagation();
        const drop = document.getElementById('drop-banner-video');
        const fileInput = document.getElementById('file-banner-video');
        const removeInput = document.getElementById('remove-banner_video');

        const existingVideo = drop.querySelector('video');
        if (existingVideo && existingVideo.src.startsWith('blob:')) {
            URL.revokeObjectURL(existingVideo.src);
        }

        if (removeInput) removeInput.value = '1';
        if (fileInput) fileInput.value = '';

        drop.classList.remove('has-file', 'filled');
        drop.innerHTML = `
            <div class="preview-placeholder" id="preview-banner-video">
                <div class="ico-circle"><i class="bi bi-camera-video" style="color:#AEB4C4;font-size:18px;"></i></div>
                <div class="drop-title">Click to upload</div>
                <div class="drop-sub">or drag &amp; drop</div>
            </div>
        `;
    }

    // ===== AJAX submit =====
    document.getElementById('serviceForm').addEventListener('submit', function (e) {
        e.preventDefault();
        submitServiceForm();
    });

    function submitServiceForm() {
        const form = document.getElementById('serviceForm');
        let totalBytes = 0;
        form.querySelectorAll('input[type="file"]').forEach(input => {
            if (input.files && input.files[0]) totalBytes += input.files[0].size;
        });

        const totalErrorBox = document.getElementById('totalSizeError');
        const totalErrorText = document.getElementById('totalSizeErrorText');

        if (totalBytes > MAX_TOTAL_BYTES) {
            totalErrorText.textContent =
                `Total upload size (${formatBytes(totalBytes)}) exceeds the ${formatBytes(MAX_TOTAL_BYTES)} limit for this form. ` +
                `Please remove or replace some images/videos before saving.`;
            totalErrorBox.style.display = 'flex';
            totalErrorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        totalErrorBox.style.display = 'none';

        const formData = new FormData(form);
        const submitBtn = form.querySelector('.btn-save');
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
                showServiceValidationErrors(data.errors);
                return;
            }

            if (!response.ok) {
                throw new Error('Request failed');
            }

            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: (data && data.message) ? data.message : 'Service page updated successfully.',
                confirmButtonColor: '#EF7B2E',
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                window.location.href = (data && data.redirect) ? data.redirect : "{{ route('admin.home.services') }}";
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

    function showServiceValidationErrors(errors) {
        const form = document.getElementById('serviceForm');

        const topLevelMap = {
            banner_title: f => f.querySelector('[name="banner_title"]'),
            banner_description: f => f.querySelector('[name="banner_description"]'),
            banner_image: f => document.getElementById('drop-banner-image'),
            banner_video: f => document.getElementById('drop-banner-video'),
            overview_title: f => f.querySelector('[name="overview_title"]'),
            overview_description: f => f.querySelector('[name="overview_description"]'),
            overview_image: f => document.getElementById('drop-overview-image'),
            features_heading: f => f.querySelector('[name="features_heading"]'),
            meta_title: f => f.querySelector('[name="meta_title"]'),
            meta_description: f => f.querySelector('[name="meta_description"]'),
            show_on_home: f => f.querySelector('[name="show_on_home"]'),
        };

        Object.keys(errors).forEach(field => {
            const message = errors[field][0];

            let m = field.match(/^process\.(\d+)\.(\w+)$/);
            if (m) {
                const [, idx, sub] = m;
                const input = form.querySelector(`[name="process[${idx}][${sub}]"]`);
                if (!input) return;
                const target = (sub === 'thumbnail' || sub === 'video')
                    ? input.previousElementSibling
                    : input;
                target.classList.add('input-error');
                const errorEl = document.createElement('span');
                errorEl.className = 'field-error';
                errorEl.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
                target.insertAdjacentElement('afterend', errorEl);
                return;
            }

            m = field.match(/^features\.(\d+)\.(\w+)$/);
            if (m) {
                const [, idx, sub] = m;
                const input = form.querySelector(`[name="features[${idx}][${sub}]"]`);
                if (!input) return;
                const target = sub === 'icon' ? input.previousElementSibling : input;
                target.classList.add('input-error');
                const errorEl = document.createElement('span');
                errorEl.className = 'field-error';
                errorEl.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
                target.insertAdjacentElement('afterend', errorEl);
                return;
            }

            m = field.match(/^gallery\.(\d+)\.(\w+)$/);
            if (m) {
                const [, idx, sub] = m;
                const input = form.querySelector(`[name="gallery[${idx}][${sub}]"]`);
                if (!input) return;
                const target = sub === 'image' ? input.previousElementSibling : input;
                target.classList.add('input-error');
                const errorEl = document.createElement('span');
                errorEl.className = 'field-error';
                errorEl.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
                target.insertAdjacentElement('afterend', errorEl);
                return;
            }

            if (field === 'process') {
                const container = document.getElementById('processRows');
                const errorEl = document.createElement('span');
                errorEl.className = 'field-error';
                errorEl.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
                container.insertAdjacentElement('afterend', errorEl);
                return;
            }
            if (field === 'features') {
                const container = document.getElementById('featureRows');
                const errorEl = document.createElement('span');
                errorEl.className = 'field-error';
                errorEl.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
                container.insertAdjacentElement('afterend', errorEl);
                return;
            }
            if (field === 'gallery') {
                const container = document.getElementById('galleryGrid');
                const errorEl = document.createElement('span');
                errorEl.className = 'field-error';
                errorEl.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
                container.insertAdjacentElement('afterend', errorEl);
                return;
            }

            const target = topLevelMap[field] ? topLevelMap[field](form) : null;
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

    // ===== Gallery rows =====
    const existingGallery = @json($galleryForJs);
    const galleryGrid = document.getElementById('galleryGrid');
    const galleryTemplate = document.getElementById('galleryRowTemplate');
    let galleryIndex = 0;

    function addGalleryRow(data = {}) {
        const clone = galleryTemplate.content.cloneNode(true);
        const tile = clone.querySelector('.gallery-tile');

        tile.querySelectorAll('input').forEach(field => {
            field.name = field.name.replace('__INDEX__', galleryIndex);
        });

        const dropEl = tile.querySelector('[data-row-slot="gallery-image"]');
        const fileInput = tile.querySelector('input[type="file"]');
        const existingInput = tile.querySelector('.existing-gallery-input');

        dropEl.addEventListener('click', () => {
            if (!dropEl.classList.contains('filled')) fileInput.click();
        });

        if (data.image) {
            dropEl.classList.add('filled');
            dropEl.innerHTML = `
                <img src="${data.image_url ?? data.image}" alt="Gallery image">
                <button type="button" class="remove-img-btn" title="Remove"><i class="bi bi-x-lg"></i></button>
            `;
            existingInput.value = data.image;
            wireGalleryRemove(dropEl, existingInput, tile);
        }

        if (data.errors && data.errors.image) {
            setFieldError(dropEl, data.errors.image, false);
        }

        galleryGrid.appendChild(tile);
        galleryIndex++;
    }

    function wireGalleryRemove(dropEl, existingInput, tile) {
        const btn = dropEl.querySelector('.remove-img-btn');
        if (!btn) return;
        btn.onclick = function (ev) {
            ev.stopPropagation();
            tile.remove();
        };
    }

    function previewGalleryImage(input) {
        if (!validateFileSize(input, MAX_IMAGE_BYTES, 'Gallery image')) return;

        const tile = input.closest('.gallery-tile');
        const dropEl = tile.querySelector('[data-row-slot="gallery-image"]');
        const oldError = tile.querySelector('.field-error');
        if (oldError) oldError.remove();

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                dropEl.classList.add('filled');
                dropEl.classList.remove('input-error');
                dropEl.innerHTML = `
                    <img src="${e.target.result}" alt="Gallery image">
                    <button type="button" class="remove-img-btn" title="Remove"><i class="bi bi-x-lg"></i></button>
                `;
                const existingInput = tile.querySelector('.existing-gallery-input');
                wireGalleryRemove(dropEl, existingInput, tile);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('addGalleryBtn').addEventListener('click', () => addGalleryRow());

    if (existingGallery.length > 0) {
        existingGallery.forEach(row => addGalleryRow(row));
    }
</script>

<style>
    @keyframes errorFlash {
        0%, 100% { box-shadow: 0 0 0 4px rgba(233, 72, 63, 0.35); }
        50% { box-shadow: 0 0 0 4px rgba(233, 72, 63, 0.1); }
    }
    .error-flash {
        animation: errorFlash 0.6s ease-in-out 2;
    }
</style>

@endsection