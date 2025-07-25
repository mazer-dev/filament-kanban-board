const preset = require('./vendor/filament/filament/tailwind.config.preset')

module.exports = {
    presets: [preset],
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./src/**/*.php",
    ],
    theme: {
        extend: {
            animation: {
                'pulse-twice': 'pulse 1s cubic-bezier(0, 0, 0.2, 1) 2',
            }
        }
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
    corePlugins: {
        preflight: false,
    },
}
