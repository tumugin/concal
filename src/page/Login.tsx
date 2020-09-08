import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Heading } from 'rebass/styled-components'
import React, { useState } from 'react'
import styled from 'styled-components'
import { Label, Input } from '@rebass/forms/styled-components'
import { Note } from 'components/Note'

export function Login() {
    const [userIdentifier, setUserIdentifier] = useState('')
    const [password, setPassword] = useState('')
    const [isAuthFailed] = useState(false)

    return (
        <PageWrapper>
            <Heading>ログイン</Heading>
            <CenteringWrapper>
                <LoginBox>
                    <Label>メールアドレス・ユーザID</Label>
                    <Input
                        type="email"
                        placeholder="concafe@example.com"
                        value={userIdentifier}
                        onChange={(event) => setUserIdentifier(event.target.value)}
                    />
                    <Label mt={2}>パスワード</Label>
                    <Input
                        type="password"
                        placeholder="concafe_ikitai_yooooo"
                        value={password}
                        onChange={(event) => setPassword(event.target.value)}
                    />
                    {isAuthFailed && <Note>パスワードもしくはユーザ名が違います。</Note>}
                    <CenteringGrid mt={4}>
                        <Button variant="primary" mr={2}>
                            ログイン
                        </Button>
                        <Button variant="outline" mr={2}>
                            新規登録(※現在準備中)
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
