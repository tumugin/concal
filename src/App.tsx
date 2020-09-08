import styled, { ThemeProvider } from 'styled-components'
// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-ignore
import { deep } from '@theme-ui/presets'
import PageRouter from 'pageRouter'
import { StoreProvider } from 'store/store'
import React from 'react'
import { Box } from 'rebass/styled-components'
import { Normalize } from 'styled-normalize'

export function App() {
    return (
        <StoreProvider>
            <Normalize />
            <ThemeProvider theme={deep}>
                <WrapperBox
                    sx={{
                        color: 'text',
                        bg: 'background',
                        fontFamily: 'body',
                        fontWeight: 'body',
                        lineHeight: 'body',
                    }}
                >
                    <PageRouter />
                </WrapperBox>
            </ThemeProvider>
        </StoreProvider>
    )
}

const WrapperBox = styled(Box)`
    width: 100vw;
    height: 100vh;
`
