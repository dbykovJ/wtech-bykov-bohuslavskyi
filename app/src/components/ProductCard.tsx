interface ProductCardProps {
    name: string;
    price: number;
    originalPrice: number;
    discount: number;
    rating: number;
}

export default function ProductCard({name, price, originalPrice, discount, rating}: ProductCardProps) {
    return (
        <div>
            <div className="placeholder w-full aspect-square rounded-[10px]"/>
            <div className="mt-2.5">
                <div className="product-name">{name}</div>
                <div className="star-rating" style={{"--rating": rating} as React.CSSProperties}>★★★★★</div>
                <div className="product-price-row">
                    <span className="product-price">${price}</span>
                    <span className="product-price-original">${originalPrice}</span>
                    <span className="badge-red">-{discount}%</span>
                </div>
            </div>
        </div>
    );
}