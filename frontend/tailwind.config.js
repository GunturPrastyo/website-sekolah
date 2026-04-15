/** @type {import('tailwindcss').Config} */
import typography from '@tailwindcss/typography';

export default {
  darkMode: 'class',
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Poppins', 'sans-serif'],
      },
      animation: {
        'scroll-x': 'scroll-x 40s linear infinite',
        'scroll-y': 'scroll-y 60s linear infinite',
      },
      keyframes: {
        'scroll-x': {
          'from': { transform: 'translateX(0)' },
          'to': { transform: 'translateX(-50%)' },
        },
        'scroll-y': {
          'from': { transform: 'translateY(0)' },
          'to': { transform: 'translateY(-50%)' },
        }
      }
    },
  },
  plugins: [typography],
}