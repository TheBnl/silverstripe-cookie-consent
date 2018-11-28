const path = require('path');
const webpack = require('webpack');

module.exports = {
  entry: {
      cookieconsent: './javascript/src/cookieconsent.js'
  },
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
