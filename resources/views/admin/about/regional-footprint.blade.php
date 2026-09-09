@extends('admin.layout')

@section('title', 'Regional Footprint')

@section('content')

    @if (session('success'))
        <div style="background:#d4edda;color:#155724;padding:10px 15px;border-radius:6px;margin-bottom:15px;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div style="background:#f8d7da;color:#721c24;padding:10px 15px;border-radius:6px;margin-bottom:15px;">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div style="background:#f8d7da;color:#721c24;padding:10px 15px;border-radius:6px;margin-bottom:15px;">
            <ul style="margin:0;padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Leaflet CSS/JS (free, no API key) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        .rf-search-wrapper{position:relative;max-width:500px;margin-bottom:10px;}
        .rf-search-box{width:100%;padding:10px 14px;border:1px solid #ccc;border-radius:6px;font-size:14px;}
        .rf-search-results{position:absolute;top:100%;left:0;right:0;background:#fff;border:1px solid #ddd;border-top:none;border-radius:0 0 6px 6px;max-height:220px;overflow-y:auto;z-index:1000;display:none;}
        .rf-search-results div{padding:8px 12px;font-size:13px;cursor:pointer;border-bottom:1px solid #f1f1f1;}
        .rf-search-results div:hover{background:#f4f6f9;}

        #rf-map{width:100%;height:420px;border-radius:8px;margin-bottom:20px;z-index:1;}

        .rf-btn{background:#3b3b58;color:#fff;border:none;padding:9px 18px;border-radius:6px;cursor:pointer;font-size:14px;}
        .rf-btn:hover{background:#27273d;}
        .rf-btn:disabled{background:#a9a9c2;cursor:not-allowed;}
        .rf-btn-secondary{background:#6c757d;}
        .rf-btn-danger{background:#e74c3c;}

        .rf-form-group{margin-bottom:12px;}
        .rf-form-group label{display:block;font-size:13px;font-weight:bold;margin-bottom:4px;}
        .rf-form-group input[type=text], .rf-form-group textarea{width:100%;padding:8px;border:1px solid #ccc;border-radius:5px;font-size:14px;}

        .rf-office-row{border:1px solid #e2e2e2;border-radius:8px;padding:15px;margin-bottom:12px;position:relative;background:#fafafa;}
        .rf-office-row .rf-remove-office{position:absolute;top:10px;right:10px;background:#e74c3c;color:#fff;border:none;border-radius:4px;padding:4px 10px;cursor:pointer;font-size:12px;}

        .rf-location-card{border:1px solid #e2e2e2;border-radius:8px;padding:18px;margin-bottom:18px;}
        .rf-location-card h4{margin-bottom:10px;}

        .rf-office-grid{display:flex;flex-wrap:wrap;gap:15px;margin-top:10px;}
        .rf-office-card{width:220px;border:1px solid #eee;border-radius:8px;overflow:hidden;position:relative;}
        .rf-office-card img{width:100%;height:120px;object-fit:cover;}
        .rf-office-card .body{padding:10px;}
        .rf-office-card .body strong{display:block;font-size:14px;margin-bottom:4px;}
        .rf-office-card .body p{font-size:12px;color:#666;}
        .rf-office-card form{position:absolute;top:6px;right:6px;}
        .rf-office-card form button{background:#e74c3c;color:#fff;border:none;border-radius:4px;padding:3px 8px;font-size:11px;cursor:pointer;}

        .rf-add-office-toggle{margin-top:10px;font-size:13px;color:#3b3b58;cursor:pointer;text-decoration:underline;background:none;border:none;padding:0;}
        .rf-mini-form{margin-top:12px;border-top:1px dashed #ddd;padding-top:12px;display:none;}
    </style>

    <h4 style="margin-bottom:15px;">Add New Location</h4>

    <div class="rf-search-wrapper">
        <input id="rf-search" class="rf-search-box" type="text" placeholder="Search a place (e.g. Erbil, Iraq)" autocomplete="off">
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

        <div id="rf-selected-preview" style="display:none;margin-bottom:15px;">
            <strong>Selected location: </strong><span id="rf-selected-label"></span>
        </div>

        <div id="rf-offices-wrapper"></div>

        <div style="margin-bottom:15px;">
            <button type="button" class="rf-btn rf-btn-secondary" id="rf-add-office-btn">+ Add Office</button>
        </div>

        <button type="submit" class="rf-btn" id="rf-submit-location-btn" disabled>Save Location</button>
    </form>

    <hr style="margin:30px 0;">

    <h4 style="margin-bottom:15px;">Existing Locations</h4>

    @forelse ($locations as $location)
        <div class="rf-location-card">
            <h4>{{ $location->title }} <small style="font-weight:normal;color:#888;">({{ $location->latitude }}, {{ $location->longitude }})</small></h4>
            @if ($location->address)
                <p style="color:#777;font-size:13px;">{{ $location->address }}</p>
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
                            <button type="submit">&times;</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <button type="button" class="rf-add-office-toggle" onclick="rfToggleMiniForm({{ $location->id }})">+ Add another office here</button>

            <div class="rf-mini-form" id="rf-mini-form-{{ $location->id }}">
                <form action="{{ route('admin.about.regional-footprint.offices.store', $location) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="rf-form-group">
                        <label>Office Title</label>
                        <input type="text" name="title" required>
                    </div>
                    <div class="rf-form-group">
                        <label>Description</label>
                        <textarea name="description" rows="2"></textarea>
                    </div>
                    <div class="rf-form-group">
                        <label>Image</label>
                        <input type="file" name="image" accept="image/*">
                    </div>
                    <button type="submit" class="rf-btn">Add Office</button>
                </form>
            </div>

            <form action="{{ route('admin.about.regional-footprint.destroy', $location) }}" method="POST" onsubmit="return confirm('Remove this whole location and its offices?');" style="margin-top:12px;">
                @csrf @method('DELETE')
                <button type="submit" class="rf-btn rf-btn-danger">Remove Location</button>
            </form>
        </div>
    @empty
        <p style="color:#888;">No locations added yet.</p>
    @endforelse

    <!-- Hidden template for a new office row in the "add location" form -->
    <template id="rf-office-template">
        <div class="rf-office-row">
            <button type="button" class="rf-remove-office">Remove</button>
            <div class="rf-form-group">
                <label>Office Title</label>
                <input type="text" name="offices[__INDEX__][title]" required>
            </div>
            <div class="rf-form-group">
                <label>Description</label>
                <textarea name="offices[__INDEX__][description]" rows="2"></textarea>
            </div>
            <div class="rf-form-group">
                <label>Image</label>
                <input type="file" name="offices[__INDEX__][image]" accept="image/*">
            </div>
        </div>
    </template>

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

            document.getElementById('rf-selected-preview').style.display = 'block';
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