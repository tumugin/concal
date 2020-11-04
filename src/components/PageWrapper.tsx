import { Box } from 'rebass/styled-components'
import React from 'react'
import styled from 'styled-components'
import { responsiveMobileMaxWidth } from 'styles/responsive'

export function PageWrapper({ children }: { children: React.ReactNode }) {
    return (
        <ResponsiveBox p={4} sx={{ marginLeft: 'auto', marginRight: 'auto', maxWidth: '1600px' }}>
            {children}
        </ResponsiveBox>
    )
}

export function PageWrapperForCalendarHeader({ children }: { children: React.ReactNode }) {
    return (
        <ResponsiveBox p={4} paddingBottom={0} sx={{ marginLeft: 'auto', marginRight: 'auto', maxWidth: '1600px' }}>
            {children}
        </ResponsiveBox>
    )
}

const ResponsiveBox = styled(Box)`
    @media screen and (max-width: ${responsiveMobileMaxWidth}) {
        padding-left: ${({ theme }) => theme.space[3]}px;
        padding-right: ${({ theme }) => theme.space[3]}px;
    }
`

export const PageWrapperForCalendar = styled(Box)`
    padding: ${({ theme }) => theme.space[4]}px;
    margin-left: auto;
    margin-right: auto;
    max-width: 1600px;

    @media screen and (max-width: ${responsiveMobileMaxWidth}) {
        padding-left: 0;
        padding-right: 0;
    }
`
