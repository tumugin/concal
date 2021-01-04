module.exports = {
    roots: ['<rootDir>/src'],
    modulePaths: ['<rootDir>/node_modules', '<rootDir>', '<rootDir>/src/'],
    moduleFileExtensions: ['js', 'ts', 'tsx', 'json'],
    transform: {
        '^.+\\.tsx?$': 'ts-jest',
        '^.+\\.js$': 'babel-jest',
    },
    transformIgnorePatterns: ['/node_modules/(?!(@storybook/.*\\.vue$))'],
}
