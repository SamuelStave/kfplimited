"use client";
import { Navigation } from './Navigation';
import { HeroSection } from './HeroSection';
import { TestimonialsSection } from './TestimonialsSection';
import { AboutSection } from './AboutSection';
import { PropertiesSection } from './PropertiesSection';
import { ContactSection } from './ContactSection';
import { Footer } from './Footer';
import { WhatsAppButton } from './WhatsAppButton';

export const MainPage = () => {
  return (
    <div className="pt-20 text-white bg-blue-600 max-md:pt-16 max-sm:pt-12">
      <Navigation />
      <HeroSection />
      <TestimonialsSection />
      <AboutSection />
      <PropertiesSection />
      <ContactSection />
      <WhatsAppButton />
      <Footer />
    </div>
  );
};

export default MainPage;
