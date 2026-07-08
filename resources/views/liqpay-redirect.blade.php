@extends('layouts.app')

@section('title', 'Перенаправлення на оплату — Look of Today')

@section('content')
    <main class="payment-main">
        <div class="container" style="text-align: center; padding: 80px 0;">
            <h1 class="payment-title heading">ПЕРЕНАПРАВЛЕННЯ НА ОПЛАТУ</h1>
            <p style="margin: 16px 0; color: #666;">Зачекайте, будь ласка. Вас буде перенаправлено на захищену сторінку LiqPay для завершення оплати.</p>

            <form id="liqpay-form" method="POST" action="{{ $url }}" accept-charset="utf-8">
                <input type="hidden" name="data" value="{{ $data }}">
                <input type="hidden" name="signature" value="{{ $signature }}">
                <noscript>
                    <button type="submit" class="place-order-btn">Перейти до оплати</button>
                </noscript>
            </form>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.getElementById('liqpay-form').submit();
    </script>
@endpush
