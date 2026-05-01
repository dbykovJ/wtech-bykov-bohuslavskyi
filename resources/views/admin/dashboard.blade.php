@extends('layouts.admin')

@section('title', 'SuperDash — Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<h1 class="admin-page-title">Dashboard</h1>


<a href="{{ route('account') }}" class="reset-filter-btn" style="margin-bottom:16px; display:inline-flex; align-items:center; gap:8px;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="15 18 9 12 15 6"/>
    </svg>
    Back to Account
</a>

<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-card__top">
            <div>
                <p class="stat-card__label">Total Users</p>
                <p class="stat-card__value">{{ number_format($totalUsers) }}</p>
            </div>
            <div class="stat-card__icon stat-card__icon--purple">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__top">
            <div>
                <p class="stat-card__label">Total Orders</p>
                <p class="stat-card__value">{{ number_format($totalOrders) }}</p>
            </div>
            <div class="stat-card__icon stat-card__icon--orange">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__top">
            <div>
                <p class="stat-card__label">Total Sales</p>
                <p class="stat-card__value">${{ number_format($totalSales, 2) }}</p>
            </div>
            <div class="stat-card__icon stat-card__icon--green">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__top">
            <div>
                <p class="stat-card__label">Pending Orders</p>
                <p class="stat-card__value">{{ number_format($totalPending) }}</p>
            </div>
            <div class="stat-card__icon stat-card__icon--peach">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card__header">
        <h2 class="card__title">Sales This Month — {{ now()->format('F Y') }}</h2>
    </div>
    <div class="chart-wrapper">
        <canvas id="salesChart"></canvas>
    </div>
</div>

<div class="card">
    <div class="card__header">
        <h2 class="card__title">Recent Orders</h2>
        <a href="{{ route('admin.orders') }}" class="admin-select" style="text-decoration:none;">View all</a>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Location</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentOrders as $order)
            @php
                $statusMap = [
                    'pending_payment' => ['label' => 'Pending',    'class' => 'badge--pending'],
                    'paid'            => ['label' => 'Processing', 'class' => 'badge--processing'],
                    'processing'      => ['label' => 'Processing', 'class' => 'badge--processing'],
                    'shipped'         => ['label' => 'In Transit', 'class' => 'badge--in-transit'],
                    'delivered'       => ['label' => 'Delivered',  'class' => 'badge--delivered'],
                    'completed'       => ['label' => 'Completed',  'class' => 'badge--completed'],
                    'cancelled'       => ['label' => 'Cancelled',  'class' => 'badge--cancelled'],
                ];
                $s = $statusMap[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'badge--pending'];
                $address = implode(', ', array_filter([$order->shipping_city, $order->shipping_country])) ?: '—';
            @endphp
            <tr>
                <td>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $order->shipping_full_name ?? $order->user?->name ?? '—' }}</td>
                <td>{{ $address }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td>${{ number_format($order->total, 2) }}</td>
                <td><span class="badge {{ $s['class'] }}">{{ $s['label'] }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; color:#9ca3af;">No orders yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Sales ($)',
                data: @json($chartData),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#6366f1',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
