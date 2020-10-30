import styled, { ThemeProvider } from 'styled-components'
// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-ignore
import { deep } from '@theme-ui/presets'
// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-ignore
import preset from '@rebass/preset'
import PageRouter from 'pageRouter'
import { StoreProvider } from 'store'
import React, { useEffect } from 'react'
import { Box } from 'rebass/styled-components'
import { Normalize } from 'styled-normalize'
import { useSavedUserLogin } from 'store/user'
import '@sweetalert2/theme-dark/dark.scss'
import 'toastr/build/toastr.css'

export function App() {
    return (
        <StoreProvider>
            <AppWithStore />
        </StoreProvider>
    )
}

function AppWithStore() {
    const savedUserLogin = useSavedUserLogin()

    useEffect(() => {
        void savedUserLogin()
    }, [savedUserLogin])

    return (
        <>
            <Normalize />
            <ThemeProvider theme={{ ...preset, ...deep }}>
                <WrapperBox
                    sx={{
                        color: 'text',
                        bg: 'background',
                        fontWeight: 'body',
                        lineHeight: 'body',
                    }}
                >
                    <PageRouter />
                </WrapperBox>
            </ThemeProvider>
        </>
    )
}

const WrapperBox = styled(Box)`
    width: 100%;
    min-height: 100vh;
`
