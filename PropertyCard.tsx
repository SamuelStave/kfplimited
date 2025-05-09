import { Property } from './types';

interface PropertyCardProps {
  property: Property;
}

export const PropertyCard = ({ property }: PropertyCardProps) => {
  return (
    <div className="overflow-hidden rounded bg-white shadow-[0_2px_4px_rgba(0,0,0,0.1)]">
      <img
        className="object-cover overflow-hidden w-full aspect-square h-[200px]"
        src="https://images.nigeriapropertycentre.com/properties/images/thumbs/2812755/067f796136b06a-affordable-land-in-ubulu-okiti-asaba-delta-state-nigeria-residential-land-for-sale-asaba-delta.jpeg"
        alt={property.title}
      />
      <div className="p-5 text-black">
        <h3 className="mb-2.5 text-xl font-semibold">
          <span>{property.title}</span>
          <div className="mt-2.5 text-2xl text-blue-700 font-bold">
            {property.price}
          </div>
          <div className="mt-1.5 text-base text-stone-500">
            <span>{property.location}</span>
            <span> • </span>
            <span>{property.size}</span>
          </div>
        </h3>
        <p className="mb-5">Prime location with excellent investment potential</p>
        <a className="px-5 py-2.5 font-semibold no-underline bg-blue-700 rounded-sm text-white">
          View Details
        </a>
      </div>
    </div>
  );
};
