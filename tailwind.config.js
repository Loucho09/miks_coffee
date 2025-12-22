import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // 🟢 UPDATED: Sophisticated Beige Theme (60-30-10 Rule)
                stone: {
                    // LIGHT MODE: The 60% and 30% Beige connection
                    50: '#F5F2EA',  // 60% - Main Beige Background (Soft & Warm)
                    100: '#EBE6D9', // 30% - Secondary/Card Background
                    200: '#DED7C5', // 10% - Subtle Beige Borders
                    
                    // DARK MODE: The "Connected" Charcoal-Beige
                    950: '#0C0B0A', // 60% - Primary Dark BG (Deep Espresso)
                    900: '#1A1816', // 30% - Secondary Dark BG (Warm Charcoal)
                    
                    // Neutral Steps for Typography and Accents
                    300: '#D1C8B1',
                    400: '#B8AD91',
                    500: '#8F8366',
                    600: '#736852',
                    700: '#574F3E',
                    800: '#3B352A',
                    1000: '#FF0000', // 🔴 Custom Red Preserved
                },
                
                amber: {
                    400: '#FBBF24',
                    500: '#F59E0B', // 🟠 Primary 10% Accent
                    600: '#D97706',
                    700: '#B45309',
                    1000: '#F59E0B',
                },

                'coffee': {
                    100: '#F5E6E0',
                    600: '#8D5F46',
                    800: '#4B2C20',
                    900: '#2C1810',
                },

                'brand': {
                    orange: '#F59E0B',
                },

                'dashboard': {
                    1000: '#FF0000',
                },
            },
            boxShadow: {
                // 🟢 NEW FEATURE: Beige Depth
                // Soft shadow tuned specifically for the #F5F2EA background
                'beige': '0 20px 40px -15px rgba(143, 131, 102, 0.2)',
                'connected': '0 25px 60px -15px rgba(0, 0, 0, 0.7)',
            },
        },
    },
    plugins: [forms],
};