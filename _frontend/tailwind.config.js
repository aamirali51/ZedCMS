/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./src/**/*.{js,jsx,ts,tsx}",
        "../content/themes/admin-default/**/*.php"
    ],
    theme: {
        extend: {
            colors: {
                "primary": "#4f46e5",
                "primary-hover": "#4338ca",
                "accent-purple": "#7c3aed",
                "accent-green": "#10b981",
            },
            fontFamily: {
                "display": ["Inter", "sans-serif"],
                "body": ["Inter", "sans-serif"],
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
    ],
}
