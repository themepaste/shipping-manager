const path = require('path');

module.exports = (env, argv) => {
    const isProduction = (argv.mode || 'production') === 'production';

    return {
        // Default to production: the shipped bundle used to be a ~1.7 MB
        // development build whose eval()-wrapped modules are both slow and a
        // problem for WordPress.org review and strict CSP setups.
        mode: argv.mode || 'production',
        // No eval-based source maps in the distributed file.
        devtool: isProduction ? false : 'source-map',
        entry: './spa/admin/Main.jsx',
        output: {
            path: path.resolve(__dirname, './assets/admin/dist'),
            filename: 'bundle.js',
            publicPath: '',
        },
        module: {
            rules: [
                {
                    test: /\.jsx?$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: [
                                '@babel/preset-react',
                                '@babel/preset-env',
                            ],
                        },
                    },
                },
                {
                    test: /\.css$/i,
                    use: ['style-loader', 'css-loader'],
                },
            ],
        },
        resolve: {
            extensions: ['.js', '.jsx'],
        },
    };
};
