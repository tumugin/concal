import { Box, Button, Flex, Text } from 'rebass/styled-components'
import React from 'react'
import { Link } from 'react-router-dom'
import styled from 'styled-components'

export function NavBar() {
    return (
        <Flex px={3} py={2} color="white" bg="black" alignItems="center">
            <Text p={2} fontWeight="bold">
                <TitleLink to="/">コンカフェカレンダー</TitleLink>
            </Text>
            <Box mx="auto" />
            <Link to="/login">
                <Button variant="outline" mr={2}>
                    ログイン
                </Button>
            </Link>
        </Flex>
    )
}

const TitleLink = styled(Link)`
    color: white;
    text-decoration: none;
`
