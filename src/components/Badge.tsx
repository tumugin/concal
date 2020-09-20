import React, { ReactNode } from 'react'
import { Box } from 'rebass/styled-components'
import styled from 'styled-components'
import { BootstrapLikeColors } from 'utils/bootstrapLike'

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
        type === 'success'
            ? BootstrapLikeColors.success
            : type === 'alert'
            ? BootstrapLikeColors.alert
            : type === 'danger'
            ? BootstrapLikeColors.danger
            : 'unset'};
`
