// resources/js/Pages/ContactUs.jsx

import React, { useState } from 'react';
import { motion } from 'framer-motion';
import Layout from '@/Layouts/navbar';
import { router } from '@inertiajs/react';
import { Player } from '@lottiefiles/react-lottie-player'; // Lottie animation

const ContactUs = () => {
  const [form, setForm] = useState({ name: '', email: '', mobile: '', message: '' });
  const [errors, setErrors] = useState({});
  const [success, setSuccess] = useState(false);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
    setErrors({ ...errors, [e.target.name]: null });
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    const newErrors = {};
    if (!form.name) newErrors.name = 'Name is required';
    if (!form.email) newErrors.email = 'Email is required';
    if (!form.mobile || form.mobile.length !== 10) newErrors.mobile = 'Mobile must be 10 digits';
    if (!form.message) newErrors.message = 'Message is required';

    if (Object.keys(newErrors).length) {
      setErrors(newErrors);
      return;
    }

    router.post("/contact-us", form, {
      onSuccess: () => {
        setSuccess(true);
        setForm({ name: '', email: '', mobile: '', message: '' });
        setErrors({});
      },
      onError: (err) => {
        setErrors(err);
      },
    });
  };

  return (
    <Layout>
      <section id="contact" className="py-16 px-4 bg-gray-50">
        <div className="max-w-6xl mx-auto flex flex-col md:flex-row gap-10 items-center">
          {/* Left - Animation */}
          <motion.div
            initial={{ x: -70, opacity: 0 }}
            whileInView={{ x: 0, opacity: 1 }}
            transition={{ duration: 1 }}
            viewport={{ once: true }}
            className="w-full md:w-1/2 flex justify-center"
          >
            <Player
              autoplay
              loop
              src="https://assets2.lottiefiles.com/packages/lf20_jbrw3hcz.json" // Change to any relevant animation
              style={{ height: '300px', width: '100%' }}
            />
          </motion.div>

          {/* Right - Contact Form */}
          <motion.div
            initial={{ x: 50, opacity: 0 }}
            whileInView={{ x: 0, opacity: 1 }}
            transition={{ duration: 1 }}
            viewport={{ once: true }}
            className="w-full md:w-1/2 bg-white p-8 rounded-xl shadow-lg"
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
                  className="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
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
                  className="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
                {errors.email && <p className="text-red-500 text-sm mt-1">{errors.email}</p>}
              </div>

              <div>
                <input
                  type="text"
                  name="mobile"
                  value={form.mobile}
                  onChange={handleChange}
                  placeholder="Mobile Number"
                  className="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
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
                  className="w-full border border-gray-300 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
                {errors.message && <p className="text-red-500 text-sm mt-1">{errors.message}</p>}
              </div>

              <button
                type="submit"
                className="w-full bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 transition"
              >
                Send Message
              </button>
            </form>
          </motion.div>
        </div>
      </section>
    </Layout>
  );
};

export default ContactUs;
