import React from 'react';
import Layout from "@/Layouts/navbar";
import { FaStoreAlt, FaLaptopCode, FaChalkboardTeacher } from 'react-icons/fa';
import { MdBusinessCenter } from 'react-icons/md';

const AboutUs = () => {
  return (
    <Layout>
      <div className="min-h-screen bg-gradient-to-br from-white to-indigo-50 py-20 px-6">
        <div className="max-w-6xl mx-auto text-center">
          <h1 className="text-5xl font-extrabold text-indigo-700 mb-6">About Us</h1>
          <p className="text-xl text-gray-700 mb-8 leading-relaxed">
            We are passionate about creating modern software solutions and providing value through development and education.
          </p>
          <p className="text-xl text-gray-700 mb-16 leading-relaxed">
            Whether you're a business needing a dynamic web app or a learner seeking demo classes, we've got you covered.
          </p>

          <div className="grid md:grid-cols-3 gap-10 text-left">
            {/* Business Services */}
            <div className="bg-white rounded-xl shadow-md p-8 hover:shadow-xl transition duration-300">
              <MdBusinessCenter className="text-indigo-600 text-5xl mb-4" />
              <h3 className="text-2xl font-bold text-indigo-700 mb-3">Business Solutions</h3>
              <ul className="list-disc list-inside text-gray-700 space-y-2">
                <li>We help businesses go digital with ease.</li>
                <li>Our apps are scalable, secure, and dynamic.</li>
                <li>We turn your vision into functional tech solutions.</li>
                <li>Fast turnaround with high-quality code.</li>
                <li>UI/UX focused to delight your users.</li>
              </ul>
            </div>

            {/* Software Development */}
            <div className="bg-white rounded-xl shadow-md p-8 hover:shadow-xl transition duration-300">
              <FaLaptopCode className="text-indigo-600 text-5xl mb-4" />
              <h3 className="text-2xl font-bold text-indigo-700 mb-3">Modern Development</h3>
              <ul className="list-disc list-inside text-gray-700 space-y-2">
                <li>We build efficient, modern web applications.</li>
                <li>Clean, maintainable code is our standard.</li>
                <li>We use agile methods for flexibility and speed.</li>
                <li>Security and performance are top priorities.</li>
                <li>Always up-to-date with modern tech stacks.</li>
              </ul>
            </div>

            {/* Education & Demo Classes */}
            <div className="bg-white rounded-xl shadow-md p-8 hover:shadow-xl transition duration-300">
              <FaChalkboardTeacher className="text-indigo-600 text-5xl mb-4" />
              <h3 className="text-2xl font-bold text-indigo-700 mb-3">Learning & Education</h3>
              <ul className="list-disc list-inside text-gray-700 space-y-2">
                <li>We offer demo classes for all skill levels.</li>
                <li>Learn through hands-on projects and examples.</li>
                <li>Concepts taught with clarity and depth.</li>
                <li>Designed for curious minds and aspiring developers.</li>
                <li>Real-world coding, not just theory.</li>
              </ul>
            </div>
            <div className="bg-white rounded-xl shadow-md p-8 hover:shadow-xl transition duration-300">
            <FaStoreAlt className="text-green-600 text-5xl mb-4" />
            <h3 className="text-2xl font-bold text-green-700 mb-3">Shop Facilities</h3>
            <ul className="list-disc list-inside text-gray-700 space-y-2">
              <li>Wide range of daily essentials, cosmetics, and general items.</li>
              <li>Available both online and at our physical store.</li>
              <li>Fast delivery and convenient pickup options.</li>
              <li>Affordable pricing with great customer service.</li>
              <li>Exclusive deals and loyalty rewards available.</li>
            </ul>
          </div>
          </div>
        </div>
      </div>
    </Layout>
  );
};

export default AboutUs;
