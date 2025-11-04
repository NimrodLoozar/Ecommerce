import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    server: {
        proxy: {
            // Proxy all requests except Vite's own requests to Laravel
            "^(?!/@vite|/@fs|/@id|/node_modules|/resources).*": {
                target: "http://localhost:8000",
                changeOrigin: true,
            },
        },
    },
});
