// src/components/Layout.jsx
import React, { useState } from "react";
import {
  FaLinkedin,
  FaGithub,
  FaTwitter,
  FaInstagram,
  FaYoutube,
  FaFacebook,
} from "react-icons/fa";

const Layout = ({ children }) => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  return (
    <div className="font-sans min-h-screen flex flex-col bg-gray-100">
      {/* Header */}
      <header className="bg-gray-900 text-white p-4 flex items-center justify-between sticky top-0 z-50 shadow-md relative">
  {/* Logo */}
  <div className="text-2xl font-bold z-50">DLS</div>

  {/* Center: Join Free Demo (always visible) */}
  <div className="absolute left-1/2 transform -translate-x-1/2 z-40">
    <a
      href="/contact"
      className="hover:text-green-400 bg-red-700 hover:bg-red-600 transition-colors px-4 py-2 rounded font-bold shadow-md"
    >
      Join Free Demo
    </a>
  </div>

  {/* Right: Desktop Nav + Mobile Hamburger */}
  <div className="flex items-center space-x-4 z-50">
    {/* Desktop Nav */}
    <nav className="hidden sm:flex space-x-4">
      <a href="/" className="hover:text-yellow-400">Home</a>
      <a href="/about-us" className="hover:text-yellow-400">About</a>
      <a href="/contact" className="hover:text-yellow-400">Contact</a>
      <a href="/services" className="hover:text-yellow-400">Our Services</a>
    </nav>

    {/* Hamburger Button */}
    <button
      className="sm:hidden flex flex-col justify-center items-center w-8 h-8 group"
      onClick={() => setIsMenuOpen(!isMenuOpen)}
      aria-label="Toggle navigation"
    >
      <div
        className={`w-6 h-0.5 bg-white mb-1 transition-transform duration-300 ${
          isMenuOpen ? "rotate-45 translate-y-1.5" : ""
        }`}
      ></div>
      <div
        className={`w-6 h-0.5 bg-white mb-1 transition-opacity duration-300 ${
          isMenuOpen ? "opacity-0" : "opacity-100"
        }`}
      ></div>
      <div
        className={`w-6 h-0.5 bg-white transition-transform duration-300 ${
          isMenuOpen ? "-rotate-45 -translate-y-1.5" : ""
        }`}
      ></div>
    </button>
  </div>

  {/* Mobile Slide-in Menu */}
  <div
    className={`fixed top-0 right-0 h-full w-64 bg-gray-800 text-white transform transition-transform duration-300 ease-in-out z-40 shadow-lg ${
      isMenuOpen ? "translate-x-0" : "translate-x-full"
    }`}
  >
    <div className="flex flex-col items-center justify-center h-full space-y-6 text-lg">
      <a href="/" onClick={() => setIsMenuOpen(false)} className="hover:text-yellow-400">Home</a>
      <a href="/about-us" onClick={() => setIsMenuOpen(false)} className="hover:text-yellow-400">About</a>
      <a href="/contact" onClick={() => setIsMenuOpen(false)} className="hover:text-yellow-400">Contact</a>
      <a href="/services" onClick={() => setIsMenuOpen(false)} className="hover:text-yellow-400">Our Services</a>
    </div>
  </div>
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
              <li>
                <a
                  href="/resume.pdf"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="hover:text-yellow-400"
                >
                  Resume
                </a>
              </li>
              <li><a href="/blog" className="hover:text-yellow-400">Blog</a></li>
              <li><a href="/terms" className="hover:text-yellow-400">Terms of Service</a></li>
              <li><a href="/services" className="hover:text-yellow-400">Top Services</a></li>
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
