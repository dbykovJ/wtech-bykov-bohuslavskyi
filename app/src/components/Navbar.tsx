import { useState } from "react";
import { Link } from "react-router-dom";
import { User, ShoppingBasket } from "lucide-react";
import { IoSearch } from "react-icons/io5";
import { IoMdArrowDropdown } from "react-icons/io";

export default function Navbar() {
  const [shopOpen, setShopOpen] = useState(false);

  return (
    <nav className="sticky top-0 z-50 border-b border-[#e5e5e5] bg-white py-3.25">
      <div className="mx-auto flex max-w-290 items-center gap-7 px-8">
        <Link to="/" className="inline-block leading-none">
          <span className="heading text-[20px] tracking-[2px] whitespace-nowrap">
            SUPERSELL
          </span>
        </Link>

        <div className="flex items-center gap-5">
          {/* Shop dropdown */}
          <div
            className="relative"
            onMouseEnter={() => setShopOpen(true)}
            onMouseLeave={() => setShopOpen(false)}
          >
            <button className="nav-link cursor-pointer border-none bg-transparent">
              Shop
              <IoMdArrowDropdown />
            </button>

            {shopOpen && (
              <div className="absolute top-full left-1/4 z-50 mt-2 w-40 -translate-x-1/4 rounded-[10px] border border-[#e5e5e5] bg-white shadow-md">
                <div className="absolute -top-2 left-0 h-2 w-full" />
                <Link
                  to="/products/men"
                  className="block px-4 py-2 text-[13px] text-[#111] hover:bg-[#f5f5f5]"
                >
                  Men
                </Link>
                <Link
                  to="/products/women"
                  className="block px-4 py-2 text-[13px] text-[#111] hover:bg-[#f5f5f5]"
                >
                  Women
                </Link>
                <Link
                  to="/products/kids"
                  className="block px-4 py-2 text-[13px] text-[#111] hover:bg-[#f5f5f5]"
                >
                  Kids
                </Link>
                <Link
                  to="/products/sale"
                  className="block px-4 py-2 text-[13px] text-[#111] hover:bg-[#f5f5f5]"
                >
                  Sale
                </Link>
              </div>
            )}
          </div>

          <Link to="/sale" draggable="false" className="nav-link">
            On Sale
          </Link>
          <Link to="/new" draggable="false" className="nav-link">
            New Arrivals
          </Link>
          <Link to="/brands" draggable="false" className="nav-link">
            Brands
          </Link>
        </div>

        <div className="mx-3 flex-1">
          <div className="flex items-center gap-2 rounded-full bg-[#f0f0f0] px-4 py-2">
            <IoSearch size={16} />
            <input
              type="search"
              placeholder="Search for Products"
              className="w-full border-none bg-transparent text-[13px] text-black outline-none placeholder:text-[#888]"
            />
          </div>
        </div>

        <div className="flex items-center gap-3.5">
          <Link to="/cart">
            <ShoppingBasket size={24} color="#000000" />
          </Link>
          <Link to="/account">
            <User size={24} color="#000000" />
          </Link>
        </div>
      </div>
    </nav>
  );
}
