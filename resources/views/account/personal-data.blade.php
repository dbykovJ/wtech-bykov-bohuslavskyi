@extends('layouts.app')

@section('title', 'Your Data — SUPERSELL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/account.css') }}" />
<link rel="stylesheet" href="{{ asset('css/user-personal-data.css') }}" />
@endpush

@section('content')
<main class="personal-main">
    <div class="container">
        <h1 class="personal-title heading">YOUR DATA</h1>

        @include('partials.account-nav')

        <div class="personal-layout">
            <div class="personal-left">
                <div class="personal-form-box">
                    <form class="personal-form" onsubmit="return false;">
                        <input type="text" class="personal-input" placeholder="Full Name" />
                        <input type="email" class="personal-input" placeholder="E-mail" />
                        <input type="text" class="personal-input" placeholder="Address" />
                        <input type="text" class="personal-input" placeholder="Post Code" />
                        <input type="text" class="personal-input" placeholder="City" />
                        <input type="text" class="personal-input" placeholder="Country" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    document.querySelector('.account-menu-toggle').addEventListener('click', function () {
        document.querySelector('.account-nav').classList.toggle('account-nav--open');
    });
</script>
@endpush
