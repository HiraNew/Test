// resources/js/Pages/Portfolio/PrivacyPolicy.jsx
import React from "react";
import { motion } from "framer-motion";
import Layout from "@/Layouts/navbar"; // Adjust the path if needed


const Services = () => {
  return (
    <Layout>
      <div className="bg-gray-50 text-gray-800 min-h-screen py-16 px-6 sm:px-12 lg:px-24">
        <motion.div
          initial={{ y: 40, opacity: 0 }}
          animate={{ y: 0, opacity: 1 }}
          transition={{ duration: 0.8 }}
          className="max-w-4xl mx-auto"
        >
          {/* Title Section */}
          <h1 className="text-4xl font-bold mb-6 text-center text-indigo-700">
            What We Offer
          </h1>
          <p className="mb-6 text-lg text-gray-600 text-center">
            At DLS, we're dedicated to empowering individuals and businesses through technology and education. Here's how we serve you:
          </p>

          {/* Offerings */}
          <ServiceSection
            title="Job-Oriented Development Courses"
            direction="left"
            description="Learn practical, in-demand coding and development skills with our expert-led training programs. We offer both online and in-person classes designed to prepare you for real-world tech careers."
          />

          <ServiceSection
            title="Online & Offline Product Sales"
            direction="right"
            description="Shop from a wide range of quality products available for both online purchase and in-store delivery. We make it easy and accessible to get what you need, wherever you are."
          />

          <ServiceSection
            title="Custom Software Solutions"
            direction="left"
            description="Have an idea for your business? We help bring it to life. From web applications to full-scale software systems, we provide tailored solutions that meet your unique needs."
          />

          <ServiceSection
            title="Get Your Store Online"
            direction="right"
            description="Thinking of going digital? We support businesses and entrepreneurs in launching their online presence—from e-commerce websites to branding and beyond."
          />

          {/* CTA */}
          <motion.div
            initial={{ scale: 0.9, opacity: 0 }}
            whileInView={{ scale: 1, opacity: 1 }}
            transition={{ duration: 0.8 }}
            viewport={{ once: true }}
            className="mt-12 bg-indigo-100 p-6 rounded-lg shadow-lg text-center"
          >
            <h3 className="text-xl font-semibold text-indigo-800 mb-2">Join Our Growing Community</h3>
            <p className="text-gray-700 mb-4">
              Whether you're shopping, learning, or building — you're in good hands.
            </p>
            <a
              href="/contact"
              className="inline-block px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition"
            >
              Get Started Today
            </a>
          </motion.div>
        </motion.div>
      </div>
    </Layout>
  );
};

// Reusable Section Component
const ServiceSection = ({ title, description, direction = "left" }) => (
  <motion.div
    initial={{ opacity: 0, x: direction === "left" ? -30 : 30 }}
    whileInView={{ opacity: 1, x: 0 }}
    transition={{ duration: 0.6 }}
    viewport={{ once: true }}
    className="mb-10"
  >
    <h2 className="text-2xl font-semibold text-indigo-600 mb-2">{title}</h2>
    <p className="text-gray-700">{description}</p>
  </motion.div>
);

export default Services;
