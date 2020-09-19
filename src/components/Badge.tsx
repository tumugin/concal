import React, { ReactNode } from 'react'
import { Box } from 'rebass/styled-components'
import styled from 'styled-components'

type ColorTypes = 'danger' | 'alert' | 'success'

export function Badge({ type, children }: { type: ColorTypes; children: ReactNode }) {
    return (
        <Wrapper p={1} color="white" type={type}>
            {children}
        </Wrapper>
    )
}

const Wrapper = styled(Box)<{ type: ColorTypes }>`
    display: inline;
    border-radius: 10px;
    background-color: ${({ type }) =>
        type === 'success' ? '#28a745' : type === 'alert' ? '#ffc107' : type === 'danger' ? '#dc3545' : ''};
`
