module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
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
        'accent': '#AD9C68', // bitter-theme-light
        'complement': '#586E75', // thick-theme-light
        'complement-darker': '#465C62', // dark-theme-light
      }
    },
  },
  plugins: [],
}
