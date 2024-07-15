
/**
 * WordPress Dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

let shipping_fees = {
    mode: 'development',
    ...defaultConfig,
    ...{
        entry: {
            admin: './src/js/shipping-fees.js',
        },
        output: {
            filename: 'shipping-fees.js',
            path: __dirname + '/assets/build/admin/js',
        }
    }
}

let free_shipping = {
    mode: 'development',
    ...defaultConfig,
    ...{
        entry: {
            admin: './src/js/free-shipping.js',
        },
        output: {
            filename: 'free-shipping.js',
            path: __dirname + '/assets/build/admin/js',
        }
    }
}

let admin_dashboard_style = {
    mode: 'development',
    entry: './src/scss/admin/general.scss',
    output: {
        path: __dirname + '/assets/build/admin/css/',
    },
    module: {
        rules: [
            {
                test: /\.scss$/, // Match .scss files
                use: [
                    MiniCssExtractPlugin.loader, // Extract CSS into separate files
                    'css-loader',                // Translate CSS into CommonJS
                    'sass-loader',               // Compile Sass to CSS
                ],
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: 'general.css', // Output CSS filename
        }),
    ],
}

module.exports = [ admin_dashboard_style, shipping_fees, free_shipping ];
