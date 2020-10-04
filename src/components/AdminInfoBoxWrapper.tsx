import React, { ReactNode } from 'react'
import { Box } from 'rebass/styled-components'
import styled from 'styled-components'

export function AdminInfoBoxWrapper({ children }: { children: ReactNode }) {
    return <Wrapper mt={4}>{children}</Wrapper>
}

const Wrapper = styled(Box)`
    display: grid;
    grid-auto-flow: row;
    grid-row-gap: 24px;
`

export const AdminVerticalButtons = styled(Box)`
    display: grid;
    grid-auto-flow: row;
    row-gap: 8px;
`
