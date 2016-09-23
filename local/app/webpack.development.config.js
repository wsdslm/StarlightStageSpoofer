var path = require('path');
var webpack = require('webpack');

module.exports = {
    devtool: 'eval',
    entry: './src/index.jsx',
    output: {
        path: path.join(__dirname, '../../dist'),
        filename: 'bundle.js',
        publicPath: '/static/'
    },
    plugins: [
        new webpack.ProvidePlugin({
            _: "lodash",
            $: "jquery",
            jQuery: "jquery",
            "window.jQuery": "jquery"
        })
    ],
    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                loaders: ['babel'],
                include: path.join(__dirname, 'src'),
                exclude: path.join(__dirname, 'node_modules')
            },
            {
                test: /\.json$/,
                loader: 'json'
            },
            {
                test: /\.css$/,
                loaders: ['style', 'css']
            }
        ]
    },
    resolve: {
        root: [
            path.join(__dirname, 'src')
        ]
    }
};
