import React, { ReactNode } from 'react'
import { Box } from 'rebass/styled-components'
import styled from 'styled-components'
import { BootstrapLikeColors } from 'utils/bootstrapLike'

type ColorTypes = 'danger' | 'alert' | 'success'

export function AdminInfoBox({
    children,
    header,
    type,
}: {
    children: ReactNode
    header?: ReactNode
    type?: ColorTypes
}) {
    return (
        <Wrapper type={type}>
            {header && (
                <HeaderArea sx={{ bg: 'muted', fontWeight: 'bold', fontSize: 18 }} px={4} py={2}>
                    {header}
                </HeaderArea>
            )}
            <Box px={4} py={3}>
                {children}
            </Box>
        </Wrapper>
    )
}

const Wrapper = styled(Box)<{ type?: ColorTypes }>`
    border-radius: 16px;
    border: ${({ type, theme }) =>
            type === 'success'
                ? BootstrapLikeColors.success
                : type === 'alert'
                ? BootstrapLikeColors.alert
                : type === 'danger'
                ? BootstrapLikeColors.danger
                : theme.colors.secondary}
        1px solid;
`

const HeaderArea = styled(Box)``
