export const AboutSection = () => {
  return (
    <section className="px-5 py-20 text-black bg-white">
      <div className="flex gap-10 items-center mx-auto my-0 max-w-screen-lg max-sm:flex-col">
        <div className="flex-1">
          <span className="block mb-2.5 text-sm font-semibold tracking-wider text-blue-700 uppercase">
            <span className="bg-blue-700 bg-opacity-0">
              <span className="text-[#0420a7]">Royal real estate solutions</span>
            </span>
          </span>
          <h2 className="mb-5 text-3xl font-semibold">
            Your trusted partner in land sales
          </h2>
          <p className="mb-5 leading-relaxed">
            At King's Farms and Properties Limited, we pride ourselves on
            being the go-to real estate company for land sales and
            agricultural opportunities. With a commitment to excellence and a
            focus on customer satisfaction, we connect buyers with prime
            properties that meet their needs.
          </p>
          <a className="inline-block mt-5 font-semibold text-[#0a26a6] underline">
            Get in touch
          </a>
        </div>
        <div className="flex-1">
          <img
            src="https://images.pexels.com/photos/7535032/pexels-photo-7535032.jpeg"
            alt="About us"
            className="object-cover overflow-hidden w-full rounded aspect-square h-[400px]"
          />
        </div>
      </div>
    </section>
  );
};
