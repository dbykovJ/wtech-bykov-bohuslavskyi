<footer class="pb-6 bg-[#f0f0f0] border-t border-[#e5e5e5]">
    <section class="bg-[#111] py-[52px]">
        <div
            class="max-w-[1160px] mx-auto px-4 md:px-8 flex flex-col md:flex-row items-center justify-between gap-8 md:gap-10">
            <h2
                class="heading text-white text-[28px] md:text-[36px] leading-[1.1] m-0 max-w-[400px] text-center md:text-left">
                STAY UPTO DATE ABOUT OUR LATEST OFFERS
            </h2>
            <div class="flex flex-col gap-3 w-full md:w-[320px] shrink-0">
                <div class="bg-[#333] rounded-full py-[11px] px-[18px] flex items-center gap-[10px]">
                    <svg width="14" height="14" fill="none" stroke="#aaa" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                    <input type="email" placeholder="Enter your email address"
                        class="bg-transparent border-none text-[#ccc] text-[13px] w-full font-body outline-none placeholder:text-[#888]" />
                </div>
                <button
                    class="bg-white text-[#111] font-bold text-sm border-none rounded-full py-[13px] px-6 cursor-pointer font-body">
                    Subscribe to Newsletter
                </button>
            </div>
        </div>
    </section>
    <div class="max-w-[1160px] w-full mx-auto px-4 md:px-8 pt-[52px]">

        <div class="grid grid-cols-2 md:grid-cols-footer gap-8 md:gap-10 mb-10">

            <div class="col-span-2 md:col-span-1">
                <div class="heading text-[20px] tracking-[2px] mb-3">SHOP.CO</div>
                <p class="text-[12px] text-[#888] leading-[1.8] m-0 mb-[18px] max-w-[200px]">
                    We have clothes that suits your style and which you're proud to wear. From women to men.
                </p>
                <div class="flex gap-[9px]">
                    <a href="#"><img src="{{ asset('assets/icons/socials/facebook.svg') }}" alt="Facebook"
                            width="32" height="32"></a>
                    <a href="#"><img src="{{ asset('assets/icons/socials/instagram.svg') }}" alt="Instagram"
                            width="32" height="32"></a>
                    <a href="#"><img src="{{ asset('assets/icons/socials/twitter.svg') }}" alt="Twitter"
                            width="32" height="32" /></a>
                    <a href="#"><img src="{{ asset('assets/icons/socials/linkedin.svg') }}" alt="LinkedIn"
                            width="32" height="32" /></a>
                </div>
            </div>

            <div>
                <div class="footer-col-title">COMPANY</div>
                <ul class="list-none p-0 m-0 flex flex-col gap-[13px]">
                    <li><a href="#" class="footer-link">About</a></li>
                    <li><a href="#" class="footer-link">Features</a></li>
                    <li><a href="#" class="footer-link">Works</a></li>
                    <li><a href="#" class="footer-link">Career</a></li>
                </ul>
            </div>

            <div>
                <div class="footer-col-title">HELP</div>
                <ul class="list-none p-0 m-0 flex flex-col gap-[13px]">
                    <li><a href="#" class="footer-link">Customer Support</a></li>
                    <li><a href="#" class="footer-link">Delivery Details</a></li>
                    <li><a href="#" class="footer-link">Terms &amp; Conditions</a></li>
                    <li><a href="#" class="footer-link">Privacy Policy</a></li>
                </ul>
            </div>

            <div>
                <div class="footer-col-title">FAQ</div>
                <ul class="list-none p-0 m-0 flex flex-col gap-[13px]">
                    <li><a href="#" class="footer-link">Account</a></li>
                    <li><a href="#" class="footer-link">Manage Deliveries</a></li>
                    <li><a href="#" class="footer-link">Orders</a></li>
                    <li><a href="#" class="footer-link">Payments</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-[#e5e5e5] pt-5 flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-[12px] text-[#aaa]">Shop.co © 2000-2025. All Rights Reserved</span>
            <div class="flex flex-wrap items-center justify-center gap-2">
                <span class="payment-badge">VISA</span>
                <span class="payment-badge">Mastercard</span>
                <span class="payment-badge">PayPal</span>
                <span class="payment-badge">Apple Pay</span>
                <span class="payment-badge">G Pay</span>
            </div>
        </div>

    </div>
</footer>
