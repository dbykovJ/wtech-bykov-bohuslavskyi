@extends('layouts.admin')

@section('title', 'SuperDash — Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<h1 class="admin-page-title">Dashboard</h1>

<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-card__top">
            <div>
                <p class="stat-card__label">Total User</p>
                <p class="stat-card__value">40,689</p>
            </div>
            <div class="stat-card__icon stat-card__icon--purple">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
        </div>
        <p class="stat-card__trend stat-card__trend--up">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            8.5% Up from yesterday
        </p>
    </div>

    <div class="stat-card">
        <div class="stat-card__top">
            <div>
                <p class="stat-card__label">Total Order</p>
                <p class="stat-card__value">10293</p>
            </div>
            <div class="stat-card__icon stat-card__icon--orange">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
        </div>
        <p class="stat-card__trend stat-card__trend--up">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            1.3% Up from past week
        </p>
    </div>

    <div class="stat-card">
        <div class="stat-card__top">
            <div>
                <p class="stat-card__label">Total Sales</p>
                <p class="stat-card__value">$89,000</p>
            </div>
            <div class="stat-card__icon stat-card__icon--green">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
        </div>
        <p class="stat-card__trend stat-card__trend--down">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
            4.3% Down from yesterday
        </p>
    </div>

    <div class="stat-card">
        <div class="stat-card__top">
            <div>
                <p class="stat-card__label">Total Pending</p>
                <p class="stat-card__value">2040</p>
            </div>
            <div class="stat-card__icon stat-card__icon--peach">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
        </div>
        <p class="stat-card__trend stat-card__trend--up">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            1.8% Up from yesterday
        </p>
    </div>
</div>

<div class="card">
    <div class="card__header">
        <h2 class="card__title">Sales Details</h2>
        <select class="admin-select">
            <option>October</option>
            <option>November</option>
            <option>December</option>
        </select>
    </div>
    <div class="chart-wrapper">
        <canvas id="salesChart"></canvas>
    </div>
</div>

<div class="card">
    <div class="card__header">
        <h2 class="card__title">Deals Details</h2>
        <select class="admin-select">
            <option>October</option>
            <option>November</option>
            <option>December</option>
        </select>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Location</th>
                <th>Date - Time</th>
                <th>Piece</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><div class="table-product"><div class="table-product__img"></div> Item</div></td>
                <td>Location</td>
                <td>12.09.2026 - 12.53 PM</td>
                <td>423</td>
                <td>$34,295</td>
                <td><span class="badge badge--delivered">Delivered</span></td>
            </tr>
            <tr>
                <td><div class="table-product"><div class="table-product__img"></div> Item</div></td>
                <td>Location</td>
                <td>15.09.2026 - 09.10 AM</td>
                <td>210</td>
                <td>$18,500</td>
                <td><span class="badge badge--pending">Pending</span></td>
            </tr>
            <tr>
                <td><div class="table-product"><div class="table-product__img"></div> Item</div></td>
                <td>Location</td>
                <td>18.09.2026 - 03.45 PM</td>
                <td>87</td>
                <td>$7,120</td>
                <td><span class="badge badge--cancelled">Cancelled</span></td>
            </tr>
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
            labels: ['5', '10', '15', '20', '25', '30'],
            datasets: [{
                label: 'Sales',
                data: [5000, 12000, 8000, 18000, 14000, 22000],
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
