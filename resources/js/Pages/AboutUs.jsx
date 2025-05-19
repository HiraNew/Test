import React from 'react';
import Layout from "@/Layouts/navbar";

const AboutUs = () => {
  return (
    <Layout>
    <div className="min-h-screen bg-white text-gray-800 py-20 px-6">
      <div className="max-w-4xl mx-auto text-center">
        <h1 className="text-4xl font-bold text-indigo-700 mb-6">About Us</h1>
        <p className="text-lg text-gray-600 mb-4">
          We are passionate about creating modern software solutions and providing value through development and education.
        </p>
        <p className="text-lg text-gray-600">
          Whether you're a business needing a dynamic web app or a learner seeking demo classes, we've got you covered.
        </p>
      </div>
    </div>
    </Layout>
  );
};

export default AboutUs;
