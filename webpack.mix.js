const mix = require('laravel-mix');
const assetsPath = 'assets';
const distributionPath = 'assets';

mix.setPublicPath(`${distributionPath}`);
mix.sass(`${assetsPath}/scss/sca-recipes.scss`, `${distributionPath}/css`).sourceMaps();

mix.minify([
  `${distributionPath}/css/sca-recipes.css`,
  `${distributionPath}/js/sca-recipes.js`,
]);
