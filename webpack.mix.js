const mix = require('laravel-mix');

mix.js('javascript/src/cookieconsent.js', 'javascript/dist/cookieconsent.js')
  .sass('scss/cookieconsent.scss', 'css/cookieconsent.css');
