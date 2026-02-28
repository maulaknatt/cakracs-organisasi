/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.ts",
    "./resources/**/*.vue",
    "./resources/**/*.html",
    "./index.html",
    "./storage/framework/views/**/*.php",
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#3B82F6', // Blue
        secondary: '#1F2937', // Dark Grey for secondary actions
        accent: '#3B82F6', // unified with primary
        soft: '#F3F4F6',
        dark: '#0B1220', // Main Dark BG
        'dark-sidebar': '#0F172A',
        'dark-card': '#111827',
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      borderRadius: {
        xl: '1.25rem',
      },
      boxShadow: {
        card: '0 2px 12px 0 rgba(79,140,255,0.08)',
      },
    },
  },
  plugins: [],
};
