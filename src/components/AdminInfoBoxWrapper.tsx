import React, { ReactNode } from 'react'
import { Box } from 'rebass/styled-components'
import styled from 'styled-components'
import { Link } from 'react-router-dom'

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
    width: fit-content;
`

export const AdminHorizontalButtons = styled(Box)`
    display: grid;
    grid-template-columns: repeat(auto-fill, 200px);
    grid-template-rows: auto;
    column-gap: 8px;
    row-gap: 8px;
`

export const AdminVerticalButtonLink = styled(Link)`
    width: fit-content;
`
