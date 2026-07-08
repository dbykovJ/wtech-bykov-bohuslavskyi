<footer class="footer">
    <div class="footer__newsletter">
        <div class="container footer__newsletter-inner">
            <h2 class="footer__newsletter-title">БУДЬТЕ В КУРСІ НАШИХ ОСТАННІХ ПРОПОЗИЦІЙ</h2>
            <form method="POST" action="{{ route('newsletter.subscribe') }}" class="footer__newsletter-form">
                @csrf
                <div class="footer__email-input">
                    <svg width="14" height="14" fill="none" stroke="#aaa" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                    <input type="email" name="newsletter_email" placeholder="Введіть вашу електронну адресу"
                           value="{{ old('newsletter_email', Auth::user()?->email) }}" required />
                </div>
                @error('newsletter_email', 'newsletter')
                    <div class="error-bubble">{{ $message }}</div>
                @enderror
                <button type="submit" class="footer__subscribe-btn">Підписатися на розсилку</button>
            </form>
            @if (session('newsletter_success'))
                <div class="success-bubble" data-success-bubble>
                    {{ session('newsletter_success') }}
                </div>
            @endif
        </div>
    </div>

    <div class="container footer__body">
        <div class="footer__cols">
            <div class="footer__brand">
                <div class="footer__brand-logo heading">
                    <img draggable="false" src="{{ asset('assets/logo.svg') }}" alt="Look of Today" class="footer__brand-logo-image" />
                </div>
                <p class="footer__brand-desc">У нас є одяг, який пасує вашому стилю і який ви з гордістю носитимете. Для жінок і чоловіків.</p>
                <div class="footer__socials">
                    <a href="#"><img draggable="false" src="{{ asset('assets/icons/socials/facebook.svg') }}" alt="Facebook" /></a>
                    <a href="#"><img draggable="false" src="{{ asset('assets/icons/socials/instagram.svg') }}" alt="Instagram" /></a>
                    <a href="#"><img draggable="false" src="{{ asset('assets/icons/socials/twitter.svg') }}" alt="Twitter" /></a>
                    <a href="#"><img draggable="false" src="{{ asset('assets/icons/socials/linkedin.svg') }}" alt="LinkedIn" /></a>
                </div>
            </div>
        </div>

        <div class="footer__bottom">
            <span class="footer__copyright">Look of Today &copy; 2000-2025. Усі права захищено</span>
            <div class="footer__payments">
                <span class="payment-badge">VISA</span>
                <span class="payment-badge">Mastercard</span>
                <span class="payment-badge">PayPal</span>
                <span class="payment-badge">Apple Pay</span>
                <span class="payment-badge">G Pay</span>
            </div>
        </div>
    </div>
</footer>
