import styled from 'styled-components'
import { Box } from 'rebass/styled-components'
import { responsiveMobileMaxWidth } from 'styles/responsive'

export const Grid250 = styled(Box)`
    display: grid;
    grid-gap: ${({ theme }) => theme.space[3]}px;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));

    @media screen and (max-width: ${responsiveMobileMaxWidth}) {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
`
