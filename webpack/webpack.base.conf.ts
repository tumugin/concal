import * as webpack from 'webpack'
import * as path from 'path'
import MiniCssExtractPlugin from 'mini-css-extract-plugin'

export default function config(
    env: { [key: string]: string | undefined },
    argv: { [key: string]: string | undefined },
    options?: { isSSR: boolean }
) {
    const isProduction = argv.mode === 'production'
    // production => cssを別途出力
    // debug => Clientであればstyle-loaderもしくはMiniCssExtractPlugin.loaderを使用、SSRビルドでは使用できないためnull-loader
    const styleLoader = options?.isSSR ? 'null-loader' : isProduction ? MiniCssExtractPlugin.loader : 'style-loader'
    const sourceMapEnabled = options?.isSSR || !isProduction
    const config: webpack.Configuration = {
        output: {
            filename: 'static/[name].[hash].bundle.js',
            path: path.resolve('public/resources/'),
            chunkFilename: 'static/[name].[hash].bundle.js',
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
                                workers: require('os').cpus().length - 1,
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
                            name: 'img/[name].[hash:7].[ext]',
                        },
                    },
                },
                {
                    test: /\.(mp4|webm|ogg|mp3|wav|flac|aac)(\?.*)?$/,
                    use: {
                        loader: 'url-loader',
                        options: {
                            limit: 10000,
                            name: 'media/[name].[hash:7].[ext]',
                        },
                    },
                },
                {
                    test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
                    use: {
                        loader: 'url-loader',
                        options: {
                            limit: 10000,
                            name: 'fonts/[name].[hash:7].[ext]',
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
        plugins: [new MiniCssExtractPlugin({ filename: 'static/common.[chunkhash].css' })],
    }
    return config
}
