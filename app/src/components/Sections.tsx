import ProductCard from "./ProductCard";

const topSellers = [
  { name: "Item 1", price: 212, originalPrice: 232, discount: 8, rating: 4 },
  { name: "Item 2", price: 212, originalPrice: 232, discount: 8, rating: 4 },
  { name: "Item 3", price: 212, originalPrice: 232, discount: 8, rating: 4 },
  { name: "Item 4", price: 212, originalPrice: 232, discount: 8, rating: 4 },
];

const newArrivals = [
  { name: "Item 1", price: 212, originalPrice: 232, discount: 8, rating: 4 },
  { name: "Item 2", price: 212, originalPrice: 232, discount: 8, rating: 4 },
  { name: "Item 3", price: 212, originalPrice: 232, discount: 8, rating: 4 },
  { name: "Item 4", price: 212, originalPrice: 232, discount: 8, rating: 3.5 },
];

export function TopBrandSeller() {
  return (
    <section className="border-t border-b border-[#e8e8e8] bg-[#f9f9f9] py-11">
      <div className="mx-auto max-w-290 px-8">
        <h2 className="section-heading">TOP BRAND SELLER</h2>
        <div className="grid-cols-brands grid h-37.5 gap-3">
          <div className="placeholder h-full rounded-xl" />
          <div className="grid grid-cols-3 grid-rows-2 gap-2.5">
            {Array.from({ length: 6 }).map((_, i) => (
              <div key={i} className="placeholder rounded-[10px]" />
            ))}
          </div>
          <div className="grid grid-rows-2 gap-2.5">
            <div className="placeholder rounded-[10px]" />
            <div className="placeholder rounded-[10px]" />
          </div>
        </div>
      </div>
    </section>
  );
}

export function TopSellers() {
  return (
    <section className="mx-auto max-w-290 px-8 py-12">
      <h2 className="section-heading">TOP SELLERS</h2>
      <div className="grid grid-cols-4 gap-5">
        {topSellers.map((item) => (
          <ProductCard key={item.name} {...item} />
        ))}
      </div>
    </section>
  );
}

export function NewArrivals() {
  return (
    <section className="mx-auto max-w-290 px-8 pb-14">
      <h2 className="section-heading">NEW ARRIVALS</h2>
      <div className="grid grid-cols-4 gap-5">
        {newArrivals.map((item) => (
          <ProductCard key={item.name} {...item} />
        ))}
      </div>
    </section>
  );
}
