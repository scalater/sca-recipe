const mix = require('laravel-mix');
const assetsPath = 'assets';
const distributionPath = 'assets';

mix.setPublicPath(`${distributionPath}`);
mix.sass(`${assetsPath}/scss/styles.scss`, `${distributionPath}/css`).sourceMaps();
mix.js(`${assetsPath}/js/script.js`, `${distributionPath}/js`);

mix.minify([
  `${distributionPath}/css/styles.css`,
  `${distributionPath}/js/script.js`,
]);
