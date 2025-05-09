import { Property } from './types';
import { PropertyCard } from './PropertyCard';

const properties: Property[] = [
  {
    id: 1,
    title: "Premium Land at Lekki",
    price: "₦25,000,000",
    pricePerSqm: "₦50,000/sqm",
    location: "Lekki Phase 1",
    size: "500 sqm",
    agent: "Aisha Mohammed",
    features: ["Dry Land", "C of O Available", "Secured Location"]
  },
  {
    id: 2,
    title: "Residential Plot at Ajah",
    price: "₦18,500,000",
    pricePerSqm: "₦41,111/sqm",
    location: "Ajah",
    size: "450 sqm",
    agent: "Tunde Bakare",
    features: ["Fenced", "Survey Plan", "Ready to Build"]
  },
  {
    id: 3,
    title: "Commercial Land at Victoria Island",
    price: "₦45,000,000",
    pricePerSqm: "₦75,000/sqm",
    location: "Victoria Island",
    size: "600 sqm",
    agent: "Elizabeth Okoro",
    features: ["Prime Location", "C of O Available", "Corner Piece"]
  }
];

export const PropertiesSection = () => {
  return (
    <section className="px-5 py-20 bg-blue-950 text-white">
      <div className="mx-auto my-0 max-w-screen-lg">
        <span className="mb-2.5 text-sm font-semibold tracking-wider text-indigo-200 uppercase">
          Land opportunities
        </span>
        <h2 className="mb-10 text-3xl font-semibold">
          Invest in prime lands today!
        </h2>
        <div className="grid gap-8 grid-cols-[repeat(3,1fr)] max-md:grid-cols-[repeat(2,1fr)] max-sm:grid-cols-[1fr]">
          {properties.map((property) => (
            <PropertyCard key={property.id} property={property} />
          ))}
        </div>
      </div>
    </section>
  );
};
