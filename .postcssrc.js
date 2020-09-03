module.exports = {
    sourceMap: true,
    plugins: {
        'postcss-import': {},
        'postcss-url': {},
        autoprefixer: {},
        cssnano: { preset: 'default', autoprefixer: false },
    },
}
