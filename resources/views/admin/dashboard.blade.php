@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')

<div class="dash-header">
    <h1>Dashboard Overview</h1>
    <p>Welcome to your admin dashboard. Quick access to all master data.</p>
</div>

<div class="dash-grid">

    <a href="{{ route('admin.home.services') }}" class="dash-card">
        <div class="dash-card-icon"><i class="bi bi-pencil-square"></i></div>
        <div class="dash-card-body">
            <span class="dash-card-count">{{ $counts['services'] }}</span>
            <span class="dash-card-label">Services</span>
        </div>
    </a>

  <a href="{{ route('admin.about.operation') }}#existing-videos-section" class="dash-card">
    <div class="dash-card-icon"><i class="bi bi-camera-video"></i></div>
    <div class="dash-card-body">
        <span class="dash-card-count">{{ $counts['operations'] }}</span>
        <span class="dash-card-label">Operations</span>
    </div>
</a>

   <a href="{{ route('admin.about.regional-footprint') }}#existing-locations-section" class="dash-card">
    <div class="dash-card-icon"><i class="bi bi-cpu"></i></div>
    <div class="dash-card-body">
        <span class="dash-card-count">{{ $counts['regionals'] }}</span>
        <span class="dash-card-label">Locations</span>
    </div>
</a>
    

    <a href="{{ route('admin.about.certificates') }}#existing-certificates-section" class="dash-card">
        <div class="dash-card-icon"><i class="bi bi-patch-check"></i></div>
        <div class="dash-card-body">
            <span class="dash-card-count">{{ $counts['certificates'] }}</span>
            <span class="dash-card-label">Certificates</span>
        </div>
    </a>

</div>

<style>
    .dash-header {
        margin-bottom: 28px;
    }

    .dash-header h1 {
        font-size: 25px;
        font-weight: 700;
        letter-spacing: -0.02em;
        margin: 0;
        color: #171B2C;
    }

    .dash-header p {
        font-size: 13.5px;
        color: #667085;
        margin: 7px 0 0;
    }

    .dash-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .dash-card {
        display: flex;
        align-items: center;
        gap: 16px;
        background: linear-gradient(95deg, #EF7B2E 0%, #b5632ed6 100%);
        border-radius: 14px;
        padding: 22px;
        text-decoration: none;
        box-shadow: 0 10px 26px -10px rgb(239 123 46 / 50%);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .dash-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 34px -10px rgba(180, 7, 7, 0.5);
    }

    .dash-card-icon {
        width: 52px;
        height: 52px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        color: #fff;
        font-size: 22px;
    }

    .dash-card-body {
        display: flex;
        flex-direction: column;
    }

    .dash-card-count {
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        line-height: 1.1;
    }

    .dash-card-label {
        font-size: 13px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.85);
        margin-top: 4px;
    }

    @media (max-width: 1200px) {
        .dash-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 900px) {
        .dash-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 560px) {
        .dash-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@endsection
