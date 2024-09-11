import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            backgroundImage: {
                "login-bg": "url('/storage/img/background_login.jpg')",
            },
            fontFamily: {
                poppins: ["Poppins", "sans-serif"],
            },
            colors: {
                "black-50": "#eaeaea",
                "black-100": "#bdbdbd",
                "black-200": "#9d9d9d",
                "black-300": "#707070",
                "black-400": "#545454",
                "black-500": "#292929",
                "black-600": "#252525",
                "black-700": "#1d1d1d",
                "black-800": "#171717",
                "black-900": "#111111",
                "blue-50": "#e6ecf3",
                "blue-100": "#b1c3db",
                "blue-200": "#8ba6c0",
                "blue-300": "#557eb1",
                "blue-400": "#3565a1",
                "blue-500": "#023e8a",
                "blue-600": "#02387e",
                "blue-700": "#012c62",
                "blue-800": "#01224c",
                "blue-900": "#011a3a",
                "yellow-50": "#fff9e6",
                "yellow-100": "#ffecb0",
                "yellow-200": "#ffe38a",
                "yellow-300": "#ffd754",
                "yellow-400": "#ffcf33",
                "yellow-500": "#ffc300",
                "yellow-600": "#e8b100",
                "yellow-700": "#b58a00",
                "yellow-800": "#8c6b00",
                "yellow-900": "#6b5200",
            },
        },
    },

    plugins: [forms],
};
