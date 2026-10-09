@extends('admin.layout')

@section('title', 'Career Enquiries')

@section('content')

<div class="wrap">
    <div class="crumbs">
        <span onclick="window.location='{{ route('admin.dashboard') }}'">Home</span>
        <span>&rsaquo;</span>
        <span onclick="window.location='{{ route('admin.career') }}'">Career</span>
        <span>&rsaquo;</span>
        <b>Enquiries</b>
    </div>

    <div class="page-header">
        <div>
            <h1>Career Enquiries</h1>
            <p>Applications sent from the Career page of your website. This list is view only.</p>
        </div>
    </div>

    <div class="card">
        <div class="toolbar">
            <form method="GET" action="{{ route('admin.career.enquiries') }}" class="search-box" id="searchForm">
                <i class="bi bi-search"></i>
                <input type="text" name="search" id="searchInput" value="{{ $search }}" placeholder="Search by name, email, phone or position..." autocomplete="off">
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                @if ($search)
                    <a href="{{ route('admin.career.enquiries') }}" class="clear-search" title="Clear search">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </form>

            <form method="GET" action="{{ route('admin.career.enquiries') }}" class="entries-select" id="perPageForm">
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
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Apply For</th>
                        <th>Received</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($enquiries as $enquiry)
                    <tr>
                        <td class="num-cell">{{ $enquiries->firstItem() + $loop->index }}</td>
                        <td class="title-cell">{{ $enquiry->name }}</td>
                        <td><a class="contact" href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></td>
                        <td class="nowrap"><a class="contact" href="tel:{{ preg_replace('/[^0-9+]/', '', $enquiry->phone) }}">{{ $enquiry->phone }}</a></td>
                        <td><span class="tag">{{ $enquiry->apply_for ?: '—' }}</span></td>
                        <td class="nowrap">{{ $enquiry->created_at?->format('d M Y, h:i A') ?? '—' }}</td>
                    </tr>
                    @empty
                        {{-- Empty state rendered outside <tbody> below --}}
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($enquiries->isEmpty())
        <div class="empty-state">
            <div class="ico"><i class="bi bi-search" style="color:var(--faint,#9AA1B2); font-size:22px;"></i></div>
            @if ($search)
                <h3>No enquiries found</h3>
                <p>Try a different search term.</p>
            @else
                <h3>No enquiries yet</h3>
                <p>Applications sent from the website's Career page will appear here.</p>
            @endif
        </div>
        @else
        <div class="table-footer">
            <span class="count">Showing {{ $enquiries->firstItem() ?? 0 }} to {{ $enquiries->lastItem() ?? 0 }} of {{ $enquiries->total() }} entries</span>
            <div class="pagination-wrap">
                {{ $enquiries->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    // ===== Auto-search: submit the search form automatically as the user types =====
    (function () {
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        if (!searchInput || !searchForm) return;

        // after a search the page reloads: put the cursor back at the end so typing can continue
        if (searchInput.value) {
            searchInput.focus();
            searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
        }

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
    .crumbs span[onclick]{ cursor:pointer; transition:color .15s; }
    .crumbs span[onclick]:hover{ color: var(--orange,#EF7B2E); }

    .page-header{ display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:24px; gap:16px; flex-wrap:wrap; }
    .page-header h1{ font-size:25px; font-weight:700; letter-spacing:-0.02em; margin:0; color: var(--ink,#171B2C); }
    .page-header p{ font-size:13.5px; color: var(--muted,#667085); margin:7px 0 0;  line-height:1.55; }

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
        display:flex; align-items:center; gap:8px; flex:1; min-width:220px; max-width:420px;
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
        text-transform:uppercase; padding:14px 24px; background:#FBFBFD; border-bottom:1px solid var(--line,#E9EBF2); white-space:nowrap;
    }
    tbody td{ padding:14px 24px; border-bottom:1px solid var(--line,#E9EBF2); vertical-align:middle; font-size:13px; color: var(--muted,#667085); }
    tbody tr:last-child td{ border-bottom:none; }
    tbody tr:hover{ background:#FAFBFD; }

    .num-cell{ width:56px; color: var(--faint,#9AA1B2); }
    .title-cell{ font-size:13.5px; font-weight:700; color: var(--ink,#171B2C); }
    .nowrap{ white-space:nowrap; }

    a.contact{ color: var(--ink,#171B2C); text-decoration:none; }
    a.contact:hover{ color: var(--orange,#EF7B2E); text-decoration:underline; }
    .tag{
        display:inline-block; padding:4px 10px; border-radius:99px; font-size:12px; font-weight:600;
        background: var(--orange-tint-strong,#FFE9D8); color: var(--orange-deep,#DA6A20);
    }

    .table-footer{
        display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;
        padding:16px 24px;
    }
    .table-footer .count{ font-size:12.5px; color: var(--faint,#9AA1B2); }

    /* Styling for Laravel's pagination view output */
    .pagination-wrap nav{ display:flex; }
    .pagination-wrap nav > div.d-sm-none{ display:none; }      /* the small-screen copy of Previous / Next */
    .pagination-wrap nav p{ display:none; }                    /* "Showing x to y" is already printed on the left */
    .pagination-wrap ul{ list-style:none; display:flex; flex-wrap:wrap; gap:6px; margin:0; padding:0; }
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
        .table-wrap{ overflow-x:auto; }
        .table-wrap table{ min-width:720px; }
    }
</style>

@endsection
