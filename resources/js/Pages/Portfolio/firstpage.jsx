import React, { useState, useEffect } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { TypeAnimation } from 'react-type-animation';



// Single animated card
const AboutCard = ({ img, text, delay = 0 }) => (
  <motion.div
    className="bg-white rounded-lg shadow-lg p-6 text-center max-w-xs w-full"
    initial={{ opacity: 0, y: 40 }}
    whileInView={{ opacity: 1, y: 0 }}
    viewport={{ once: true }}
    transition={{ duration: 0.8, delay }}
  >
    <img src={img} alt="About" className="w-32 h-32 mx-auto rounded-full object-cover mb-4" />
    <p className="text-gray-700">{text}</p>
  </motion.div>
);
const aboutCards = [
  {
    img: "code.jpg",
    text: "I'm a frontend developer focused on responsive and interactive UI using React.",
  },
  {
    img: "code.jpg",
    text: "I craft elegant, accessible, and performance-optimized interfaces.",
  },
  {
    img: "code.jpg",
    text: "I love designing creative UI/UX with a focus on user delight.",
  },
  {
    img: "code.jpg",
    text: "I build end-to-end web apps from scratch to deployment.",
  },
];

const App = () => {
  return (
    <div className="font-sans">
      {/* Header */}
      <header className="bg-gray-900 text-white p-4 flex justify-between items-center sticky top-0 z-50">
        <h1 className="text-2xl font-bold">My Portfolio</h1>
        
        <nav className="space-x-4">
          <a href="#home" className="hover:text-yellow-400">Home</a>
          <a href="#about" className="hover:text-yellow-400">About Me</a>
          <a href="#contact" className="hover:text-yellow-400">Contact</a>
        </nav>
      </header>


      {/* Hero / Home Section */}
      {/* Hero / Home Section */}
        <section
          id="home"
          className="h-screen bg-black text-white flex flex-col justify-center items-center relative overflow-hidden"
         >
          <motion.div
            className="absolute top-0 left-0 w-full h-full -z-10"
            initial={{ opacity: 0 }}
            animate={{ opacity: 0.5 }}
            transition={{ duration: 1 }}
          >
            <img
              src="/code.jpg"
              alt="Background of code"
              className="w-full h-full object-cover"
              loading="lazy"
            />
          </motion.div>

          <motion.h2
            className="text-4xl md:text-6xl font-bold mb-4 z-10 text-center"
            initial={{ y: -50, opacity: 0 }}
            animate={{ y: 0, opacity: 1 }}
            transition={{ duration: 1 }}
          >
            Welcome to My Portfolio
          </motion.h2>

          <TypeAnimation
            sequence={[
              "I'm a passionate Developer.",
              2000,
              "I'm a Creative Designer.",
              2000,
              "I Build Animated Experiences.",
              2000,
              "I Love Coding.",
              2000,
            ]}
            wrapper="p"
            cursor={true}
            repeat={Infinity}
            className="text-lg md:text-2xl text-gray-300 z-10 text-center"
          />
        </section>

      {/* About Me Section */}
      <section
      id="about"
      className="min-h-screen bg-white text-gray-900 py-16 px-4"
    >
      <motion.div
        initial={{ y: 50, opacity: 0 }}
        whileInView={{ y: 0, opacity: 1 }}
        viewport={{ once: true }}
        transition={{ duration: 1 }}
        className="text-center mb-12"
      >
        <h3 className="text-4xl font-bold mb-4">About Me</h3>
        <p className="text-gray-600 text-lg">What I do and how I do it...</p>
      </motion.div>

      {/* Responsive Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 max-w-7xl mx-auto">
        {aboutCards.map((card, index) => (
          <AboutCard
            key={index}
            img={card.img}
            text={card.text}
            delay={index * 0.2}
          />
        ))}
      </div>
    </section>


      {/* Contact Us Section */}
      <section id="contact" className="min-h-screen bg-gray-100 p-8 flex flex-col justify-center items-center">
        <motion.div
          initial={{ x: 100, opacity: 0 }}
          whileInView={{ x: 0, opacity: 1 }}
          viewport={{ once: true }}
          transition={{ duration: 1 }}
          className="max-w-3xl w-full"
        >
          <h3 className="text-3xl font-bold mb-4 text-center">Contact Me</h3>
          <form className="space-y-4">
            <input
              type="text"
              placeholder="Your Name"
              className="w-full border border-gray-300 p-3 rounded"
            />
            <input
              type="email"
              placeholder="Your Email"
              className="w-full border border-gray-300 p-3 rounded"
            />
            <textarea
              placeholder="Your Message"
              className="w-full border border-gray-300 p-3 rounded h-32"
            ></textarea>
            <button className="bg-gray-900 text-white px-6 py-3 rounded hover:bg-gray-700">
              Send Message
            </button>
          </form>
        </motion.div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-900 text-white p-4 text-center">
        &copy; {new Date().getFullYear()} My Portfolio. All rights reserved.
      </footer>
    </div>
  );
};

export default App;