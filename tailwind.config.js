const preset = require('./vendor/filament/filament/tailwind.config.preset')

module.exports = {
    darkMode: 'class',
    presets: [preset],
    content: ['./resources/**/*.{blade.php,js}'],
    corePlugins: {
        preflight: false,
    },
}
