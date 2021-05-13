import baseConfig from './webpack.base.config'
import * as webpack from 'webpack'
import WebpackBar from 'webpackbar'
import { WebpackManifestPlugin } from 'webpack-manifest-plugin'
import * as path from 'path'

export default function config(
    env: { [key: string]: string | undefined },
    argv: { [key: string]: string | undefined }
) {
    const base = baseConfig(env, argv, 'admin/assets/css/common.[chunkhash].css')
    const config: webpack.Configuration = {
        ...base,
        entry: {
            app: path.resolve('src/admin/admin-main.tsx'),
        },
        output: {
            filename: 'admin/assets/js/[name].[chunkhash].bundle.js',
            path: path.resolve('public/'),
            chunkFilename: 'admin/assets/js/[name].[chunkhash].bundle.js',
            publicPath: '/',
        },
        plugins: [
            ...(base.plugins || []),
            // FIXME: 型エラーを直す
            new WebpackBar({
                color: '#ffaaaa',
                profile: true,
                name: 'admin frontend',
            }) as unknown as webpack.WebpackPluginInstance,
            // FIXME: 型エラーを直す
            new WebpackManifestPlugin({
                fileName: path.resolve('storage/app/admin-manifest.json'),
            }) as unknown as webpack.WebpackPluginInstance,
        ],
    }
    return config
}
