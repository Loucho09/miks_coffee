import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // 🟢 PATHS: Ensure Tailwind scans all your Blade and PHP files
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    

    // Enable Dark Mode (Class-based or Media-based)
    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                // 🟢 NEW FEATURE: Branding Typography
                // Uses 'Outfit' as the primary font with standard sans fallbacks
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // 🟢 SOPHISTICATED BEIGE & ESPRESSO THEME (60-30-10 Rule)
                stone: {
                    // LIGHT MODE: The 60% and 30% Beige connection
                    50: '#F5F2EA',  // 60% - Main Beige Background (Soft & Warm)
                    100: '#EBE6D9', // 30% - Secondary/Card Background
                    200: '#DED7C5', // 10% - Subtle Beige Borders
                    
                    // NEUTRAL STEPS: Typography and Accents
                    300: '#D1C8B1', //
                    400: '#B8AD91', //
                    500: '#8F8366', //
                    600: '#736852', //
                    700: '#574F3E', //
                    800: '#3B352A', //
                    
                    // DARK MODE: The "Connected" Charcoal-Beige
                    900: '#1A1816', // 30% - Secondary Dark BG (Warm Charcoal)
                    950: '#0C0B0A', // 60% - Primary Dark BG (Deep Espresso)
                    1000: '#FF0000', // Custom Red Preserved
                },
                
                // 🟢 ACCENT COLORS: 10% Branding
                amber: {
                    400: '#FBBF24', //
                    500: '#F59E0B', // Primary 10% Accent (Brand Orange)
                    600: '#D97706', //
                    700: '#B45309', //
                    1000: '#F59E0B', //
                },

                // 🟢 COFFEE SPECIFIC PALETTE
                'coffee': {
                    100: '#F5E6E0', //
                    600: '#8D5F46', //
                    800: '#4B2C20', //
                    900: '#2C1810', //
                },

                // 🟢 LEGACY BRANDING: Compatibility
                'brand': {
                    orange: '#F59E0B', //
                },

                'dashboard': {
                    1000: '#FF0000', //
                },

                'dandelion': {
                    50: '#f19e25',
                    100: '#f2a73a', //
                },
            },
            boxShadow: {
                // 🟢 NEW FEATURE: Custom Depth Effects
                // Soft shadow tuned for light beige backgrounds
                'beige': '0 20px 40px -15px rgba(143, 131, 102, 0.2)', //
                // Heavy, atmospheric shadow for dark theme depth
                'connected': '0 25px 60px -15px rgba(0, 0, 0, 0.7)', //
            },
            letterSpacing: {
                // For that premium high-end look on headers
                'widest': '0.4em',
            },
        },
    },
    
    // 🟢 PLUGINS: Laravel Breeze Defaults
    plugins: [forms], //
};