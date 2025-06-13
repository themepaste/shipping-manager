const path = require('path');

module.exports = (env, argv) => {
    return {
        mode: argv.mode || 'development',
        entry: './spa/admin/Admin.jsx',
        output: {
            filename: 'admin.js',
            path: path.resolve(__dirname, 'assets/admin/build'),
        },
        module: {
            rules: [
                {
                    test: /\.jsx?$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-react'],
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
