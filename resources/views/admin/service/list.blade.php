@extends('admin.layout')
@section('title', 'Service Cards')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Done!',
            text: @json(session('success')),
            confirmButtonColor: '#EF7B2E',
            timer: 2200,
            timerProgressBar: true
        });
    });
</script>
@endif

<div class="wrap">
    <div class="crumbs">
        <span onclick="window.location='{{ route('admin.dashboard') }}'">Home</span>
        <span>&rsaquo;</span>
        <b>Service Cards</b>
    </div>

    <div class="page-header">
        <div>
            <h1>Service Cards</h1>
            <p>These are the services shown in the Services section of your homepage.</p>
        </div>
        <a href="{{ route('admin.home.services.create') }}" class="btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Service
        </a>
    </div>

    <div class="card">
        <div class="toolbar">
            <form method="GET" action="{{ route('admin.home.services') }}" class="search-box" id="searchForm">
                <i class="bi bi-search"></i>
                <input type="text" name="search" id="searchInput" value="{{ $search }}" placeholder="Search by title..." autocomplete="off">
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                @if ($search)
                    <a href="{{ route('admin.home.services') }}" class="clear-search" title="Clear search">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </form>

            <form method="GET" action="{{ route('admin.home.services') }}" class="entries-select" id="perPageForm">
                @if ($search)
                    <input type="hidden" name="search" value="{{ $search }}">
                @endif
                Show
                <select name="per_page" id="per_page" onchange="document.getElementById('perPageForm').submit()">
                    @foreach ([10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ (int) $perPage === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
                entries
            </form>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                    <tr>
                        <td>
                            <div class="thumb-wrap">
                                @if ($service->banner_image)
                                    <img src="{{ Storage::url($service->banner_image) }}" alt="{{ $service->banner_title }}">
                                @else
                                    <i class="bi bi-image" style="color:var(--faint,#9AA1B2);"></i>
                                @endif
                            </div>
                        </td>
                        <td class="title-cell">
                            {{ $service->banner_title }}
                            @if ($service->show_on_home ?? false)
                                <span class="status-pill">Live</span>
                            @endif
                        </td>
                        <td class="desc-cell">
                            <div class="desc-clamp">{{ Str::limit($service->banner_description, 90) ?: '—' }}</div>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="{{ route('admin.home.services.edit', $service->id) }}" class="icon-btn edit" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.home.services.destroy', $service->id) }}" method="POST"
                                      id="delete-form-{{ $service->id }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="icon-btn delete btn-delete-trigger"
                                            data-form-id="delete-form-{{ $service->id }}"
                                            data-name="{{ $service->banner_title }}" title="Delete">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                        {{-- Empty state rendered outside <tbody> below --}}
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($services->isEmpty())
        <div class="empty-state">
            <div class="ico"><i class="bi bi-search" style="color:var(--faint,#9AA1B2); font-size:22px;"></i></div>
            <h3>No services found</h3>
            <p>Try a different search term, or add a new service.</p>
        </div>
        @else
        <div class="table-footer">
            <span class="count">Showing {{ $services->firstItem() ?? 0 }} to {{ $services->lastItem() ?? 0 }} of {{ $services->total() }} entries</span>
            <div class="pagination-wrap">
                {{ $services->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-trigger');
        if (!btn) return;

        const formId = btn.dataset.formId;
        const name = btn.dataset.name;

        Swal.fire({
            title: 'Are you sure?',
            html: `Do you really want to delete <strong>"${name}"</strong>?<br>This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
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

    // ===== Auto-search: submit the search form automatically as the user types =====
    // Debounced so it doesn't fire a request on every single keystroke — waits until
    // typing pauses briefly, which keeps it feeling instant without hammering the server.
    (function () {
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        if (!searchInput || !searchForm) return;

        let debounceTimer;
        const DEBOUNCE_MS = 450;

        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                searchForm.requestSubmit ? searchForm.requestSubmit() : searchForm.submit();
            }, DEBOUNCE_MS);
        });

        // Pressing Enter should search immediately, not wait for the debounce delay.
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(debounceTimer);
                searchForm.requestSubmit ? searchForm.requestSubmit() : searchForm.submit();
            }
        });
    })();
</script>

<style>
    .crumbs{ display:flex; align-items:center; gap:8px; font-size:13px; color: var(--faint,#9AA1B2); margin-bottom:10px; }
    .crumbs b{ color: var(--ink,#171B2C); font-weight:600; }
    .crumbs span:first-child{ cursor:pointer; transition:color .15s; }
    .crumbs span:first-child:hover{ color: var(--orange,#EF7B2E); }

    .page-header{ display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:24px; gap:16px; flex-wrap:wrap; }
    .page-header h1{ font-size:25px; font-weight:700; letter-spacing:-0.02em; margin:0; color: var(--ink,#171B2C); }
    .page-header p{ font-size:13.5px; color: var(--muted,#667085); margin:7px 0 0;  line-height:1.55; }

    .btn-primary{
        display:flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:#fff;
        background:linear-gradient(135deg, #0F1526, #1D2439); border:none; text-decoration:none;
        padding:11px 20px; border-radius:9px; cursor:pointer; white-space:nowrap;
        box-shadow:0 4px 12px -4px rgba(15,21,38,0.4);
        transition:transform .12s ease, box-shadow .12s ease;
    }
    .btn-primary:hover{ transform:translateY(-1px); box-shadow:0 8px 18px -6px rgba(15,21,38,0.5); color:#fff; }

    .card{
        background:#fff; border:1px solid var(--line,#E9EBF2); border-radius:16px;
        box-shadow:0 1px 2px rgba(15,21,38,0.03), 0 8px 24px -16px rgba(15,21,38,0.10);
        overflow:hidden;
    }

    .toolbar{
        display:flex; align-items:center; justify-content:space-between; gap:16px;
        padding:20px 24px; flex-wrap:wrap; border-bottom:1px solid var(--line,#E9EBF2);
    }
    .search-box{
        display:flex; align-items:center; gap:8px; flex:1; min-width:220px; max-width:360px;
        border:1px solid var(--input-border,#DBDFEA); border-radius:10px; padding:9px 12px;
        background:#FAFBFD; transition:border-color .15s, box-shadow .15s;
    }
    .search-box:focus-within{ border-color: var(--orange,#EF7B2E); box-shadow:0 0 0 4px var(--orange-tint-strong,#FFE9D8); background:#fff; }
    .search-box i{ color: var(--faint,#9AA1B2); flex-shrink:0; }
    .search-box input{ border:none; background:none; outline:none; font-size:13.5px; width:100%; color: var(--ink,#171B2C); }
    .clear-search{ color: var(--faint,#9AA1B2); font-size:16px; text-decoration:none; display:flex; align-items:center; flex-shrink:0; }
    .clear-search:hover{ color:#e74c3c; }

    .entries-select{ display:flex; align-items:center; gap:8px; font-size:13px; color: var(--muted,#667085); white-space:nowrap; }
    .entries-select select{
        border:1px solid var(--input-border,#DBDFEA); border-radius:8px; padding:6px 10px; font-size:13px;
        color: var(--ink,#171B2C); background:#fff; outline:none; cursor:pointer;
    }
    .entries-select select:focus{ border-color: var(--orange,#EF7B2E); }

    table{ width:100%; border-collapse:collapse; }
    thead th{
        text-align:left; font-size:11px; font-weight:700; letter-spacing:.04em; color: var(--faint,#9AA1B2);
        text-transform:uppercase; padding:14px 24px; background:#FBFBFD; border-bottom:1px solid var(--line,#E9EBF2);
    }
    thead th:last-child{ text-align:right; }
    tbody td{ padding:14px 24px; border-bottom:1px solid var(--line,#E9EBF2); vertical-align:middle; }
    tbody tr:last-child td{ border-bottom:none; }
    tbody tr:hover{ background:#FAFBFD; }

    .thumb-wrap{
        width:56px; height:56px; border-radius:10px; overflow:hidden; border:1px solid var(--line,#E9EBF2);
        background: var(--canvas,#F6F7FB); display:flex; align-items:center; justify-content:center;
    }
    .thumb-wrap img{ width:100%; height:100%; object-fit:cover; display:block; }

    .title-cell{ font-size:13.5px; font-weight:700; color: var(--ink,#171B2C); }
    .status-pill{
        display:inline-flex; align-items:center; gap:5px; font-size:10.5px; font-weight:700;
        padding:3px 9px; border-radius:99px; text-transform:uppercase; letter-spacing:.02em;
        background: var(--green-tint,#E9F8EF); color: var(--green,#12875A); margin-left:8px; vertical-align:middle;
    }
    .status-pill::before{ content:""; width:5px; height:5px; border-radius:99px; background: var(--green,#12875A); display:inline-block; }

    .desc-cell{ font-size:13px; color: var(--muted,#667085); max-width:420px; }
    .desc-clamp{ display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; line-height:1.45; }

    .actions-cell{ display:flex; justify-content:flex-end; gap:8px; }
    .icon-btn{
        width:34px; height:34px; border-radius:9px; border:none; display:flex; align-items:center; justify-content:center;
        cursor:pointer; text-decoration:none; font-size:14px; transition:background .15s, transform .1s;
    }
    .icon-btn:active{ transform:scale(0.94); }
    .icon-btn.edit{ background: var(--orange-tint-strong,#FFE9D8); color: var(--orange,#EF7B2E); }
    .icon-btn.edit:hover{ background:#FFDDBB; }
    .icon-btn.delete{ background:#FDEDEC; color:#E9483F; }
    .icon-btn.delete:hover{ background:#FADBD8; }

    .table-footer{
        display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;
        padding:16px 24px;
    }
    .table-footer .count{ font-size:12.5px; color: var(--faint,#9AA1B2); }

    /* Best-effort styling for Laravel's default pagination view output */
    .pagination-wrap nav{ display:flex; }
    .pagination-wrap ul{ list-style:none; display:flex; gap:6px; margin:0; padding:0; }
    .pagination-wrap li{ display:flex; }
    .pagination-wrap li > a, .pagination-wrap li > span{
        min-width:32px; height:32px; padding:0 8px; border-radius:8px; border:1px solid var(--input-border,#DBDFEA);
        background:#fff; font-size:12.5px; font-weight:600; color: var(--muted,#667085); cursor:pointer;
        display:flex; align-items:center; justify-content:center; text-decoration:none;
        transition:background .15s, color .15s, border-color .15s;
    }
    .pagination-wrap li > a:hover{ border-color: var(--orange,#EF7B2E); color: var(--orange,#EF7B2E); }
    .pagination-wrap li.active span, .pagination-wrap li > span[aria-current]{
        background: linear-gradient(135deg, #0F1526, #1D2439); border-color: transparent; color:#fff;
    }
    .pagination-wrap li.disabled span{ opacity:.4; cursor:not-allowed; }

    .empty-state{ padding:64px 24px; text-align:center; }
    .empty-state .ico{
        width:56px; height:56px; border-radius:99px; background: var(--canvas,#F6F7FB); margin:0 auto 14px;
        display:flex; align-items:center; justify-content:center;
    }
    .empty-state h3{ font-size:14.5px; font-weight:700; margin:0 0 4px; color: var(--ink,#171B2C); }
    .empty-state p{ font-size:13px; color: var(--muted,#667085); margin:0 0 18px; }

    @media (max-width:760px){
        .desc-cell{ display:none; }
        thead th:nth-child(3){ display:none; }
        .table-wrap{ overflow-x:auto; }
    }
</style>

@endsection