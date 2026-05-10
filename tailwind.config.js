/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/views/**/*.blade.php", "./resources/js/**/*.js"],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "neon-cyan": "#00D9FF",
                "neon-purple": "#B537F2",
                "neon-blue": "#0066FF",
                "dark-bg": "#0F0F1E",
                "dark-card": "#1A1A2E",
                "dark-border": "#16213E",
                "glass-light": "rgba(255, 255, 255, 0.05)",
                "glass-medium": "rgba(255, 255, 255, 0.08)",
                "glass-dark": "rgba(255, 255, 255, 0.03)",
            },
            backdropBlur: {
                xs: "2px",
                sm: "4px",
                md: "8px",
                lg: "12px",
                xl: "16px",
                "2xl": "24px",
            },
            boxShadow: {
                "glow-cyan": "0 0 20px rgba(0, 217, 255, 0.3)",
                "glow-cyan-sm": "0 0 10px rgba(0, 217, 255, 0.2)",
                "glow-cyan-lg": "0 0 30px rgba(0, 217, 255, 0.4)",
                "glow-purple": "0 0 20px rgba(181, 55, 242, 0.3)",
                "glow-blue": "0 0 20px rgba(0, 102, 255, 0.3)",
                glass: "0 8px 32px rgba(0, 0, 0, 0.3)",
                "glass-hover": "0 12px 40px rgba(0, 217, 255, 0.15)",
            },
            animation: {
                "pulse-glow": "pulse-glow 2s ease-in-out infinite",
                float: "float 6s ease-in-out infinite",
                "glow-fade": "glow-fade 2s ease-in-out infinite",
                "slide-in": "slide-in 0.3s ease-out",
                "fade-in": "fade-in 0.5s ease-out",
                "scan-pulse":
                    "scan-pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite",
            },
            keyframes: {
                "pulse-glow": {
                    "0%, 100%": {
                        opacity: "1",
                        boxShadow: "0 0 20px rgba(0, 217, 255, 0.3)",
                    },
                    "50%": {
                        opacity: "0.8",
                        boxShadow: "0 0 30px rgba(0, 217, 255, 0.5)",
                    },
                },
                float: {
                    "0%, 100%": { transform: "translateY(0px)" },
                    "50%": { transform: "translateY(-10px)" },
                },
                "glow-fade": {
                    "0%, 100%": { opacity: "0.5" },
                    "50%": { opacity: "1" },
                },
                "slide-in": {
                    from: { transform: "translateX(-20px)", opacity: "0" },
                    to: { transform: "translateX(0)", opacity: "1" },
                },
                "fade-in": {
                    from: { opacity: "0" },
                    to: { opacity: "1" },
                },
                "scan-pulse": {
                    "0%, 100%": { opacity: "1" },
                    "50%": { opacity: "0.5" },
                },
            },
        },
    },
    plugins: [],
};
