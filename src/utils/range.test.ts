import { range } from 'utils/range'

describe('range', () => {
    test.each([
        [0, 0, [0]],
        [0, 5, [0, 1, 2, 3, 4, 5]],
        [0, 1, [0, 1]],
    ])('%i to %i', (start, end, expected) => {
        expect(range(start, end)).toStrictEqual(expected)
    })
})
