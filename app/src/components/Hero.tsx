export default function Hero() {
  return (
    <section className="mx-auto max-w-290 px-8 pt-12 pb-10">
      <div className="grid grid-cols-2 items-start gap-8">
        <div>
          <h1 className="heading mb-3.5 text-[58px] leading-[1.03] tracking-[-1px]">
            FIND CLOTHES
            <br />
            THAT MATCHES
            <br />
            YOUR STYLE
          </h1>
          <p className="mb-7 max-w-85 text-[13px] leading-[1.75] text-[#666]">
            Browse through our diverse range of meticulously crafted garments,
            designed to bring out your individuality and cater to your sense of
            style.
          </p>
          <a
            href="#"
            className="inline-block rounded-full bg-[#111] px-10 py-3.25 text-sm font-bold text-white transition-colors hover:bg-[#333]"
          >
            Shop Now
          </a>

          <div className="mt-12 flex items-center gap-5 border-t border-[#e5e5e5] pt-6">
            <div>
              <div className="hero-heading">200+</div>
              <div className="hero-text">International Brands</div>
            </div>
            <div className="h-9.5 w-px bg-[#e5e5e5]" />
            <div>
              <div className="hero-heading">2,000+</div>
              <div className="hero-text">High Quality Products</div>
            </div>
            <div className="h-9.5 w-px bg-[#e5e5e5]" />
            <div>
              <div className="hero-heading">30,000+</div>
              <div className="hero-text">Happy Customers</div>
            </div>
          </div>
        </div>

        <div className="placeholder h-100 w-full" />
      </div>
    </section>
  );
}
