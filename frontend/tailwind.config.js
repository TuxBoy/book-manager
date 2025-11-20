/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./index.html",
        "./src/**/*.{js,ts,jsx,tsx}", // inclut tous les fichiers React
    ],
    theme: {
        extend: {},
    },
    plugins: [require("daisyui")], // active daisyui
    daisyui: {
        themes: ["light", "dark"], // tu peux ajouter d'autres thèmes
    },
};
