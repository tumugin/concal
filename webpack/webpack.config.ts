import baseConfig from './webpack.base.config'
import * as webpack from 'webpack'
import WebpackBar from 'webpackbar'
import * as path from 'path'
import { WebpackManifestPlugin } from 'webpack-manifest-plugin'

export default function config(
    env: { [key: string]: string | undefined },
    argv: { [key: string]: string | undefined }
) {
    const base = baseConfig(env, argv)
    const config: webpack.Configuration = {
        ...base,
        plugins: [
            ...(base.plugins || []),
            // FIXME: 型エラーを直す
            new WebpackBar({
                color: '#7adad6',
                profile: true,
                name: 'frontend client',
            }) as unknown as webpack.WebpackPluginInstance,
            // FIXME: 型エラーを直す
            new WebpackManifestPlugin({
                fileName: path.resolve('storage/app/manifest.json'),
            }) as unknown as webpack.WebpackPluginInstance,
        ],
    }
    return config
}
