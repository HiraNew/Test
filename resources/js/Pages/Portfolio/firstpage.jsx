import React, { useEffect, useRef } from "react";
import { motion } from "framer-motion";
import { TypeAnimation } from "react-type-animation";
import Layout from "@/Layouts/navbar";
import { router } from "@inertiajs/react";
import { FaUsers, FaCode, FaUserGraduate, FaBriefcase, FaStore, FaChalkboardTeacher } from "react-icons/fa";


// === Background Carousel Component ===

const stats = [
  {
    title: "Total Happy Customers",
    value: "1,200+",
    icon: <FaUsers className="text-blue-600 text-4xl" />,
    delay: 0,
  },
  {
    title: "Total Software Developed",
    value: "350+",
    icon: <FaCode className="text-green-600 text-4xl" />,
    delay: 0.1,
  },
  {
    title: "Total Trained Students",
    value: "900+",
    icon: <FaUserGraduate className="text-purple-600 text-4xl" />,
    delay: 0.2,
  },
  {
    title: "Total Students Got Jobs",
    value: "500+",
    icon: <FaBriefcase className="text-yellow-600 text-4xl" />,
    delay: 0.3,
  },
  {
    title: "Total Customers Connected to Shop",
    value: "2,000+",
    icon: <FaStore className="text-pink-600 text-4xl" />,
    delay: 0.4,
  },
  {
    title: "Total Free Demo's Student's Attended",
    value: "300+",
    icon: <FaChalkboardTeacher className="text-red-600 text-4xl" />,
    delay: 0.5,
  },
];



const BackgroundCarousel = () => {
  const scrollRef = useRef(null);

  useEffect(() => {
    const container = scrollRef.current;
    let scrollAmount = 0;

    const scrollSpeed = 5.0; // smoothness
    const scroll = () => {
      if (!container) return;
      scrollAmount += scrollSpeed;
      container.scrollLeft = scrollAmount;

      // Looping back to start
      if (scrollAmount >= container.scrollWidth / 2) {
        scrollAmount = 0;
      }

      requestAnimationFrame(scroll);
    };

    scroll();
  }, []);

  const images = ["/code.jpg", "/learn.webp", "/general.jpg"];

  return (
    <div
      ref={scrollRef}
      className="absolute inset-0 overflow-hidden whitespace-nowrap z-10"
      style={{ maskImage: "linear-gradient(to right, transparent, black 10%, black 90%, transparent)" }}
    >
      <div className="flex w-max h-full">
        {[...images, ...images].map((src, index) => (
          <img
            key={index}
            src={src}
            alt={`bg-${index}`}
            className="h-screen w-screen object-cover shrink-0"
          />
        ))}
      </div>
      <div className="absolute inset-0 bg-black bg-opacity-60" />
    </div>
  );
};

// === App Component ===
const App = () => {
  const [form, setForm] = React.useState({
    name: "",
    email: "",
    mobile: "",
    message: "",
  });

  const [errors, setErrors] = React.useState({});
  const [success, setSuccess] = React.useState(false);

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
        {/* === HOME SECTION WITH BACKGROUND CAROUSEL === */}
        <section
          id="home"
          className="relative w-full h-screen text-white overflow-hidden flex items-center justify-center"
        >
          <BackgroundCarousel />

          {/* Foreground Text Overlay */}
          <div className="relative z-10 text-center px-4 text-green-800">
            <motion.h2
              className="text-4xl md:text-6xl font-extrabold mb-6"
              initial={{ y: -40, opacity: 0 }}
              animate={{ y: 0, opacity: 1 }}
              transition={{ duration: 1 }}
            >
              Welcome to Our DLS
            </motion.h2>

            <TypeAnimation
              sequence={[
                "Passionate Software Developer.",
                2000,
                "Providing job oriented development course.",
                2000,
                "Providing free demo class.",
                2000,
                "Welcome you to shopping on our store or online.",
                2000,
                "Providing Software Solution and Ideas.",
                2000,
              ]}
              wrapper="p"
              cursor={true}
              repeat={Infinity}
              className="text-3xl md:text-3xl text-blue-800 font-bold"
            />
              <div className="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            {/* Card 1: Shop Now */}
            <a href="http://127.0.0.1:8001/" target="_blank">
            <div className="bg-white bg-opacity-90 rounded-lg shadow-md p-6 flex flex-col items-center hover:scale-105 transition-transform">
            {/* Fresh Shopping Cart Icon */}
            <svg xmlns="http://www.w3.org/2000/svg" className="h-12 w-12 text-green-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.25 3h1.386a.75.75 0 01.728.576l.347 1.388M6 6h14.25l-1.5 6H7.5m0 0l-1.5-6m1.5 6L6.75 17.25m12 0a1.5 1.5 0 11-3 0m-6 0a1.5 1.5 0 11-3 0" />
            </svg>
            <h4 className="text-xl font-bold text-green-800">Shop Your Faviroute Products Now</h4>
          </div>
            </a>



            {/* Card 2: Learn Now */}
            <div className="bg-white bg-opacity-90 rounded-lg shadow-md p-6 flex flex-col items-center hover:scale-105 transition-transform">
              <svg xmlns="http://www.w3.org/2000/svg" className="h-12 w-12 text-blue-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 14l9-5-9-5-9 5 9 5z" />
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 14l6.16-3.422A12.044 12.044 0 0118 10.535V17a2 2 0 01-2 2H8a2 2 0 01-2-2v-6.465c.61.148 1.224.326 1.84.543L12 14z" />
              </svg>
              <h4 className="text-xl font-bold text-blue-800">Learn Job Oriented Development Course Now</h4>
            </div>

            {/* Card 3: Get Software Ideas Now */}
            <div className="bg-white bg-opacity-90 rounded-lg shadow-md p-6 flex flex-col items-center hover:scale-105 transition-transform">
              <svg xmlns="http://www.w3.org/2000/svg" className="h-12 w-12 text-purple-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
              <h4 className="text-xl font-bold text-purple-800 text-center">Develop Software & Get Your Shop Online Today Now</h4>
            </div>
          </div>

          </div>
        </section>
        <section id="about" className="py-16 px-4 bg-gradient-to-r from-gray-100 to-gray-200">
      <div className="max-w-7xl mx-auto text-center mb-12">
        <h2 className="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Our Achievements</h2>
        <p className="text-lg text-gray-600">We take pride in delivering value to learners and clients.</p>
      </div>

      <div className="grid gap-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 max-w-6xl mx-auto">
        {stats.map((stat, index) => (
          <motion.div
            key={index}
            initial={{ opacity: 0, y: 40 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: stat.delay }}
            className="bg-white rounded-xl shadow-md p-6 flex flex-col items-center hover:shadow-xl transition"
          >
            <div className="mb-4">{stat.icon}</div>
            <h3 className="text-2xl font-bold text-gray-900">{stat.value}</h3>
            <p className="text-gray-600 mt-2 text-center">{stat.title}</p>
          </motion.div>
        ))}
      </div>
    </section>

        {/* === Rest of your site content here... === */}
        {/* ABOUT / CONTACT SECTIONS — same as before */}
        
      </Layout>
    </div>
  );
};

export default App;
