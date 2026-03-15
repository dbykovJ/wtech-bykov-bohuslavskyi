@extends('layouts.app')

@section('title', 'SUPERSELL')

@section('content')

    <section class="max-w-[1160px] mx-auto px-4 md:px-8 pt-10 md:pt-12 pb-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            <div>
                <h1 class="heading text-[38px] sm:text-[48px] md:text-[58px] leading-[1.03] tracking-[-1px] mb-[14px]">
                    FIND CLOTHES<br>THAT MATCHES<br>YOUR STYLE
                </h1>
                <p class="text-[13px] text-[#666] leading-[1.75] max-w-[340px] mb-7">
                    Browse through our diverse range of meticulously crafted garments, designed to bring out your individuality and cater to your sense of style.
                </p>
                <a href="#" class="inline-block bg-[#111] text-white text-sm font-bold py-[13px] px-10 rounded-full select-none" draggable="false">
                    Shop Now
                </a>
                <div class="flex flex-wrap items-center gap-4 md:gap-5 mt-10 md:mt-12 pt-6 border-t border-[#e5e5e5]">
                    <div>
                        <div class="heading text-[26px] md:text-[30px] leading-[1.1]">200+</div>
                        <div class="text-[11px] text-[#888] mt-[3px]">International Brands</div>
                    </div>
                    <div class="w-px h-[38px] bg-[#e5e5e5]"></div>
                    <div>
                        <div class="heading text-[26px] md:text-[30px] leading-[1.1]">2,000+</div>
                        <div class="text-[11px] text-[#888] mt-[3px]">High Quality Products</div>
                    </div>
                    <div class="w-px h-[38px] bg-[#e5e5e5]"></div>
                    <div>
                        <div class="heading text-[26px] md:text-[30px] leading-[1.1]">30,000+</div>
                        <div class="text-[11px] text-[#888] mt-[3px]">Happy Customers</div>
                    </div>
                </div>
            </div>
            <div class="placeholder w-full h-[260px] sm:h-[320px] md:h-[400px]"></div>
        </div>
    </section>

    <section class="bg-[#f9f9f9] border-t border-b border-[#e8e8e8] py-11">
        <div class="max-w-[1160px] mx-auto px-4 md:px-8">
            <h2 class="section-heading">TOP BRAND SELLER</h2>
            <div class="hidden md:grid grid-cols-brands gap-3 h-[150px]">
                <div class="placeholder rounded-[12px] h-full"></div>
                <div class="grid grid-cols-3 grid-rows-brands-inner gap-[10px]">
                    <div class="placeholder rounded-[10px]"></div>
                    <div class="placeholder rounded-[10px]"></div>
                    <div class="placeholder rounded-[10px]"></div>
                    <div class="placeholder rounded-[10px]"></div>
                    <div class="placeholder rounded-[10px]"></div>
                    <div class="placeholder rounded-[10px]"></div>
                </div>
                <div class="grid grid-rows-2 gap-[10px]">
                    <div class="placeholder rounded-[10px]"></div>
                    <div class="placeholder rounded-[10px]"></div>
                </div>
            </div>
            <div class="grid md:hidden grid-cols-2 gap-3">
                <div class="placeholder rounded-[10px] h-[80px]"></div>
                <div class="placeholder rounded-[10px] h-[80px]"></div>
                <div class="placeholder rounded-[10px] h-[80px]"></div>
                <div class="placeholder rounded-[10px] h-[80px]"></div>
            </div>
        </div>
    </section>

    <section class="max-w-[1160px] mx-auto px-4 md:px-8 py-12">
        <h2 class="section-heading">TOP SELLERS</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
            @for ($i = 1; $i <= 4; $i++)
                <div>
                    <div class="placeholder w-full aspect-square"></div>
                    <div class="mt-[10px]">
                        <div class="product-name">Item {{ $i }}</div>
                        <div class="star-rating" style="--rating: 4">★★★★★</div>
                        <div class="product-price-row">
                            <span class="product-price">$212</span>
                            <span class="product-price-original">$232</span>
                            <span class="badge-red">-8%</span>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </section>

    <section class="max-w-[1160px] mx-auto px-4 md:px-8 pb-14">
        <h2 class="section-heading">NEW ARRIVALS</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
            @for ($i = 1; $i <= 4; $i++)
                <div>
                    <div class="placeholder w-full aspect-square"></div>
                    <div class="mt-[10px]">
                        <div class="product-name">Item {{ $i }}</div>
                        <div class="star-rating" style="--rating: 4">★★★★★</div>
                        <div class="product-price-row">
                            <span class="product-price">$212</span>
                            <span class="product-price-original">$232</span>
                            <span class="badge-red">-8%</span>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </section>
@endsection