import { Box, Button, Flex, Text } from 'rebass/styled-components'
import React from 'react'
import { Link } from 'react-router-dom'
import styled from 'styled-components'
import { useStoreContext } from 'store/store'

export function NavBar() {
    const { store } = useStoreContext()
    const isLoggedIn = store.user.isLoggedIn
    const isAdminUser = store.user?.self?.userPrivilege === 'admin'

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
            </Flex>
            {isAdminUser && (
                <Flex px={2} py={1} bg="muted">
                    <Text p={2} fontWeight="bold">
                        管理者メニュー
                    </Text>
                    <TitleLink to="/">
                        <Text p={2}>店舗グループ管理</Text>
                    </TitleLink>
                    <TitleLink to="/">
                        <Text p={2}>店舗管理</Text>
                    </TitleLink>
                    <TitleLink to="/">
                        <Text p={2}>キャスト・出勤管理</Text>
                    </TitleLink>
                </Flex>
            )}
        </>
    )
}

const TitleLink = styled(Link)`
    color: white;
    text-decoration: none;
`
