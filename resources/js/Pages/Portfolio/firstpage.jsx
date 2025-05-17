import React, { useState } from "react";
import { motion } from "framer-motion";
import { TypeAnimation } from 'react-type-animation';
import Layout from "@/Layouts/navbar";
import { router } from "@inertiajs/react";

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
   const [form, setForm] = useState({
    name: "",
    email: "",
    message: "",
  });

  const [errors, setErrors] = useState({});
  const [success, setSuccess] = useState(false);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    setErrors({});
    setSuccess(false);

    router.post("/contact", form, {
      onSuccess: () => {
        setSuccess(true);
        setForm({ name: "", email: "", message: "" });
      },
      onError: (err) => {
        setErrors(err);
      },
    });
  };
  return (
    <div className="font-sans">
      <Layout>
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
      className="bg-white text-gray-900 py-16 px-4"
    >
      {/* Section Header */}
      <motion.div
        initial={{ y: 50, opacity: 0 }}
        whileInView={{ y: 0, opacity: 1 }}
        viewport={{ once: true }}
        transition={{ duration: 1 }}
        className="text-center mb-16"
      >
        <h3 className="text-4xl font-bold mb-4">About Me</h3>
        <p className="text-gray-600 text-lg max-w-xl mx-auto">
          Here’s a closer look at my professional focus and passion for web development.
        </p>
      </motion.div>

      {/* Responsive Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10 max-w-[1280px] mx-auto px-2">
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


      {/* Contact Section */}
        <section id="contact" className="py-16 px-4 flex justify-center items-center">
          <motion.div
            initial={{ x: 100, opacity: 0 }}
            whileInView={{ x: 0, opacity: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 1 }}
            className="w-full max-w-3xl p-8 rounded-xl shadow-lg bg-white"
          >
            <h3 className="text-3xl font-bold mb-6 text-center text-gray-800">Contact Me</h3>

            {success && (
              <div className="mb-4 text-green-600 text-center font-semibold">
                Message sent successfully!
              </div>
            )}

            <form className="space-y-6" onSubmit={handleSubmit}>
              <div>
                <input
                  type="text"
                  name="name"
                  value={form.name}
                  onChange={handleChange}
                  placeholder="Your Name"
                  className="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-700"
                />
                {errors.name && <p className="text-red-500 text-sm mt-1">{errors.name}</p>}
              </div>

              <div>
                <input
                  type="email"
                  name="email"
                  value={form.email}
                  onChange={handleChange}
                  placeholder="Your Email"
                  className="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-700"
                />
                {errors.email && <p className="text-red-500 text-sm mt-1">{errors.email}</p>}
              </div>

              <div>
                <textarea
                  name="message"
                  value={form.message}
                  onChange={handleChange}
                  placeholder="Your Message"
                  rows={6}
                  className="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-700"
                />
                {errors.message && <p className="text-red-500 text-sm mt-1">{errors.message}</p>}
              </div>

              <button
                type="submit"
                className="w-full bg-gray-900 text-white py-3 rounded-md hover:bg-gray-800 transition"
              >
                Send Message
              </button>
            </form>
          </motion.div>
        </section>
      </Layout>
    </div>
  );
};

export default App;