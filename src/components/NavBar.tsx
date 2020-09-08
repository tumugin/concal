import { Box, Button, Flex, Text } from 'rebass/styled-components'
import React from 'react'
import { Link } from 'react-router-dom'
import styled from 'styled-components'
import { useStoreContext } from 'store/store'

export function NavBar() {
    const { store } = useStoreContext()
    const isLoggedIn = store.user.isLoggedIn

    return (
        <Flex px={3} py={2} color="white" bg="black" alignItems="center">
            <Text p={2} fontWeight="bold">
                <TitleLink to="/">コンカフェカレンダー</TitleLink>
            </Text>
            <Box mx="auto" />
            {!isLoggedIn && (
                <Link to="/login">
                    <Button variant="outline" mr={2}>
                        ログイン
                    </Button>
                </Link>
            )}
        </Flex>
    )
}

const TitleLink = styled(Link)`
    color: white;
    text-decoration: none;
`
