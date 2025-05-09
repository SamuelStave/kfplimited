"use client";
import { useState } from 'react';

export const ContactSection = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    message: ''
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Handle form submission
  };

  return (
    <section className="px-5 py-20 bg-white">
      <div className="flex gap-10 mx-auto my-0 max-w-screen-lg max-md:flex-col">
        <div className="flex-1">
          <h2 className="mb-8 text-3xl font-semibold text-black">
            Get in touch
          </h2>
          <form onSubmit={handleSubmit}>
            <div className="mb-5">
              <label className="mb-2 font-medium text-black">Name</label>
              <input
                type="text"
                className="p-3 w-full rounded border border-solid border-stone-500"
                value={formData.name}
                onChange={(e) => setFormData({...formData, name: e.target.value})}
              />
            </div>
            <div className="mb-5">
              <label className="mb-2 font-medium text-black">Email</label>
              <input
                type="email"
                className="p-3 w-full rounded border border-solid border-stone-500"
                value={formData.email}
                onChange={(e) => setFormData({...formData, email: e.target.value})}
              />
            </div>
            <div className="mb-5">
              <label className="mb-2 font-medium text-black">Message</label>
              <textarea
                className="p-3 w-full rounded border border-solid border-stone-500 min-h-[120px]"
                value={formData.message}
                onChange={(e) => setFormData({...formData, message: e.target.value})}
              />
            </div>
            <button className="px-8 py-4 w-full font-semibold bg-blue-700 rounded-sm cursor-pointer border-none text-white">
              Send Message
            </button>
          </form>
        </div>
        <div className="overflow-hidden flex-1 rounded-md border-2 border-blue-700 border-solid">
          <div className="p-10 bg-blue-700 rounded">
            <h3 className="mb-5 text-2xl font-semibold text-white">
              Contact Information
            </h3>
            <div className="mb-5 text-white">
              <p className="mb-2.5">
                <strong>Phone:</strong>
                <span> +234 803 731 9301</span>
              </p>
              <p className="mb-2.5">
                <strong>Email:</strong>
                <span> contact@royalestate.com</span>
              </p>
              <p>
                <strong>Address:</strong>
                <span> Lagos, Nigeria</span>
              </p>
            </div>
            <div className="text-white">
              <h4 className="mb-2.5 text-lg font-semibold">
                Business Hours
              </h4>
              <p className="mb-1.5">Monday - Friday: 9:00 AM - 6:00 PM</p>
              <p className="mb-1.5">Saturday: 10:00 AM - 4:00 PM</p>
              <p>Sunday: Closed</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};
