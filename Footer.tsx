export const Footer = () => {
  return (
    <footer className="px-5 pt-16 pb-5 bg-blue-950 text-white">
      <div className="mx-auto my-0 max-w-screen-lg">
        <div className="grid gap-10 mb-10 grid-cols-[repeat(4,1fr)] max-md:grid-cols-[repeat(2,1fr)] max-sm:grid-cols-[1fr]">
          <div>
            <h4 className="mb-5 text-lg font-semibold">About Us</h4>
            <p className="leading-relaxed">
              Your trusted partner in real estate, providing exceptional
              property solutions.
            </p>
          </div>
          <div>
            <h4 className="mb-5 text-lg font-semibold">Quick Links</h4>
            <ul className="p-0">
              <li className="mb-2.5">
                <a className="no-underline text-white">Home</a>
              </li>
              <li className="mb-2.5">
                <a className="no-underline text-white">Properties</a>
              </li>
              <li className="mb-2.5">
                <a className="no-underline text-white">About</a>
              </li>
              <li>
                <a className="no-underline text-white">Contact</a>
              </li>
            </ul>
          </div>
          <div>
            <h4 className="mb-5 text-lg font-semibold">Contact</h4>
            <p className="mb-2.5">Lagos, Nigeria</p>
            <p className="mb-2.5">Phone: +234 803 731 9301</p>
            <p>Email: info@kfplimited.com.ng</p>
          </div>
          <div>
            <h4 className="mb-5 text-lg font-semibold">Follow Us</h4>
            <div className="flex gap-4">
              <a className="no-underline text-white">Facebook</a>
              <a className="no-underline text-white">Twitter</a>
              <a className="no-underline text-white">Instagram</a>
            </div>
          </div>
        </div>
        <div className="pt-5 text-center border-t border-solid border-t-white border-t-opacity-10">
          <p>
            © 2025 King's Farms And Properties Ltd.. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  );
};
