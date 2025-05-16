import React from "react";
import { motion } from "framer-motion";
import { TypeAnimation } from 'react-type-animation';

const App = () => {
  return (
    <div className="font-sans">
      {/* Header */}
      <header className="bg-gray-900 text-white p-4 flex justify-between items-center">
        <h1 className="text-2xl font-bold">My Portfolio</h1>
        <nav className="space-x-4">
          <a href="#home" className="hover:text-yellow-400">Home</a>
          <a href="#about" className="hover:text-yellow-400">About Me</a>
          <a href="#contact" className="hover:text-yellow-400">Contact</a>
        </nav>
      </header>

      {/* Hero / Home Section */}
      <section id="home" className="h-screen bg-black text-white flex flex-col justify-center items-center">
        <motion.video
          autoPlay
          loop
          muted
          className="w-full h-full object-cover absolute top-0 left-0 opacity-50 -z-10"
          initial={{ opacity: 0 }}
          animate={{ opacity: 0.5 }}
          transition={{ duration: 1 }}
        >
          <video autoPlay loop muted playsInline>
  <source src="http://localhost:8000/01320006.MP4" type="video/mp4" />
</video>




        </motion.video>
        <motion.h2
            className="text-4xl md:text-6xl font-bold mb-4"
            initial={{ y: -50, opacity: 0 }}
            animate={{ y: 0, opacity: 1 }}
            transition={{ duration: 1 }}
            >
            Welcome to My Portfolio
            </motion.h2>
            <TypeAnimation
            sequence={[
                'I\'m a passionate Developer.', // First phrase
                2000, // Wait 2 seconds
                'I\'m a Creative Designer.',    // Second phrase
                2000,
                'I Build Animated Experiences.', // Third phrase
                2000,
                'I Love Coding.', // Fourth phrase
                2000,
            ]}
            wrapper="p"
            cursor={true}
            repeat={Infinity}
            className="text-lg md:text-2xl text-gray-300"
            />

      </section>

      {/* About Me Section */}
      <section id="about" className="min-h-screen bg-white text-gray-900 p-8 flex flex-col justify-center items-center">
        <motion.div
          initial={{ x: -100, opacity: 0 }}
          whileInView={{ x: 0, opacity: 1 }}
          viewport={{ once: true }}
          transition={{ duration: 1 }}
          className="max-w-3xl text-center"
        >
          <h3 className="text-3xl font-bold mb-4">About Me</h3>
          <p className="text-lg leading-relaxed">
            Hello! I'm a creative web developer with a knack for building responsive and
            engaging websites. My expertise includes React, UI/UX design, and creating animated user
            experiences that captivate visitors.
          </p>
        </motion.div>
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