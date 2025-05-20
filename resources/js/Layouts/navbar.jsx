// src/components/Layout.jsx
import React from "react";
import {FaLinkedin, FaGithub, FaTwitter, FaInstagram, FaYoutube, FaFacebook } from "react-icons/fa";




const Layout = ({ children }) => {
  const currentUrl = window.location.origin;
console.log("URL:",currentUrl);
  const baserUrl = "http://127.0.0.1:8000/";
  if(baserUrl)
  {
    console.log(baserUrl);
    
  }
  return (
    <div className="font-sans min-h-screen flex flex-col bg-gray-100">
      {/* Header */}
      <header className="bg-gray-900 text-white p-4 flex justify-between items-center sticky top-0 z-50 shadow-md">
        <h1 className="text-2xl font-bold">DLS</h1>
        <nav className="space-x-4">
        {currentUrl != baserUrl ? (
  <>
    <a href="/" className="hover:text-yellow-400">Home</a>
    <a href="/about-us" className="hover:text-yellow-400">About</a>
    <a href="/contact" className="hover:text-yellow-400">Contact</a>
  </>
) : (
  <a href="#home" className="hover:text-yellow-400">LAL</a>
)}

          <a href="/services" className="hover:text-yellow-400">Our Services</a>
        </nav>
      </header>

      {/* Page Content */}
      <main className="flex-1">{children}</main>

      {/* Footer */}
      <footer className="bg-gray-900 text-white px-4 py-10">
  <div className="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 text-sm">
    
    {/* Company/Brand Info */}
    <div>
      <h4 className="text-lg font-semibold mb-4">Our DLS</h4>
      <p className="text-gray-400">
        Showcasing my journey, skills, and work in web development.
      </p>
    </div>

    {/* Navigation Links */}
    <div>
      <h4 className="text-lg font-semibold mb-4">Navigation</h4>
      <ul className="space-y-2">
        <li><a href="/" className="hover:text-yellow-400">Home</a></li>
        <li><a href="/about-us" className="hover:text-yellow-400">About</a></li>
        <li><a href="#projects" className="hover:text-yellow-400">Projects</a></li>
        <li><a href="/contact" className="hover:text-yellow-400">Contact</a></li>
      </ul>
    </div>

    {/* Useful Links */}
    <div>
      <h4 className="text-lg font-semibold mb-4">Resources</h4>
      <ul className="space-y-2">
        <li><a href="/resume.pdf" target="_blank" rel="noopener noreferrer" className="hover:text-yellow-400">Resume</a></li>
        <li><a href="/blog" className="hover:text-yellow-400">Blog</a></li>
        <li><a href="/terms" className="hover:text-yellow-400">Terms of Service</a></li>
        <li><a href="/services" className="hover:text-yellow-400">Top Services</a></li>
        {/* <li><a href="/privacy-policy" className="hover:text-yellow-400"></a></li> */}
      </ul>
    </div>

    {/* Social Media Links */}
    <div>
  <h4 className="text-lg font-semibold mb-4">Follow Me</h4>
  <ul className="space-y-2">
    <li>
      <a
        href="https://linkedin.com/in/yourprofile"
        target="_blank"
        rel="noopener noreferrer"
        className="flex items-center space-x-2 hover:text-yellow-400"
      >
        <FaLinkedin />
        <span>LinkedIn</span>
      </a>
    </li>
    <li>
      <a
        href="https://github.com/yourusername"
        target="_blank"
        rel="noopener noreferrer"
        className="flex items-center space-x-2 hover:text-yellow-400"
      >
        <FaGithub />
        <span>GitHub</span>
      </a>
    </li>
    <li>
      <a
        href="https://twitter.com/yourhandle"
        target="_blank"
        rel="noopener noreferrer"
        className="flex items-center space-x-2 hover:text-yellow-400"
      >
        <FaTwitter />
        <span>Twitter</span>
      </a>
    </li>
    <li>
      <a
        href="https://instagram.com/yourhandle"
        target="_blank"
        rel="noopener noreferrer"
        className="flex items-center space-x-2 hover:text-yellow-400"
      >
        <FaInstagram />
        <span>Instagram</span>
      </a>
    </li>
    <li>
      <a
        href="https://youtube.com/@yourchannel"
        target="_blank"
        rel="noopener noreferrer"
        className="flex items-center space-x-2 hover:text-yellow-400"
      >
        <FaYoutube />
        <span>YouTube</span>
      </a>
    </li>
    <li>
      <a
        href="https://facebook.com/yourpage"
        target="_blank"
        rel="noopener noreferrer"
        className="flex items-center space-x-2 hover:text-yellow-400"
      >
        <FaFacebook />
        <span>Facebook</span>
      </a>
    </li>
  </ul>
</div>



  </div>

  {/* Footer Bottom */}
  <div className="mt-10 border-t border-gray-700 pt-6 text-center text-xs text-gray-500">
    &copy; {new Date().getFullYear()} DLS. All rights reserved.
  </div>
</footer>

    </div>
  );
};

export default Layout;
