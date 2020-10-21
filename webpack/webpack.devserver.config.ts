import baseConfig from './webpack.base.config'
import * as webpack from 'webpack'
import * as path from 'path'
import WebpackBar from 'webpackbar'
import HtmlWebpackPlugin from 'html-webpack-plugin'
import WebpackDevServer from 'webpack-dev-server'

export default function config(
    env: { [key: string]: string | undefined },
    argv: { [key: string]: string | undefined }
) {
    const base = baseConfig(env, argv)
    const config: webpack.Configuration & { devServer: WebpackDevServer.Configuration } = {
        ...base,
        plugins: [
            ...(base.plugins || []),
            // 型エラーを直す
            (new WebpackBar({
                color: '#7adad6',
                profile: true,
                name: 'frontend dev client',
            }) as unknown) as webpack.WebpackPluginInstance,
            new HtmlWebpackPlugin({
                filename: 'index.html',
                template: path.resolve('resources/html/template.html'),
                inject: true,
            }),
            new webpack.DefinePlugin({
                IS_WEBPACK_DEV_SERVER: true,
            }),
        ],
        devServer: {
            historyApiFallback: true,
        },
    }
    return config
}
