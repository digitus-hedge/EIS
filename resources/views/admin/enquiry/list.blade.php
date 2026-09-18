@extends('admin.layout')
@section('title', 'Enquiries')
@section('content')

@if (session('success'))
<div class="alert alert-success">
    <i class="bi bi-check-circle"></i> {{ session('success') }}
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="list-header">
    <h4>Enquiries</h4>
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('admin.home.enquiries') }}" class="search-form">
        <div class="search-input">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, email, or subject...">
        </div>
        @if ($search)
        <a href="{{ route('admin.home.enquiries') }}" class="clear-search" title="Clear search">
            <i class="bi bi-x-circle"></i>
        </a>
        @endif
        <button type="submit" class="btn-search">Search</button>
    </form>

    <form method="GET" action="{{ route('admin.home.enquiries') }}" class="per-page-form" id="perPageForm">
        @if ($search)
        <input type="hidden" name="search" value="{{ $search }}">
        @endif
        <label for="per_page">Show</label>
        <select name="per_page" id="per_page" onchange="document.getElementById('perPageForm').submit()">
            @foreach ([10, 25, 50, 100] as $option)
            <option value="{{ $option }}" {{ (int) $perPage === $option ? 'selected' : '' }}>{{ $option }}</option>
            @endforeach
        </select>
        <span>entries</span>
    </form>
</div>

<div class="table-wrapper">
    <table class="styled-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Message</th>
                <th>Received</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($enquiries as $enquiry)
            <tr class="{{ !$enquiry->is_read ? 'unread-row' : '' }}">
                <td class="title-cell">
                 
                    {{ $enquiry->full_name }}
                </td>
                <td>{{ $enquiry->email }}</td>
                <td>{{ $enquiry->phone_number ?: '—' }}</td>
                <td class="desc-cell">{{ Str::limit($enquiry->comments, 40) ?: '—' }}</td>
                <td>{{ $enquiry->created_at->format('M d, Y h:i A') }}</td>
                <td class="text-right">
                    <div class="action-icons">
                        <a href="{{ route('admin.home.enquiries.show', $enquiry->id) }}" class="icon-btn icon-edit" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <form action="{{ route('admin.home.enquiries.destroy', $enquiry->id) }}" method="POST"
                            id="delete-form-{{ $enquiry->id }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="icon-btn icon-delete btn-delete-trigger"
                                data-form-id="delete-form-{{ $enquiry->id }}"
                                data-name="{{ $enquiry->full_name }}" title="Delete">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty-row">
                    <i class="bi bi-inbox"></i>
                    <p>No enquiries received yet.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrapper">
    <div class="pagination-info">
        Showing {{ $enquiries->firstItem() ?? 0 }} to {{ $enquiries->lastItem() ?? 0 }} of {{ $enquiries->total() }} entries
    </div>
    {{ $enquiries->links() }}
</div>

<style>
    .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; font-size: 14px; }
    .alert-success { background: #d4edda; color: #155724; }
    .list-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .list-header h4 { color: #1e1e2d; }
    .toolbar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 18px; }
    .search-form { display: flex; align-items: center; gap: 8px; }
    .search-input { position: relative; display: flex; align-items: center; }
    .search-input i { position: absolute; left: 12px; color: #999; font-size: 14px; }
    .search-input input { padding: 9px 12px 9px 35px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; width: 260px; outline: none; }
    .search-input input:focus { border-color: #3b3b58; }
    .clear-search { color: #999; font-size: 18px; text-decoration: none; display: flex; align-items: center; }
    .clear-search:hover { color: #e74c3c; }
    .btn-search { background: #3b3b58; color: #fff; border: none; padding: 9px 16px; border-radius: 6px; font-size: 14px; cursor: pointer; }
    .btn-search:hover { background: #2b2b42; }
    .per-page-form { display: flex; align-items: center; gap: 8px; font-size: 14px; color: #555; }
    .per-page-form select { padding: 7px 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; outline: none; }
    .table-wrapper { background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); }
    .styled-table { width: 100%; border-collapse: collapse; }
    .styled-table thead tr { background: #f4f6f9; text-align: left; }
    .styled-table th { padding: 14px 16px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; color: #666; border-bottom: 1px solid #eee; }
    .styled-table td { padding: 14px 16px; border-bottom: 1px solid #f0f0f0; font-size: 14px; vertical-align: middle; }
    .styled-table tbody tr:hover { background: #fafbfc; }
    .unread-row { background: #fdf7f0; font-weight: 600; }
    .unread-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #b40707; margin-right: 8px; }
    .text-right { text-align: right; }
    .title-cell { font-weight: 600; color: #1e1e2d; }
    .desc-cell { color: #777; max-width: 280px; }
    .action-icons { display: flex; gap: 8px; justify-content: flex-end; }
    .icon-btn { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 6px; border: none; cursor: pointer; font-size: 15px; text-decoration: none; transition: all 0.2s ease; }
    .icon-edit { background: #eaf1fb; color: #2c6fbb; }
    .icon-edit:hover { background: #2c6fbb; color: #fff; }
    .icon-delete { background: #fdecea; color: #c0392b; }
    .icon-delete:hover { background: #c0392b; color: #fff; }
    .empty-row { text-align: center; padding: 50px 20px; color: #aaa; }
    .empty-row i { font-size: 36px; display: block; margin-bottom: 10px; }
    .pagination-wrapper { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
    .pagination-info { font-size: 13px; color: #888; }
</style>

<script>
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-trigger');
        if (!btn) return;

        const formId = btn.dataset.formId;
        const name = btn.dataset.name;

        Swal.fire({
            title: 'Are you sure?',
            html: `Do you really want to delete the enquiry from <strong>"${name}"</strong>?<br>This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-trash3"></i> Yes, delete it',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    });
</script>

@endsection
