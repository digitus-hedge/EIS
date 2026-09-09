@extends('admin.layout')

@section('title', 'Operations')

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

    <style>
        .op-btn{background:#3b3b58;color:#fff;border:none;padding:9px 18px;border-radius:6px;cursor:pointer;font-size:14px;}
        .op-btn:hover{background:#27273d;}
        .op-btn-danger{background:#e74c3c;}
        .op-form-group{margin-bottom:14px;max-width:600px;}
        .op-form-group label{display:block;font-size:13px;font-weight:bold;margin-bottom:4px;}
        .op-form-group input[type=text], .op-form-group textarea{width:100%;padding:8px;border:1px solid #ccc;border-radius:5px;font-size:14px;}
        .op-checkbox-row{display:flex;align-items:center;gap:8px;margin-bottom:14px;}
        .op-video-grid{display:flex;flex-wrap:wrap;gap:18px;margin-top:20px;}
        .op-video-card{width:260px;border:1px solid #eee;border-radius:8px;overflow:hidden;position:relative;background:#fafafa;}
        .op-video-card img{width:100%;height:140px;object-fit:cover;background:#ddd;}
        .op-video-card .body{padding:12px;}
        .op-video-card .body strong{display:block;font-size:14px;margin-bottom:4px;}
        .op-video-card .body p{font-size:12px;color:#666;margin:0 0 8px;}
        .op-main-badge{position:absolute;top:8px;left:8px;background:var(--orange, #E8792D);background:#E8792D;color:#fff;font-size:11px;font-weight:bold;padding:3px 8px;border-radius:4px;}
        .op-video-card form{margin-top:6px;}
        .op-video-card form button{background:#e74c3c;color:#fff;border:none;border-radius:4px;padding:5px 10px;font-size:12px;cursor:pointer;}
        .op-video-tag{display:inline-block;font-size:11px;color:#888;margin-bottom:6px;}
    </style>

    <h4 style="margin-bottom:15px;">Add New Video</h4>

    <form action="{{ route('admin.about.operation.videos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="op-form-group">
            <label>Title</label>
            <input type="text" name="title" required>
        </div>
        <div class="op-form-group">
            <label>Description</label>
            <textarea name="description" rows="3"></textarea>
        </div>
        <div class="op-form-group">
            <label>Thumbnail Image</label>
            <input type="file" name="thumbnail" accept="image/*">
        </div>
        <div class="op-form-group">
            <label>Video File (mp4, mov, or webm — max 50MB)</label>
            <input type="file" name="video" accept="video/*">
        </div>
        <button type="submit" class="op-btn">Add Video</button>
    </form>

    <h4 style="margin-bottom:15px;">Existing Videos</h4>

    <div class="op-video-grid">
        @forelse ($videos as $video)
            <div class="op-video-card">
                @if ($video->thumbnail)
                    <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="{{ $video->title }}">
                @endif
                <div class="body">
                    <strong>{{ $video->title }}</strong>
                    <p>{{ $video->description }}</p>
                    @if ($video->video)
                        <span class="op-video-tag">🎬 Video attached</span>
                    @else
                        <span class="op-video-tag" style="color:#c0392b;">No video file uploaded</span>
                    @endif

                    <form action="{{ route('admin.about.operation.videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Remove this video?');">
                        @csrf @method('DELETE')
                        <button type="submit">Remove</button>
                    </form>
                </div>
            </div>
        @empty
            <p style="color:#888;">No videos added yet.</p>
        @endforelse
    </div>

@endsection