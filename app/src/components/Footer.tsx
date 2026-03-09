import { FaFacebook, FaLinkedin } from "react-icons/fa";
import { FaXTwitter } from "react-icons/fa6";
import { AiFillInstagram } from "react-icons/ai";
import { Link } from "react-router-dom";

export default function Footer() {
  return (
    <footer className="border-t border-[#e5e5e5] bg-white pt-25 pb-6">
      <div className="mx-auto max-w-290 px-8">
        <div className="grid-cols-footer mb-10 grid gap-10">
          <div>
            <div className="heading mb-3 text-[20px] tracking-[2px]">
              SHOP.CO
            </div>
            <p className="m-0 mb-4.5 max-w-50 text-[12px] leading-[1.8] text-[#888]">
              We have clothes that suits your style and which you're proud to
              wear. From women to men.
            </p>
            <div className="flex gap-2.25">
              <FaFacebook size={32} />
              <AiFillInstagram size={32} />
              <FaXTwitter size={32} />
              <FaLinkedin size={32} />
            </div>
          </div>

          <div>
            <div className="footer-col-title">COMPANY</div>
            <ul className="m-0 flex list-none flex-col gap-3.25 p-0">
              {["About", "Features", "Works", "Career"].map((link) => (
                <Link
                  to={`/${link.toLowerCase().replace("/\s+\g", "-")}`}
                  className="footer-link"
                  draggable="false"
                >
                  {link}
                </Link>
              ))}
            </ul>
          </div>

          <div>
            <div className="footer-col-title">HELP</div>
            <ul className="m-0 flex list-none flex-col gap-3.25 p-0">
              {[
                "Customer Support",
                "Delivery Details",
                "Terms & Conditions",
                "Privacy Policy",
              ].map((link) => (
                <Link
                  to={`/${link.toLowerCase().replace("/\s+\g", "-")}`}
                  className="footer-link"
                  draggable="false"
                >
                  {link}
                </Link>
              ))}
            </ul>
          </div>

          <div>
            <div className="footer-col-title">FAQ</div>
            <ul className="m-0 flex list-none flex-col gap-3.25 p-0">
              {["Account", "Manage Deliveries", "Orders", "Payments"].map(
                (link) => (
                  <Link
                    to={`/${link.toLowerCase().replace("/\s+\g", "-")}`}
                    className="footer-link"
                    draggable="false"
                  >
                    {link}
                  </Link>
                ),
              )}
            </ul>
          </div>
        </div>

        <div className="flex items-center justify-between border-t border-[#e5e5e5] pt-5">
          <span className="text-[12px] text-[#aaa]">
            Shop.co © 2000-2025. All Rights Reserved
          </span>
          <div className="flex items-center gap-2">
            {["VISA", "Mastercard", "PayPal", "Apple Pay", "G Pay"].map(
              (badge) => (
                <span key={badge} className="payment-badge">
                  {badge}
                </span>
              ),
            )}
          </div>
        </div>
      </div>
    </footer>
  );
}
