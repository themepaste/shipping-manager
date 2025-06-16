const path = require('path');

module.exports = (env, argv) => {
    return {
        mode: argv.mode || 'development',
        entry: './spa/admin/Main.jsx',
        output: {
            path: path.resolve(__dirname, './assets/admin/dist'),
            filename: 'bundle.js',
            publicPath: '/',
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
            ],
        },
        resolve: {
            extensions: ['.js', '.jsx'],
        },
    };
};
