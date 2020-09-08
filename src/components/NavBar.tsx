import { Box, Button, Flex, Text } from 'rebass/styled-components'
import React from 'react'
import { Link } from 'react-router-dom'

export function NavBar() {
    return (
        <Flex px={3} py={2} color="white" bg="black" alignItems="center">
            <Text p={2} fontWeight="bold">
                コンカフェカレンダー
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
