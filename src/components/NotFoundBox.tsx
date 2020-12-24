import React from 'react'
import { Box, BoxProps } from 'rebass/styled-components'
import styled from 'styled-components'

export default function NotFoundBox({ children, ...rest }: { children: React.ReactNode } & BoxProps) {
    return (
        <Wrapper sx={{ fontSize: 3 }} {...rest}>
            {children}
        </Wrapper>
    )
}

const Wrapper = styled(Box)`
    display: flex;
    justify-content: center;
`
