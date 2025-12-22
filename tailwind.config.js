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
                stone: {
                    50: '#FDFBF7', 
                    100: '#F7F3F0',
                    200: '#E9E2DB',
                    300: '#D6C9BE',
                    400: '#A39284',
                    500: '#7B6F63',
                    600: '#5F554C',
                    700: '#4A423B',
                    800: '#38322D',
                    900: '#26221F',
                    950: '#0C0A09',
                    1000: '#FF0000', 
                },
                
                amber: {
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
        },
    },
    plugins: [forms],
};