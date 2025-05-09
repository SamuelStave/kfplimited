export const WhatsAppButton = () => {
  return (
    <a
      className="flex fixed right-5 bottom-5 justify-center items-center bg-green-500 rounded-full transition-transform duration-[0.3s] ease-[ease] h-[60px] shadow-[0_2px_12px_rgba(0,0,0,0.2)] text-white w-[60px] z-[1000]"
      href="https://wa.me/2348037319301"
      target="_blank"
      rel="noopener noreferrer"
      aria-label="Contact us on WhatsApp"
    >
      <svg viewBox="0 0 24 24" width="30" height="30" fill="currentColor">
        <path d="M20.4 3.6C18.2 1.4 15.2 0 12 0 5.4 0 0 5.4 0 12c0 2.1.6 4.2 1.7 6L0 24l6.2-1.6c1.8 1 3.8 1.5 5.8 1.5 6.6 0 12-5.4 12-12 0-3.2-1.4-6.2-3.6-8.4zM12 22c-1.8 0-3.6-.5-5.2-1.4l-.4-.2-3.7 1 1-3.6-.2-.4c-1-1.6-1.5-3.4-1.5-5.3 0-5.5 4.5-10 10-10s10 4.5 10 10-4.5 10-10 10zm5.5-7.4c-.3-.1-1.8-.9-2.1-1-.3-.1-.5-.1-.7.1-.2.2-.8.9-1 1.1-.2.2-.3.2-.6.1-1.7-.9-2.8-1.6-4-3.6-.3-.5.3-.5.9-1.7.1-.2 0-.4-.1-.6s-.7-1.8-1-2.4c-.3-.7-.6-.6-.8-.6h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.4s1 2.8 1.2 3c.2.2 2.1 3.2 5.1 4.5.7.3 1.3.5 1.7.6.7.2 1.4.2 1.9.1.6-.1 1.8-.7 2-1.4.2-.7.2-1.3.2-1.4 0-.2-.2-.3-.5-.4z" />
      </svg>
    </a>
  );
};
