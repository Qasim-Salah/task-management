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
            colors: {
                teal: {
                    100: '#e6fffa',
                    500: '#38b2ac',
                    900: '#234e52',
                },
                green: {
                    100: '#f0fff4',
                    500: '#48bb78',
                    900: '#22543d',
                },
                blue: {
                    500: '#4299e1',
                    700: '#2b6cb0',
                },
                red: {
                    500: '#f56565',
                    700: '#c53030',
                },
                gray: {
                    50: '#f9fafb',
                    100: '#f7fafc',
                    200: '#edf2f7',
                    500: '#a0aec0',
                    700: '#4a5568',
                    900: '#1a202c',
                },
                white: '#ffffff',
            },
        },
    },

    plugins: [forms],
};
