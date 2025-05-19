// resources/js/Pages/Portfolio/PrivacyPolicy.jsx
import React from "react";
import { motion } from "framer-motion";
import Layout from "@/Layouts/navbar"; // or change this to your actual layout path

const PrivacyPolicy = () => {
  return (
    <Layout>
      <div className="bg-gray-50 text-gray-800 min-h-screen py-16 px-6 sm:px-12 lg:px-24">
        <motion.div
          initial={{ y: 40, opacity: 0 }}
          animate={{ y: 0, opacity: 1 }}
          transition={{ duration: 0.8 }}
          className="max-w-4xl mx-auto"
        >
          <h1 className="text-4xl font-bold mb-6 text-center text-indigo-700">
            Privacy Policy
          </h1>
          <p className="mb-6 text-lg text-gray-600 text-center">
            Your privacy is important to us. Here's how we collect, use, and protect your data across our services.
          </p>

          {/* Sections... */}
          {/* Demo Classes */}
          <Section title="Free Software Development Demo Classes" direction="left">
            We offer free demo classes for individuals interested in software development. No fees, no commitment—
            just real value to help you understand the path to becoming a developer. If you feel inspired, you're
            welcome to continue your learning journey with us at no cost.
          </Section>

          {/* General Store */}
          <Section title="General Store – Affordable, Quality Products" direction="right">
            We run a general store offering everyday products at affordable prices. All transactions are securely handled.
            Unsatisfied? We offer a no-hassle return policy. Customer satisfaction is our top priority.
          </Section>

          {/* Software Services */}
          <Section title="Custom Software & Web Development Services" direction="left">
            From static websites to dynamic web applications, we build reliable and affordable software solutions tailored to your business.
            We keep your data fully secure and confidential.
          </Section>

          {/* Call to Action */}
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

// Helper reusable section component
const Section = ({ title, children, direction = "left" }) => (
  <motion.div
    initial={{ opacity: 0, x: direction === "left" ? -30 : 30 }}
    whileInView={{ opacity: 1, x: 0 }}
    transition={{ duration: 0.6 }}
    viewport={{ once: true }}
    className="mb-10"
  >
    <h2 className="text-2xl font-semibold text-indigo-600 mb-2">{title}</h2>
    <p className="text-gray-700">{children}</p>
  </motion.div>
);

export default PrivacyPolicy;
