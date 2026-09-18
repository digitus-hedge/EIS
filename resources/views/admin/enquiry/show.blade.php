@extends('admin.layout')
@section('title', 'Enquiry Details')
@section('content')

<div class="form-header">
    <h4><i class="bi bi-envelope-open"></i> Enquiry from {{ $enquiry->full_name }}</h4>
    <a href="{{ route('admin.home.enquiries') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Back to list
    </a>
</div>

<div class="form-card">
    <div class="detail-row">
        <span class="detail-label">Full Name</span>
        <span class="detail-value">{{ $enquiry->full_name }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Email</span>
        <span class="detail-value">{{ $enquiry->email }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Phone</span>
        <span class="detail-value">{{ $enquiry->phone_number ?: '—' }}</span>
    </div>

     <div class="detail-row">
        <span class="detail-label">Address</span>
        <span class="detail-value">{{ $enquiry->address ?: '—' }}</span>
    </div>

    <div class="detail-row">
        <span class="detail-label">Comments</span>
        <span class="detail-value">{{ $enquiry->comments ?: '—' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Received</span>
        <span class="detail-value">{{ $enquiry->created_at->format('M d, Y h:i A') }}</span>
    </div>
    <!-- <div class="detail-row" style="border-bottom: none;">
        <span class="detail-label">Message</span>
        <p class="detail-message">{{ $enquiry->message }}</p>
    </div> -->
</div>

<style>
    .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
    .form-header h4 { display: flex; align-items: center; gap: 8px; color: #1e1e2d; }
    .btn-back { display: flex; align-items: center; gap: 6px; color: #3b3b58; text-decoration: none; font-size: 14px; }
    .btn-back:hover { text-decoration: underline; }
    .form-card { background: #fff; padding: 28px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
    .detail-row { display: flex; padding: 14px 0; border-bottom: 1px solid #f0f0f0; }
    .detail-label { width: 140px; flex-shrink: 0; font-weight: 600; font-size: 13.5px; color: #555; }
    .detail-value { font-size: 14px; color: #1e1e2d; }
    .detail-message { margin: 8px 0 0; font-size: 14.5px; line-height: 1.7; color: #333; white-space: pre-wrap; }
</style>

@endsection
