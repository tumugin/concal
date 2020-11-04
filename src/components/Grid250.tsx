import styled from 'styled-components'
import { Box } from 'rebass/styled-components'

export const Grid250 = styled(Box)`
    display: grid;
    grid-gap: ${({ theme }) => theme.space[3]}px;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
`
