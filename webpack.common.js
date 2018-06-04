const path = require('path');
const webpack = require('webpack');

module.exports = {
  entry: {
      cookieconsent: './javascript/src/cookieconsent.js',
      cookieconsentpopup: './javascript/src/cookieconsentpopup.js',
  },
  plugins: [
    new webpack.ProvidePlugin({
      $: "jquery",
      jQuery: "jquery"
    })
  ],
  module: {
    rules: [
      {
        test: /\.js$/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ['@babel/preset-env']
          }
        }
      }
    ]
  },
  output: {
    path: path.resolve(__dirname, 'javascript/dist'),
    filename: '[name].js'
  },
};
