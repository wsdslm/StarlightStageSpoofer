var path = require('path');
var webpack = require('webpack');

module.exports = {
    devtool: 'cheap-source-map',
    entry: './src/index.jsx',
    output: {
        path: path.join(__dirname, '../../dist'),
        filename: 'bundle.js'
    },
    plugins: [
        new webpack.ProvidePlugin({
            _: "lodash",
            $: "jquery",
            jQuery: "jquery",
            "window.jQuery": "jquery"
        }),
        new webpack.NoErrorsPlugin(),
        new webpack.DefinePlugin({
            'process.env.NODE_ENV': '"production"'
        }),
        new webpack.optimize.OccurrenceOrderPlugin(),
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false
            }
        })
    ],
    module: {
        loaders: [
            {
                test: /\.js(x)?$/,
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
