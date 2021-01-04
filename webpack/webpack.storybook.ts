import baseConfig from './webpack.base.config'
import * as webpack from 'webpack'
import WebpackBar from 'webpackbar'

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
            (new WebpackBar({
                color: '#7adad6',
                profile: true,
                name: 'storybook',
            }) as unknown) as webpack.WebpackPluginInstance,
        ],
    }
    return config
}
