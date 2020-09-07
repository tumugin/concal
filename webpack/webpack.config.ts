import baseConfig from './webpack.base.config'
import * as webpack from 'webpack'
import WebpackBar from 'webpackbar'
import ManifestPlugin from 'webpack-manifest-plugin'

export default function config(
    env: { [key: string]: string | undefined },
    argv: { [key: string]: string | undefined }
) {
    const base = baseConfig(env, argv)
    const config: webpack.Configuration = {
        ...base,
        plugins: [
            ...(base.plugins || []),
            new WebpackBar({
                color: '#7adad6',
                profile: true,
                name: 'frontend client',
            }),
            new ManifestPlugin(),
        ],
    }
    return config
}
