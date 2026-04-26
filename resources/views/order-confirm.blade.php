@extends('layouts.app')

@section('title', 'Order Confirmed — SUPERSELL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/order-confirm.css') }}" />
@endpush

@section('content')
<main class="confirm-main">
    <div class="container">
        <div class="confirm-box">
            <h1 class="confirm-title heading">THANK YOU FOR SHOPPING AT SUPERSELL</h1>

            @if (session('success'))
                <p style="margin: 12px 0; color: #0a7a3f;">{{ session('success') }}</p>
            @endif

            <div class="confirm-actions">
                <button class="confirm-btn confirm-btn--full" onclick="window.print()">
                    Print out shopping recipe
                </button>
                <div class="confirm-actions__row">
                    <button class="confirm-btn" onclick="window.location.href='{{ route('category') }}'">
                        Continue shopping
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <button class="confirm-btn" onclick="window.location.href='{{ route('account') }}'">
                        To my account
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
