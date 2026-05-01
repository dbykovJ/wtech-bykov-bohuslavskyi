@extends('layouts.admin')

@section('title', 'SuperDash — Orders')

@section('content')
<h1 class="admin-page-title">Order Lists</h1>

@php
    $statusBadge = [
        'pending_payment' => 'badge--pending',
        'paid'            => 'badge--paid',
        'processing'      => 'badge--processing',
        'shipped'         => 'badge--in-transit',
        'delivered'       => 'badge--delivered',
        'completed'       => 'badge--completed',
        'cancelled'       => 'badge--cancelled',
    ];
    $dateLabels = [
        'newest'     => 'Newest first',
        'oldest'     => 'Oldest first',
        'this_week'  => 'This week',
        'this_month' => 'This month',
    ];
@endphp

<form method="GET" action="{{ route('admin.orders') }}" id="filter-form">

    {{-- Search --}}
    <div style="margin-bottom: 16px; display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
        <div style="position:relative; max-width:340px; flex:1 1 240px;">
            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;"
                 width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name or order ID…"
                style="width:100%;padding:8px 12px 8px 36px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;outline:none;"
                onchange="document.getElementById('filter-form').submit()">
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <input
                type="number"
                name="min_total"
                value="{{ request('min_total') }}"
                placeholder="Min total"
                min="0"
                step="0.01"
                style="width:120px;padding:8px 10px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;outline:none;"
                onchange="document.getElementById('filter-form').submit()">
            <input
                type="number"
                name="max_total"
                value="{{ request('max_total') }}"
                placeholder="Max total"
                min="0"
                step="0.01"
                style="width:120px;padding:8px 10px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;outline:none;"
                onchange="document.getElementById('filter-form').submit()">
        </div>
    </div>

    <div class="order-filters">
        <div class="filter-left">
            <span class="filter-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                </svg>
            </span>
            <span class="filter-by-label">Filter By</span>

            {{-- Date --}}
            <div class="filter-dropdown" id="filter-date">
                <button type="button" class="filter-btn{{ request('date') ? ' filter-btn--active' : '' }}"
                        onclick="toggleDropdown('date-menu')">
                    <span>{{ $dateLabels[request('date')] ?? 'Date' }}</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div class="dropdown-menu" id="date-menu">
                    @foreach ($dateLabels as $val => $label)
                        <button type="button" onclick="applyFilter('date', '{{ $val }}')">{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            {{-- Category / Type --}}
            @if ($categories->isNotEmpty())
            <div class="filter-dropdown" id="filter-type">
                <button type="button" class="filter-btn{{ request('type') ? ' filter-btn--active' : '' }}"
                        onclick="toggleDropdown('type-menu')">
                    <span>{{ request('type') ?? 'Order Type' }}</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div class="dropdown-menu" id="type-menu">
                    @foreach ($categories as $cat)
                        <button type="button" onclick="applyFilter('type', '{{ $cat }}')">{{ $cat }}</button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Status --}}
            <div class="filter-dropdown" id="filter-status">
                <button type="button" class="filter-btn{{ request('status') ? ' filter-btn--active' : '' }}"
                        onclick="toggleDropdown('status-menu')">
                    <span>{{ $statuses[request('status')] ?? 'Order Status' }}</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div class="dropdown-menu" id="status-menu">
                    @foreach ($statuses as $val => $label)
                        <button type="button" onclick="applyFilter('status', '{{ $val }}')">{{ $label }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        <a href="{{ route('admin.orders') }}" class="reset-filter-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="1 4 1 10 7 10"/>
                <path d="M3.51 15a9 9 0 1 0 .49-3.51"/>
            </svg>
            Reset Filter
        </a>
    </div>

    {{-- Hidden inputs to carry active filters when form re-submits --}}
    @foreach (['date','type','status','min_total','max_total'] as $f)
        @if (request($f))
            <input type="hidden" name="{{ $f }}" value="{{ request($f) }}">
        @endif
    @endforeach

</form>

<div class="orders-table-card">
    <table class="admin-table orders-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>ADDRESS</th>
                <th>DATE</th>
                <th>TYPE</th>
                <th>PRICE</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
            @php
                $firstItem = $order->items->first();
                $category  = $firstItem?->product?->category?->name ?? '—';
                $address   = implode(', ', array_filter([$order->shipping_city, $order->shipping_country])) ?: '—';
                $name      = $order->shipping_full_name ?? $order->user?->name ?? '—';
                $badge     = $statusBadge[$order->status] ?? 'badge--pending';
                $label     = $statuses[$order->status] ?? ucfirst($order->status);
            @endphp
            <tr>
                <td>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $name }}</td>
                <td>{{ $address }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td>{{ $category }}</td>
                <td>${{ number_format($order->total, 2) }}</td>
                <td><span class="badge {{ $badge }}">{{ $label }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;color:#9ca3af;padding:32px 0;">No orders found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if ($orders->total())
    <div class="table-footer">
        <span class="table-showing">
            Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}
        </span>
        <div class="pagination">
            @if ($orders->onFirstPage())
                <button class="pagination-btn" disabled>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
            @else
                <a href="{{ $orders->previousPageUrl() }}" class="pagination-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                </a>
            @endif
            @if ($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}" class="pagination-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            @else
                <button class="pagination-btn" disabled>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function toggleDropdown(id) {
        document.querySelectorAll('.dropdown-menu').forEach(m => {
            if (m.id !== id) m.classList.remove('dropdown-menu--open');
        });
        document.getElementById(id).classList.toggle('dropdown-menu--open');
    }

    // Update a hidden input and submit the form
    function applyFilter(name, value) {
        const form = document.getElementById('filter-form');
        let input = form.querySelector(`input[name="${name}"]`);
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            form.appendChild(input);
        }
        input.value = value;
        // Reset page when filter changes
        const pageInput = form.querySelector('input[name="page"]');
        if (pageInput) pageInput.remove();
        form.submit();
    }

    document.addEventListener('click', e => {
        if (!e.target.closest('.filter-dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('dropdown-menu--open'));
        }
    });
</script>
@endpush
