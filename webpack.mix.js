const mix = require('laravel-mix');
const assetsPath = 'src';
const distributionPath = 'assets';

mix.setPublicPath(`${distributionPath}`);
mix.sass(`${assetsPath}/styles/forms.scss`, `${distributionPath}/css`).sourceMaps();
mix.sass(`${assetsPath}/styles/filters.scss`, `${distributionPath}/css`).sourceMaps();
mix.js(`${assetsPath}/scripts/forms.js`, `${distributionPath}/js`);
mix.js(`${assetsPath}/scripts/filters.js`, `${distributionPath}/js`);
mix.js(`${assetsPath}/scripts/lemonlight-page-pillar-content.js`, `${distributionPath}/js`).react();
mix.js(`${assetsPath}/scripts/lemonlight-post-sidebar-options.js`, `${distributionPath}/js`).react();

mix.minify([
  `${distributionPath}/css/forms.css`,
  `${distributionPath}/css/filters.css`,
  `${distributionPath}/js/forms.js`,
  `${distributionPath}/js/filters.js`,
  `${distributionPath}/js/lemonlight-page-pillar-content.js`,
  `${distributionPath}/js/gutenberg/lemonlight-page-pillar-content-metafield.js`,
]);
