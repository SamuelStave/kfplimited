import { Testimonial } from './types';

const testimonials: Testimonial[] = [
  {
    name: "Oluwaseun Adebayo",
    role: "Property Investor",
    text: "The team at King's Farms made my land acquisition process seamless. Their expertise in the Nigerian real estate market is unmatched!",
    image: "https://images.pexels.com/photos/31928799/pexels-photo-31928799.jpeg"
  },
  {
    name: "Chioma Okonkwo",
    role: "Real Estate Developer",
    text: "Excellent service and professional guidance. They helped me secure prime land in Lagos at a great value.",
    image: "https://images.pexels.com/photos/30677592/pexels-photo-30677592.jpeg"
  },
  {
    name: "Babajide Ogunleye",
    role: "First-time Buyer",
    text: "As a first-time investor, they guided me through every step. Their knowledge of land documentation in Nigeria is impressive!",
    image: "https://images.pexels.com/photos/7191260/pexels-photo-7191260.jpeg"
  }
];

export const TestimonialsSection = () => {
  return (
    <section className="overflow-hidden relative px-5 py-20 bg-blue-950 text-white">
      <div className="mx-auto my-0 max-w-screen-lg">
        <h2 className="mb-10 text-3xl font-semibold text-center">
          What Our Clients Say
        </h2>
        <div className="grid gap-8 grid-cols-[repeat(3,1fr)] max-md:grid-cols-[repeat(2,1fr)] max-sm:grid-cols-[1fr]">
          {testimonials.map((testimonial) => (
            <div
              className="p-8 text-center rounded-lg bg-white bg-opacity-10"
              key={testimonial.name}
            >
              <img
                className="object-cover overflow-hidden mx-auto mt-0 mb-5 w-20 h-20 rounded-full aspect-square"
                src={testimonial.image}
                alt={testimonial.name}
              />
              <p className="mb-5 text-base italic leading-relaxed">
                &quot;{testimonial.text}&quot;
              </p>
              <h3 className="mb-1.5 text-lg font-semibold">
                {testimonial.name}
              </h3>
              <p className="text-sm text-white text-opacity-80">
                {testimonial.role}
              </p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};
