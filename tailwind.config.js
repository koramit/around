module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                'soft-theme-light': '#FDF6E3',
                'alt-theme-light': '#EEE9D5',
                'bitter-theme-light': '#AD9C68',
                'thick-theme-light': '#586E75',
                'dark-theme-light': '#465C62',

                'primary': '#FDF6E3', // soft-theme-light
                'primary-darker': '#EEE9D5', // alt-theme-light
                // https://colourcontrast.cc/ffffff/907326
                // https://coolors.co/contrast-checker/907326-ffffff
                // https://app.contrast-finder.org/result.html?foreground=%23FFFFFF&background=%23907326&algo=Rgb&ratio=7&isBackgroundTested=true
                'accent': '#AD9C68',
                'accent-darker': '#907326', // '#AD9C68', // bitter-theme-light; for hi contrast #907326 < #735406
                'complement': '#586E75', // thick-theme-light
                'complement-darker': '#465C62', // dark-theme-light
            }
        },
    },
    plugins: [],
};
