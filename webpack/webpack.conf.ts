import baseConfig from './webpack.base.conf'
import * as webpack from 'webpack'
import * as path from 'path'
import WebpackBar from 'webpackbar'
import HtmlWebpackPlugin from 'html-webpack-plugin'

export default function config(
    env: { [key: string]: string | undefined },
    argv: { [key: string]: string | undefined }
) {
    const base = baseConfig(env, argv)
    const config: webpack.Configuration = {
        ...base,
        entry: {
            app: path.resolve('src/main.tsx'),
        },
        plugins: [
            ...(base.plugins || []),
            (new WebpackBar({
                color: '#7adad6',
                profile: true,
                name: 'frontend client',
            }) as unknown) as webpack.Plugin,
            new HtmlWebpackPlugin({
                filename: 'index.html',
                template: path.resolve('resources/html/template.html'),
                inject: true,
            }),
        ],
    }
    return config
}
