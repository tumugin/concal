import styled, { ThemeProvider } from 'styled-components'
// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-ignore
import { deep } from '@theme-ui/presets'
// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-ignore
import preset from '@rebass/preset'
import React, { useEffect, useState } from 'react'
import { Box } from 'rebass/styled-components'
import { Normalize } from 'styled-normalize'
import { useSavedUserLogin } from 'admin/store/user'
import '@sweetalert2/theme-dark/dark.scss'
import 'toastr/build/toastr.css'
import AdminPageRouter from 'admin/AdminPageRouter'
import { AdminStoreProvider } from 'admin/store'

export function AdminApp() {
    return (
        <AdminStoreProvider>
            <AppWithStore />
        </AdminStoreProvider>
    )
}

function AppWithStore() {
    const savedUserLogin = useSavedUserLogin()
    const [loginCompleted, setLoginCompleted] = useState(false)

    useEffect(() => {
        ;(async () => {
            await savedUserLogin()
            setLoginCompleted(true)
        })()
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
                    {loginCompleted && <AdminPageRouter />}
                </WrapperBox>
            </ThemeProvider>
        </>
    )
}

const WrapperBox = styled(Box)`
    width: 100%;
    min-height: 100vh;
`
