export interface Testimonial {
  name: string;
  role: string;
  text: string;
  image: string;
}

export interface Property {
  id: number;
  title: string;
  price: string;
  pricePerSqm: string;
  location: string;
  size: string;
  agent: string;
  features: string[];
}
