import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Heading } from 'rebass/styled-components'
import React, { KeyboardEvent, useCallback, useState } from 'react'
import styled from 'styled-components'
import { Label, Input } from '@rebass/forms/styled-components'
import { Note } from 'components/Note'
import { useUserLogin } from 'admin/store/user'
import { LoginException } from 'api/error'
import { useHistory } from 'react-router-dom'

export default function AdminLogin() {
    const history = useHistory()
    const [userIdentifier, setUserIdentifier] = useState('')
    const [password, setPassword] = useState('')
    const [isAuthFailed, setIsAuthFailed] = useState(false)
    const [isLoading, setIsLoading] = useState(false)
    const login = useUserLogin()
    const onLogin = useCallback(async () => {
        setIsLoading(true)
        try {
            await login({
                userIdentifier,
                password,
            })
            setIsAuthFailed(false)
            history.replace('/admin/')
        } catch (e) {
            if (e instanceof LoginException) {
                setIsAuthFailed(true)
            } else {
                throw e
            }
        }
        setIsLoading(false)
    }, [history, login, password, userIdentifier])
    const onKeyPressHandler = useCallback(
        (e: KeyboardEvent) => {
            if (e.key === 'Enter') {
                e.preventDefault()
                void onLogin()
            }
        },
        [onLogin]
    )

    return (
        <PageWrapper>
            <Heading>管理画面ログイン</Heading>
            <CenteringWrapper>
                <LoginBox>
                    <Label>メールアドレス・ユーザID</Label>
                    <Input
                        type="email"
                        placeholder="concafe@example.com"
                        value={userIdentifier}
                        onChange={(event) => setUserIdentifier(event.target.value)}
                        onKeyPress={onKeyPressHandler}
                        disabled={isLoading}
                    />
                    <Label mt={2}>パスワード</Label>
                    <Input
                        type="password"
                        placeholder="concafe_ikitai_yooooo"
                        value={password}
                        onChange={(event) => setPassword(event.target.value)}
                        onKeyPress={onKeyPressHandler}
                        disabled={isLoading}
                    />
                    {isAuthFailed && (
                        <Box mt={2}>
                            <Note>パスワードもしくはユーザ名が違います。</Note>
                        </Box>
                    )}
                    <CenteringGrid mt={4}>
                        <Button variant="primary" mr={2} onClick={onLogin} disabled={isLoading}>
                            ログイン
                        </Button>
                    </CenteringGrid>
                </LoginBox>
            </CenteringWrapper>
        </PageWrapper>
    )
}

const CenteringWrapper = styled(Box)`
    display: flex;
    justify-content: center;
`

const CenteringGrid = styled(Box)`
    display: grid;
    grid-auto-flow: row;
    justify-content: center;
    row-gap: 8px;
`

const LoginBox = styled(Box)`
    margin-top: 48px;
    width: 600px;
    max-width: 600px;
`
