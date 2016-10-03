var debug = process.env.NODE_ENV !== "production";
var webpack = require('webpack');

module.exports = {
    context: __dirname + "/node_modules",
    devtool: debug ? "inline-sourcemap" : null,
    resolve: {
        alias: {
            jquery: "jquery/src/jquery"
        }
    },
    entry: "../public/js/scripts.js",
    output: {
        path: __dirname + "/public/js",
        filename: "app2.js"
    },
    plugins: debug ? [] : [
        new webpack.optimize.DedupePlugin(),
        new webpack.optimize.OccurenceOrderPlugin(),
        new webpack.optimize.UglifyJsPlugin({ mangle: false, sourcemap: false }),
    ],
};