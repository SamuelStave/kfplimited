export const HeroSection = () => {
  return (
    <section className="flex relative items-center px-5 py-0 mt-20 h-[600px]">
      <img
        src="https://images.pexels.com/photos/8134850/pexels-photo-8134850.jpeg"
        alt="Hero background"
        className="object-cover overflow-hidden absolute inset-x-0 bottom-0 w-full aspect-square brightness-[0.7] h-[711px] top-[-79px]"
      />
      <div className="relative mx-auto my-0 w-full max-w-screen-lg text-white">
        <h1 className="mb-5 text-6xl font-bold max-md:text-5xl max-md:leading-tight max-sm:text-4xl max-sm:leading-none">
          Find Your Dream Home
        </h1>
        <p className="mb-10 text-2xl">
          Discover exceptional properties in prime locations
        </p>
        <a className="overflow-hidden px-10 py-4 font-semibold tracking-wider no-underline uppercase bg-blue-700 rounded-xl text-white">
          View Properties
        </a>
      </div>
    </section>
  );
};
