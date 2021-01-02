import { Box, Button, Flex, Text } from 'rebass/styled-components'
import React, { useCallback } from 'react'
import { Link, useHistory } from 'react-router-dom'
import styled from 'styled-components'
import { useUser, useUserLogout } from 'main/store/user'
import { responsiveMobileMaxWidth } from 'styles/responsive'
import Swal from 'sweetalert2'

export function NavBar() {
    const history = useHistory()
    const logout = useUserLogout()
    const user = useUser()
    const isLoggedIn = user.isLoggedIn
    const userName = user?.self?.name

    const onUserLogout = useCallback(async () => {
        const dialogResult = await Swal.fire({
            icon: 'question',
            title: 'ログアウトしますか？',
            showCancelButton: true,
            showConfirmButton: true,
        })
        if (dialogResult.isConfirmed) {
            await logout()
            history.push('/')
        }
    }, [history, logout])

    return (
        <>
            <Flex px={3} py={2} color="white" bg="black" alignItems="center">
                <TitleLink to="/">
                    <Text p={2} fontWeight="bold">
                        コンカフェカレンダー
                    </Text>
                </TitleLink>
                <Box mx="auto" />
                {!isLoggedIn && (
                    <Link to="/login">
                        <Button variant="outline" mr={2}>
                            ログイン
                        </Button>
                    </Link>
                )}
                {isLoggedIn && (
                    <DesktopLoggedInHeader>
                        <SenpaiNameText mr={3}>
                            ようこそ<SenpaiName>{userName}</SenpaiName>センパイ!
                        </SenpaiNameText>
                        <Button variant="outline" mr={2} onClick={onUserLogout}>
                            ログアウト
                        </Button>
                    </DesktopLoggedInHeader>
                )}
                {isLoggedIn && (
                    <MobileLoggedInHeader>
                        <Button variant="outline" mr={2} onClick={onUserLogout}>
                            @{userName}
                        </Button>
                    </MobileLoggedInHeader>
                )}
            </Flex>
        </>
    )
}

const DesktopLoggedInHeader = styled(Flex)`
    @media screen and (max-width: ${responsiveMobileMaxWidth}) {
        display: none;
    }
`

const MobileLoggedInHeader = styled(Flex)`
    @media screen and (min-width: ${responsiveMobileMaxWidth}) {
        display: none;
    }
`

const SenpaiNameText = styled(Text)`
    display: flex;
    align-items: center;
`

const SenpaiName = styled.span`
    font-weight: bold;
`

const TitleLink = styled(Link)`
    color: white;
    text-decoration: none;
`
