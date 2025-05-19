import React, { useState } from 'react';
import { motion } from 'framer-motion';
import Layout from '@/Layouts/navbar'; // Adjust if your layout path is different
import { router } from '@inertiajs/react';

const ContactUs = () => {
  const [form, setForm] = useState({ name: '', email: '', mobile: '', message: '' });
  const [errors, setErrors] = useState({});
  const [success, setSuccess] = useState(false);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
    setErrors({ ...errors, [e.target.name]: null });
  };

  const handleSubmit = async(e) => {
    e.preventDefault();

    // Simulated validation
    const newErrors = {};
    if (!form.name) newErrors.name = 'Name is required';
    if (!form.email) newErrors.email = 'Email is required';
    if (!form.mobile || form.mobile.length !== 10) newErrors.mobile = 'Mobile must be 10 digits';
    if (!form.message) newErrors.message = 'Message is required';

    if (Object.keys(newErrors).length) {
      setErrors(newErrors);
      return;
    }

    // Simulate successful submission
    try {
    const response = router.post("/contact-us", form, {
      onSuccess: () => {
        setSuccess(true);
        setForm({ name: "", email: "", mobile: "", message: "" });
      },
      onError: (err) => {
        setErrors(err);
      },
    });

    if (response) {
      setSuccess(true);
      setForm({ name: '', email: '', mobile: '', message: '' });
      setErrors({});
    } else {
      const data = await response.json();
      setErrors(data.errors || {});
    }
  } catch (error) {
    console.error('Error submitting form:', error);
  }
  };

  return (
    <Layout>
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
                type="text"
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
  );
};

export default ContactUs;
