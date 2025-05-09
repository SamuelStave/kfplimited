"use client";
import { useState } from 'react';

export const Navigation = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  return (
    <nav className="fixed inset-x-0 top-0 p-5 border-b border-solid bg-blue-950 border-b-white border-b-opacity-40 z-[100]">
      <div className="flex justify-between items-center mx-auto my-0 max-w-screen-lg">
        <a className="flex items-center no-underline">
          <img
            src="https://cdn.builder.io/api/v1/image/assets%2Fcf04c8d6d7ed4f12a4d6db8d3970edf4%2F9f9e6179c7bd4fc384a2f8f09eba89a6"
            className="object-cover overflow-hidden mr-2.5 w-20 h-auto aspect-square"
            alt="Company Logo"
          />
        </a>
        <button
          onClick={() => setIsMenuOpen(!isMenuOpen)}
          className="text-white md:hidden"
        >
          Menu
        </button>
        <div
          className={`md:block ${isMenuOpen ? 'block' : 'hidden'} max-sm:absolute max-sm:top-full max-sm:left-0 max-sm:right-0 max-sm:bg-blue-950`}
        >
          <ul className="flex gap-6 justify-center items-center max-sm:flex-col max-sm:p-5">
            <li><a className="no-underline text-white">Home</a></li>
            <li><a className="no-underline text-white">Properties</a></li>
            <li><a className="no-underline text-white">About</a></li>
            <li>
              <a
                href="https://wa.me/2348037319301"
                target="_blank"
                className="overflow-hidden px-8 py-1.5 py-2.5 no-underline rounded-3xl border-2 border-solid cursor-pointer pointer-events-auto border-white text-white"
              >
                Contact
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  );
};
