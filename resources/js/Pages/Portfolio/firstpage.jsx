import React, { useState } from "react";
import { motion } from "framer-motion";
import { TypeAnimation } from 'react-type-animation';
import Layout from "@/Layouts/navbar";
import { router } from "@inertiajs/react";

// Single animated card 
const AboutCard = ({ img, video, text, delay = 0 }) => (
  <motion.div
    className="bg-white rounded-lg shadow-lg p-6 text-center max-w-xs w-full"
    initial={{ opacity: 0, y: 40 }}
    whileInView={{ opacity: 1, y: 0 }}
    viewport={{ once: true }}
    transition={{ duration: 0.8, delay }}
  >
    <div className="w-full aspect-w-16 aspect-h-9 mb-4 rounded overflow-hidden">
    
  {video ? (
    <iframe
      src={video}
      title="Embedded video"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
      allowFullScreen
      className="w-full h-full"
    ></iframe>
  ) : (
    <img
      src={img}
      alt="About"
      className="w-full h-full object-cover rounded"
    />
  )}
</div>


    <p className="text-gray-700">{text}</p>
  </motion.div>
);

const aboutCards = [
  {
    // img: null,
    video: "https://www.youtube-nocookie.com/embed/Ahsii3YnEuU?si=b5rQD9sjYRXQksoJ",
    text: "Watch our demo class for frontend development.",
    link: "#",
  },
  {
    // img: "code.jpg",
    video: "https://www.youtube.com/embed/Ahsii3YnEuU?si=ctwC9Fmp0n4hom5r",
    text: "I craft elegant, accessible, and performance-optimized.",
    link: "#",
  },
  {
    // img: null,https://player.vimeo.com/video/76979871
    video: "https://www.youtube.com/embed/Ahsii3YnEuU?si=ctwC9Fmp0n4hom5r",
    text: "Explore creative UI/UX design thinking.",
    link: "#",
  },
  {
    // img: "code.jpg",
    video: "https://www.youtube.com/embed/Ahsii3YnEuU?si=ctwC9Fmp0n4hom5r",
    text: "I build end-to-end web apps from scratch to deployment.",
    link: "#",
  },
  {
    // img: null,
    video: "https://www.youtube-nocookie.com/embed/Ahsii3YnEuU?si=b5rQD9sjYRXQksoJ",
    text: "Watch our demo class for frontend development.",
    link: "#",
  },
  {
    // img: "code.jpg",
    video: "https://www.youtube.com/embed/Ahsii3YnEuU?si=ctwC9Fmp0n4hom5r",
    text: "I craft elegant, accessible, and performance-optimized.",
    link: "#",
  },
  {
    // img: null,https://player.vimeo.com/video/76979871
    video: "https://www.youtube.com/embed/Ahsii3YnEuU?si=ctwC9Fmp0n4hom5r",
    text: "Explore creative UI/UX design thinking.",
    link: "#",
  },
  {
    // img: "code.jpg",
    video: "https://www.youtube.com/embed/Ahsii3YnEuU?si=ctwC9Fmp0n4hom5r",
    text: "I build end-to-end web apps from scratch to deployment.",
    link: "#",
  },
];


const App = () => {
   const [form, setForm] = useState({
    name: "",
    email: "",
    mobile: "",
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

    router.post("/contact-us", form, {
      onSuccess: () => {
        setSuccess(true);
        setForm({ name: "", email: "", mobile: "", message: "" });
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
              "I Love YOU.",
              2000,
            ]}
            wrapper="p"
            cursor={true}
            repeat={Infinity}
            className="text-lg md:text-2xl text-gray-300 z-10 text-center"
          />
        </section>

      {/* About Me Section */}
      <section id="about" className="bg-white text-gray-900 py-16 px-4">
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
    <a
      href="/resume.pdf"
      className="inline-block mt-6 text-blue-600 hover:text-blue-800 underline text-lg transition-colors duration-300"
      target="_blank"
      rel="noopener noreferrer"
    >
      View My Resume
    </a>
  </motion.div>

  <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10 max-w-[1280px] mx-auto px-4">
    {aboutCards.map((card, index) => (
      <a
        key={index}
        href={card.link}
        rel="noopener noreferrer"
        className="block transition-transform hover:scale-105"
      >
        <AboutCard
          img={card.img}
          video={card.video}
          text={card.text}
          delay={index * 0.2}
        />
      </a>
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
                <input
                  type="mobile"
                  name="mobile"
                  value={form.mobile}
                  onChange={handleChange}
                  placeholder="Mobile Number"
                  className="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-700"
                />
                {errors.mobile && <p className="text-red-500 text-sm mt-1">{errors.mobile}</p>}
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