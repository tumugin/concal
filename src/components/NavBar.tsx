import { Box, Button, Flex, Text } from 'rebass/styled-components'
import React, { useCallback } from 'react'
import { Link, useHistory } from 'react-router-dom'
import styled from 'styled-components'
import { useUser, useUserLogout } from 'store/user'

export function NavBar() {
    const history = useHistory()
    const logout = useUserLogout()
    const user = useUser()
    const isLoggedIn = user.isLoggedIn
    const isAdminUser = user?.self?.userPrivilege === 'admin'
    const userName = user?.self?.name

    const onUserLogout = useCallback(async () => {
        await logout()
        history.push('/')
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
                    <Flex>
                        <SenpaiNameText mr={3}>
                            ようこそ<SenpaiName>{userName}</SenpaiName>センパイ!
                        </SenpaiNameText>
                        <Button variant="outline" mr={2} onClick={onUserLogout}>
                            ログアウト
                        </Button>
                    </Flex>
                )}
            </Flex>
            {isAdminUser && (
                <AdminMenu px={3} py={1} bg="muted">
                    <AdminMenuText p={2} fontWeight="bold">
                        管理者メニュー
                    </AdminMenuText>
                    <TitleLink to="/admin/users">
                        <AdminMenuText p={2}>ユーザ管理</AdminMenuText>
                    </TitleLink>
                    <TitleLink to="/admin/groups">
                        <AdminMenuText p={2}>店舗グループ管理</AdminMenuText>
                    </TitleLink>
                    <TitleLink to="/admin/stores">
                        <AdminMenuText p={2}>店舗管理</AdminMenuText>
                    </TitleLink>
                    <TitleLink to="/admin/casts">
                        <AdminMenuText p={2}>キャスト・出勤管理</AdminMenuText>
                    </TitleLink>
                </AdminMenu>
            )}
        </>
    )
}

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

const AdminMenu = styled(Flex)`
    white-space: nowrap;
    overflow: auto;
`

const AdminMenuText = styled(Text)`
    min-width: unset;
`
