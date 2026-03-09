import Hero from "../components/Hero";
import {
  TopBrandSeller,
  TopSellers,
  NewArrivals,
} from "../components/Sections";
import Newsletter from "../components/Newsletter";

export default function Home() {
  return (
    <>
      <Hero />
      <TopBrandSeller />
      <TopSellers />
      <NewArrivals />
      <Newsletter />
    </>
  );
}
