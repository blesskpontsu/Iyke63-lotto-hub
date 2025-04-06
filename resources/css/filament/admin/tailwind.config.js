import preset from "../../../../vendor/filament/filament/tailwind.config.preset";

export default {
    presets: [preset],
    content: [
        "./app/Filament/**/*.php",
        "./resources/views/filament/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
    ],

    theme: {
        extend: {
            colors: {
                // "green-400": "#4ade80", // Custom green
                // "red-500": "#ef4444", // Custom red
                // "yellow-400": "#ffd300",
            },
        },
    },
};
