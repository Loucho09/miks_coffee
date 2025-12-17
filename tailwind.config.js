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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // ADD THIS SECTION:
            colors: {
                'coffee': {
                    100: '#F5E6E0', // Light Latte
                    600: '#8D5F46', // Milk Coffee
                    800: '#4B2C20', // Espresso (Dark Brown)
                    900: '#2C1810', // Dark Roast
                },
                'brand': {
                    orange: '#F59E0B', // Warm Amber (Matches your menu)
                }
            }
        },
    },

    plugins: [forms],
};
