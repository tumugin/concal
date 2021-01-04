import custom from '../webpack/webpack.storybook'
import webpack from 'webpack'

export default {
    stories: ['../src/**/*.stories.*'],
    addons: ['@storybook/addon-essentials', '@storybook/addon-knobs'],
    webpackFinal: (config: webpack.Configuration): webpack.Configuration => {
        const customConfig = custom({}, { mode: 'development' })
        return {
            ...config,
            resolve: {
                ...config.resolve,
                modules: [...(config.resolve?.modules ?? []), ...(customConfig.resolve?.modules ?? [])],
            },
            module: { ...config.module, rules: customConfig.module?.rules },
            plugins: [...(config.plugins ?? []), ...(customConfig.plugins ?? [])],
        }
    },
}
