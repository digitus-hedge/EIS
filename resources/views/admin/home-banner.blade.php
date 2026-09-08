@extends('admin.layout')
@section('title', 'Home - Banner Section')
@section('content')

    @if (session('success'))
        <div style="background:#d4edda; color:#155724; padding:10px 15px; border-radius:6px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background:#f8d7da; color:#721c24; padding:10px 15px; border-radius:6px; margin-bottom:20px;">
            <ul style="margin-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h4 style="margin-bottom:15px;">Add New Banner</h4>

    <form action="{{ route('admin.home.banner.store') }}" method="POST" enctype="multipart/form-data" style="margin-bottom:40px;">
        @csrf

        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px; font-weight:600;">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" required
                   style="width:100%; max-width:500px; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px; font-weight:600;">Description</label>
            <textarea name="description" rows="4"
                      style="width:100%; max-width:500px; padding:10px; border:1px solid #ccc; border-radius:6px;">{{ old('description') }}</textarea>
        </div>

        <div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:15px;">
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600;">Image 1</label>
                <input type="file" name="image_1" accept="image/*">
            </div>
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600;">Image 2</label>
                <input type="file" name="image_2" accept="image/*">
            </div>
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600;">Image 3</label>
                <input type="file" name="image_3" accept="image/*">
            </div>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:600;">Video</label>
            <input type="file" name="video" accept="video/*">
        </div>

        <button type="submit"
                style="background:#3b3b58; color:#fff; padding:10px 22px; border:none; border-radius:6px; cursor:pointer;">
            Save Banner
        </button>
    </form>

    <hr style="margin-bottom:25px;">

    <h4 style="margin-bottom:15px;">All Banners</h4>

    <div style="display:flex; flex-direction:column; gap:20px;">
        @forelse ($banners as $banner)
            <div style="border:1px solid #eee; border-radius:8px; padding:15px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <h5>{{ $banner->title }}</h5>
                        <p style="color:#666; font-size:14px; margin-top:5px;">{{ $banner->description }}</p>
                    </div>
                    <form action="{{ route('admin.home.banner.destroy', $banner->id) }}" method="POST"
                          onsubmit="return confirm('Delete this banner?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="background:#e74c3c; color:#fff; border:none; padding:6px 14px; border-radius:5px; cursor:pointer;">
                            Delete
                        </button>
                    </form>
                </div>

                <div style="display:flex; gap:10px; margin-top:12px; flex-wrap:wrap;">
                    @foreach (['image_1', 'image_2', 'image_3'] as $field)
                        @if ($banner->{$field})
                            <img src="{{ Storage::url($banner->{$field}) }}"
                                 style="width:150px; height:75px; object-fit:cover; border-radius:6px;">
                        @endif
                    @endforeach

                    @if ($banner->video)
                        <video src="{{ Storage::url($banner->video) }}" controls
                               style="width:200px; height:110px; border-radius:6px;"></video>
                    @endif
                </div>
            </div>
        @empty
            <p style="color:#888;">No banners added yet.</p>
        @endforelse
    </div>

@endsection