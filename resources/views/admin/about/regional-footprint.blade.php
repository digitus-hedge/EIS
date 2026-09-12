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
            <h2><span class="icon"><i class="bi bi-geo-alt"></i></span> Add New Location</h2>
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

    <div class="card">
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
                            <form action="{{ route('admin.about.regional-footprint.offices.destroy', $office) }}" method="POST" onsubmit="return confirm('Remove this office?');">
                                @csrf @method('DELETE')
                                <button type="submit"><i class="bi bi-x-lg"></i></button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="rf-add-office-toggle" onclick="rfToggleMiniForm({{ $location->id }})">
                    <i class="bi bi-plus-lg"></i> Add another office here
                </button>

                <div class="rf-mini-form" id="rf-mini-form-{{ $location->id }}">
                    <form action="{{ route('admin.about.regional-footprint.offices.store', $location) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="field">
                            <div class="field-top"><label class="field-label">Office Title</label></div>
                            <input type="text" name="title" required>
                        </div>
                        <div class="field" style="margin-top:12px;">
                            <div class="field-top"><label class="field-label">Description</label></div>
                            <textarea name="description" rows="2"></textarea>
                        </div>
                        <div class="field" style="margin-top:12px;">
                            <div class="field-top"><label class="field-label">Image</label></div>
                            <input type="file" name="image" accept="image/*">
                        </div>
                        <button type="submit" class="btn-secondary-pill" style="margin-top:14px;">
                            <i class="bi bi-plus-lg"></i> Add Office
                        </button>
                    </form>
                </div>

                <form action="{{ route('admin.about.regional-footprint.destroy', $location) }}" method="POST" onsubmit="return confirm('Remove this whole location and its offices?');" style="margin-top:14px;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger-pill">
                        <i class="bi bi-trash3"></i> Remove Location
                    </button>
                </form>
            </div>
        @empty
            <p class="field-hint">No locations added yet.</p>
        @endforelse
    </div>
</div>

<!-- Hidden template for a new office row in the "add location" form -->
<template id="rf-office-template">
    <div class="rf-office-row">
        <button type="button" class="rf-remove-office" title="Remove"><i class="bi bi-trash3"></i></button>
        <div class="field">
            <div class="field-top"><label class="field-label">Office Title</label></div>
            <input type="text" name="offices[__INDEX__][title]" required>
        </div>
        <div class="field" style="margin-top:12px;">
            <div class="field-top"><label class="field-label">Description</label></div>
            <textarea name="offices[__INDEX__][description]" rows="2"></textarea>
        </div>
        <div class="field" style="margin-top:12px; margin-bottom:0;">
            <div class="field-top"><label class="field-label">Image</label></div>
            <input type="file" name="offices[__INDEX__][image]" accept="image/*">
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
    .notice.caution{ background:#FFF8E8; border:1px solid #F5E3B3; }
    .notice.caution i{ color:#B7791F; }
    .notice.caution p{ color:#8A6116; margin:0; font-size:12px; }

    /* ===== Search box ===== */
    .rf-search-wrapper{ position:relative; max-width:520px; margin-bottom:14px; }
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

@endsection