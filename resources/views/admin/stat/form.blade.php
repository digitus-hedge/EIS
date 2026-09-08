@extends('admin.layout')
@section('title', 'Stats Section')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
    <div class="alert alert-error">
        <i class="bi bi-exclamation-circle"></i>
        Please fill below fields before submitting
    </div>
@endif

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: @json(session('success')),
                confirmButtonColor: '#3b3b58',
                timer: 2500,
                timerProgressBar: true
            });
        });
    </script>
@endif

<div class="form-header">
    <h4>
        <i class="bi bi-bar-chart"></i>
        Stats Section
    </h4>
</div>

<form action="{{ route('admin.home.stats.store') }}" method="POST" class="banner-form" id="statForm">
    @csrf

    <div class="form-card">
        <div class="stats-table-header">
            <span>Value</span>
            <span>Label</span>
            <span>Description</span>
            <span></span>
        </div>

        <div id="statsRows"></div>

        <button type="button" id="addStatBtn" class="btn-add-row">
            <i class="bi bi-plus-circle"></i> Add Stat
        </button>
        <p class="hint-text" id="maxHint" style="display:none;">Maximum of 5 stats reached.</p>
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.dashboard') }}" class="btn-cancel">Cancel</a>
        <button type="submit" class="btn-submit">
            <i class="bi bi-check-lg"></i>
            Save
        </button>
    </div>
</form>

<template id="statRowTemplate">
    <div class="stats-row">
        <input type="text" name="items[__INDEX__][value]" placeholder="e.g. 100K">
        <input type="text" name="items[__INDEX__][label]" placeholder="e.g. LICENSES">
        <input type="text" name="items[__INDEX__][description]" placeholder="e.g. Held & managed">
        <button type="button" class="btn-remove-row"><i class="bi bi-trash3"></i></button>
    </div>
</template>

<script>
    const existingItems = @json(old('items', $stat->items ?? []));
    const maxRows = 5;
    const rowsContainer = document.getElementById('statsRows');
    const template = document.getElementById('statRowTemplate');
    const addBtn = document.getElementById('addStatBtn');
    const maxHint = document.getElementById('maxHint');

    function rowCount() {
        return rowsContainer.querySelectorAll('.stats-row').length;
    }

    function updateAddButtonState() {
        const reachedMax = rowCount() >= maxRows;
        addBtn.style.display = reachedMax ? 'none' : 'inline-flex';
        maxHint.style.display = reachedMax ? 'block' : 'none';
    }

    function addRow(data = { value: '', label: '', description: '' }) {
        if (rowCount() >= maxRows) return;

        const index = rowCount();
        const clone = template.content.cloneNode(true);
        const rowEl = clone.querySelector('.stats-row');

        rowEl.querySelectorAll('input').forEach(input => {
            input.name = input.name.replace('__INDEX__', index);
        });

        rowEl.querySelector('input[name$="[value]"]').value = data.value ?? '';
        rowEl.querySelector('input[name$="[label]"]').value = data.label ?? '';
        rowEl.querySelector('input[name$="[description]"]').value = data.description ?? '';

        rowEl.querySelector('.btn-remove-row').addEventListener('click', function () {
            rowEl.remove();
            reindexRows();
            updateAddButtonState();
        });

        rowsContainer.appendChild(rowEl);
        updateAddButtonState();
    }

    function reindexRows() {
        rowsContainer.querySelectorAll('.stats-row').forEach((row, i) => {
            row.querySelectorAll('input').forEach(input => {
                input.name = input.name.replace(/items\[\d+\]/, `items[${i}]`);
            });
        });
    }

    addBtn.addEventListener('click', () => addRow());

    // Preload existing data, or start with 1 empty row
    if (existingItems.length > 0) {
        existingItems.forEach(item => addRow(item));
    } else {
        addRow();
    }
</script>

<style>
    .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; display:flex; align-items:center; gap:8px; }
    .alert-error { background: #fdecea; color: #c0392b; }
    .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
    .form-header h4 { display: flex; align-items: center; gap: 8px; color: #1e1e2d; }
    .banner-form { width: 100%; }
    .form-card { background: #fff; padding: 22px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 18px; }

    .stats-table-header {
        display: grid;
        grid-template-columns: 1fr 1fr 2fr 40px;
        gap: 12px;
        font-size: 12.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #888;
        padding: 0 4px 10px;
        border-bottom: 1px solid #eee;
        margin-bottom: 14px;
    }

    .stats-row {
        display: grid;
        grid-template-columns: 1fr 1fr 2fr 40px;
        gap: 12px;
        align-items: center;
        margin-bottom: 12px;
    }

    .stats-row input {
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        outline: none;
        width: 100%;
    }
    .stats-row input:focus { border-color: #3b3b58; }

    .btn-remove-row {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 6px;
        border: 1px solid #f0d0d0;
        background: #fdecea;
        color: #c0392b;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .btn-remove-row:hover { background: #c0392b; color: #fff; }

    .btn-add-row {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f4f6f9;
        color: #3b3b58;
        border: 1px solid #ddd;
        padding: 9px 16px;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        margin-top: 6px;
    }
    .btn-add-row:hover { background: #e9ecf2; }

    .hint-text { font-size: 12.5px; color: #888; margin-top: 10px; }

    .field-error { display: flex; align-items: center; gap: 5px; color: #e74c3c; font-size: 12.5px; margin-top: 6px; }

    .form-actions { display: flex; gap: 12px; margin-top: 6px; }
    .btn-cancel { padding: 11px 22px; border-radius: 6px; border: 1px solid #ddd; color: #555; text-decoration: none; font-size: 14px; }
    .btn-cancel:hover { background: #f4f6f9; }
    .btn-submit { display: flex; align-items: center; gap: 7px; background: #3b3b58; color: #fff; border: none; padding: 11px 24px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; }
    .btn-submit:hover { background: #2b2b42; }
</style>

@endsection
