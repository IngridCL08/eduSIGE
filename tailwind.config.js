/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                // Paleta principal eduSIGE — Violeta #5E2DEE
                navy: {
                    50:  '#f0ebff',
                    100: '#e2d5fe',
                    200: '#c5aafd',
                    300: '#a47dfc',
                    400: '#8350fb',
                    500: '#7234f5',  // links / focus rings
                    600: '#5E2DEE',  // color base de la marca
                    700: '#5022d8',  // hover botones
                    800: '#4219bf',  // botones primarios
                    900: '#311590',
                    950: '#1e0b6a',  // sidebar oscuro (base gradiente)
                },
                carbon: {
                    50:  '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                    950: '#0A0A0A',  // negro principal / texto
                },
                // Estados
                success:  '#16a34a',
                warning:  '#d97706',
                danger:   '#dc2626',
                info:     '#0284c7',
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
            boxShadow: {
                card: '0 1px 3px 0 rgba(0,0,0,0.1), 0 1px 2px -1px rgba(0,0,0,0.1)',
                'card-hover': '0 4px 6px -1px rgba(0,0,0,0.15), 0 2px 4px -2px rgba(0,0,0,0.1)',
            },
            animation: {
                'fade-in': 'fadeIn 0.2s ease-in-out',
                'slide-in': 'slideIn 0.3s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%':   { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideIn: {
                    '0%':   { transform: 'translateY(-8px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)',    opacity: '1' },
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
