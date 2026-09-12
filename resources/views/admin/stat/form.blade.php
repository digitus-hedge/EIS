@extends('admin.layout')
@section('title', 'Stats Section')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

<div class="wrap">
    <div class="crumbs">
        <span onclick="window.location='{{ route('admin.dashboard') }}'">Home</span>
        <span>&rsaquo;</span>
        <b>Stats Section</b>
    </div>

    <div class="header">
        <div>
            <h1>Stats Section</h1>
            <p>The numbers strip shown on your homepage — up to 5 stats, each with a value, label, and short description.</p>
        </div>
    </div>

    <form action="{{ route('admin.home.stats.store') }}" method="POST" id="statForm">
        @csrf

        <div class="card">
            <div class="section-title">
                <h2><span class="icon"><i class="bi bi-bar-chart"></i></span> Stats</h2>
                <span class="section-sub" style="margin:0;">Up to 5 items</span>
            </div>

            <div class="stats-table-header">
                <span>Value</span>
                <span>Label</span>
                <span>Description</span>
                <span></span>
            </div>

            <div id="statsRows"></div>

            <button type="button" id="addStatBtn" class="choose-btn inline">
                <i class="bi bi-plus-circle"></i> Add Stat
            </button>
            <p class="section-sub" id="maxHint" style="display:none; margin:10px 0 0;">Maximum of 5 stats reached.</p>
        </div>

        <div class="savebar">
            <div class="savebar-inner">
                <span class="savebar-status">All changes save to the live Stats section</span>
                <div class="btn-group">
                    <a href="{{ route('admin.dashboard') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-save">
                        <i class="bi bi-check-lg"></i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<template id="statRowTemplate">
    <div class="stats-row-wrapper">
        <div class="stats-row">
            <div class="input-group-cell">
                <input type="text" name="items[__INDEX__][value]" maxlength="8" placeholder="e.g. 100K">
                <span class="field-error error-value" style="display: none;"><i class="bi bi-exclamation-circle"></i> <span class="error-msg"></span></span>
            </div>
            <div class="input-group-cell">
                <input type="text" name="items[__INDEX__][label]" maxlength="20" placeholder="e.g. LICENSES">
                <span class="field-error error-label" style="display: none;"><i class="bi bi-exclamation-circle"></i> <span class="error-msg"></span></span>
            </div>
            <div class="input-group-cell">
                <input type="text" name="items[__INDEX__][description]" maxlength="20" placeholder="e.g. Held & managed">
                <span class="field-error error-description" style="display: none;"><i class="bi bi-exclamation-circle"></i> <span class="error-msg"></span></span>
            </div>
            <button type="button" class="action-btn delete btn-remove-row" title="Remove stat"><i class="bi bi-trash3"></i></button>
        </div>
    </div>
</template>

<script>
    const existingItems = @json(old('items', $stat->items ?? []));
    const validationErrors = @json($errors->toArray());
    const maxRows = 5;
    const rowsContainer = document.getElementById('statsRows');
    const template = document.getElementById('statRowTemplate');
    const addBtn = document.getElementById('addStatBtn');
    const maxHint = document.getElementById('maxHint');

    function rowCount() {
        return rowsContainer.querySelectorAll('.stats-row-wrapper').length;
    }

    function updateAddButtonState() {
        const reachedMax = rowCount() >= maxRows;
        addBtn.style.display = reachedMax ? 'none' : 'inline-flex';
        maxHint.style.display = reachedMax ? 'block' : 'none';
    }

    function addRow(data = { value: '', label: '', description: '' }, indexOverride = null) {
        if (rowCount() >= maxRows) return;

        const index = indexOverride !== null ? indexOverride : rowCount();
        const clone = template.content.cloneNode(true);
        const wrapperEl = clone.querySelector('.stats-row-wrapper');

        wrapperEl.querySelectorAll('input').forEach(input => {
            input.name = input.name.replace('__INDEX__', index);
        });

        wrapperEl.querySelector('input[name$="[value]"]').value = data.value ?? '';
        wrapperEl.querySelector('input[name$="[label]"]').value = data.label ?? '';
        wrapperEl.querySelector('input[name$="[description]"]').value = data.description ?? '';

        ['value', 'label', 'description'].forEach(field => {
            const errorKey = `items.${index}.${field}`;
            if (validationErrors[errorKey]) {
                const errorEl = wrapperEl.querySelector(`.error-${field}`);
                const inputEl = wrapperEl.querySelector(`input[name="items[${index}][${field}]"]`);

                if (errorEl && inputEl) {
                    errorEl.querySelector('.error-msg').textContent = validationErrors[errorKey][0];
                    errorEl.style.display = 'inline-flex';
                    inputEl.classList.add('input-error');
                }
            }
        });

        wrapperEl.querySelector('.btn-remove-row').addEventListener('click', function () {
            wrapperEl.remove();
            reindexRows();
            updateAddButtonState();
        });

        rowsContainer.appendChild(wrapperEl);
        updateAddButtonState();
    }

    function reindexRows() {
        rowsContainer.querySelectorAll('.stats-row-wrapper').forEach((wrapper, i) => {
            wrapper.querySelectorAll('input').forEach(input => {
                input.name = input.name.replace(/items\[\d+\]/, `items[${i}]`);
            });
        });
    }

    addBtn.addEventListener('click', () => addRow());

    if (existingItems.length > 0) {
        existingItems.forEach((item, idx) => addRow(item, idx));
    } else {
        addRow();
    }
</script>

<style>
    .crumbs{ display:flex; align-items:center; gap:8px; font-size:13px; color: var(--faint,#9AA1B2); margin-bottom:10px; }
    .crumbs b{ color: var(--ink,#171B2C); font-weight:600; }
    .crumbs span:first-child{ cursor:pointer; transition:color .15s; }
    .crumbs span:first-child:hover{ color: var(--orange,#EF7B2E); }

    .header{ display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:32px; gap:16px; flex-wrap:wrap; }
    .header h1{ font-size:25px; font-weight:700; letter-spacing:-0.02em; margin:0; color: var(--ink,#171B2C); }
    .header p{ font-size:13.5px; color: var(--muted,#667085); margin:7px 0 0; line-height:1.55; }

    .section-title{ display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:6px; }
    .section-title h2{ display:flex; align-items:center; gap:8px; font-size:14px; font-weight:700; margin:0; color: var(--ink,#171B2C); }
    .icon{ display:inline-flex; color: var(--orange,#EF7B2E); }
    .section-sub{ font-size:12px; color: var(--faint,#9AA1B2); }

    .stats-table-header{
        display:grid; grid-template-columns:1fr 1fr 2fr 40px; gap:12px;
        font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;
        color: var(--faint,#9AA1B2); padding:0 4px 10px; border-bottom:1px solid var(--line,#E9EBF2); margin-bottom:14px;
    }

    .stats-row{ display:grid; grid-template-columns:1fr 1fr 2fr 40px; gap:12px; align-items:flex-start; margin-bottom:12px; }
    .input-group-cell{ display:flex; flex-direction:column; }

    .stats-row input{
        padding:10px 12px; border:1px solid var(--input-border,#DBDFEA); border-radius:8px;
        font-size:14px; outline:none; width:100%; font-family:inherit; color: var(--ink,#171B2C);
        transition:box-shadow .15s, border-color .15s;
    }
    .stats-row input:focus{ border-color: var(--orange,#EF7B2E); box-shadow:0 0 0 4px var(--orange-tint-strong,#FFE9D8); }
    .stats-row input.input-error{ border-color:#e74c3c; background:#fff8f8; }

    .field-error{ display:flex; align-items:center; gap:5px; color:#e74c3c; font-size:12px; margin-top:6px; }

    .action-btn{
        width:36px; height:36px; border-radius:8px; border:none;
        display:inline-flex; align-items:center; justify-content:center;
        cursor:pointer; font-size:14px; transition:filter .15s;
    }
    .action-btn.delete{ background:#fdecea; color:#e74c3c; }
    .action-btn.delete:hover{ filter:brightness(0.95); }

    .choose-btn{
        font-size:12px; font-weight:600; color: var(--orange,#EF7B2E);
        background:#fff; border:1px solid var(--orange-border,#F3D8C2); border-radius:8px;
        padding:8px 16px; cursor:pointer; transition:background .15s;
    }
    .choose-btn.inline{ display:inline-flex; align-items:center; gap:6px; }
    .choose-btn:hover{ background: var(--orange-tint,#FFF8F3); }

    .notice{ display:flex; align-items:flex-start; gap:8px; background: var(--canvas,#F6F7FB); border-radius:10px; padding:10px 12px; }
    .notice.caution{ background:#FFF8E8; border:1px solid #F5E3B3; }
    .notice.caution i{ color:#B7791F; }
    .notice.caution p{ color:#8A6116; margin:0; font-size:12px; }

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

    @media (max-width:700px){
        .stats-table-header, .stats-row{ grid-template-columns:1fr; }
        .stats-table-header span:not(:first-child){ display:none; }
        .input-group-cell::before{ content: attr(data-label); font-size:11px; font-weight:600; color: var(--faint,#9AA1B2); margin-bottom:4px; }
    }
</style>

@endsection