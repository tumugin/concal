import * as webpack from 'webpack'
import * as path from 'path'
import MiniCssExtractPlugin from 'mini-css-extract-plugin'
import * as os from 'os'

export default function config(
    env: { [key: string]: string | undefined },
    argv: { [key: string]: string | undefined }
) {
    const isProduction = argv.mode === 'production'
    const styleLoader = isProduction ? MiniCssExtractPlugin.loader : 'style-loader'
    const sourceMapEnabled = !isProduction
    const config: webpack.Configuration = {
        entry: {
            app: path.resolve('src/main.tsx'),
        },
        output: {
            filename: 'assets/js/[name].[hash].bundle.js',
            path: path.resolve('public/'),
            chunkFilename: 'assets/js/[name].[hash].bundle.js',
            publicPath: '/',
        },
        devtool: sourceMapEnabled && 'source-map',
        optimization: {
            splitChunks: {
                chunks: 'all',
                cacheGroups: {
                    commons: {
                        test: /[\\/]node_modules[\\/]/,
                        name: 'vendor',
                        chunks: 'all',
                    },
                },
            },
            namedModules: true,
            noEmitOnErrors: true,
        },
        resolve: {
            extensions: ['.js', '.json', '.ts', '.tsx'],
            modules: [path.resolve('src/'), 'node_modules'],
            alias: {
                '@': path.resolve('src/'),
            },
        },
        module: {
            rules: [
                {
                    test: /\.(ts|tsx)$/,
                    use: [
                        {
                            loader: 'thread-loader',
                            options: {
                                workers: os.cpus().length - 1,
                            },
                        },
                        {
                            loader: 'babel-loader',
                        },
                        {
                            loader: 'ts-loader',
                            options: {
                                happyPackMode: true,
                                compilerOptions: {
                                    module: 'esnext',
                                },
                            },
                        },
                    ],
                },
                {
                    test: /\.(js|jsx)$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                    },
                },
                {
                    test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
                    use: {
                        loader: 'url-loader',
                        options: {
                            limit: 10000,
                            name: 'assets/img/[name].[hash:7].[ext]',
                        },
                    },
                },
                {
                    test: /\.(mp4|webm|ogg|mp3|wav|flac|aac)(\?.*)?$/,
                    use: {
                        loader: 'url-loader',
                        options: {
                            limit: 10000,
                            name: 'assets/media/[name].[hash:7].[ext]',
                        },
                    },
                },
                {
                    test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
                    use: {
                        loader: 'url-loader',
                        options: {
                            limit: 10000,
                            name: 'assets/fonts/[name].[hash:7].[ext]',
                        },
                    },
                },
                {
                    test: /\.css$/,
                    use: [styleLoader, 'css-loader', 'postcss-loader'],
                },
                {
                    test: /\.styl(us)?$/,
                    use: [styleLoader, 'css-loader', 'postcss-loader', 'stylus-loader'],
                },
                {
                    test: /\.scss$/,
                    use: [styleLoader, 'css-loader', 'postcss-loader', 'sass-loader'],
                },
            ],
        },
        plugins: [new MiniCssExtractPlugin({ filename: 'assets/css/common.[chunkhash].css' })],
    }
    return config
}
