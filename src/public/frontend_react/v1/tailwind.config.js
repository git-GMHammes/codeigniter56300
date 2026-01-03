/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{jsx,js,ts,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        gold: "#FFD700",
        orange: "#FFA500",
      },
      boxShadow: {
        'white-glow': '0 0 10px 2px #FFFFFF',
        'gold-glow': '0 0 15px 3px #FFD700',
        'header': '0 4px 20px rgba(255, 215, 0, 0.3)',
        'card': '0 10px 30px rgba(255, 215, 0, 0.1)',
        'card-hover': '0 15px 40px rgba(255, 215, 0, 0.3)',
        'logo': '0 8px 25px rgba(0, 0, 0, 0.4)',
      },
    },
  },
  plugins: [],
};