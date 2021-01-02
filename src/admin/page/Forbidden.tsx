import { Box, Heading, Text } from 'rebass/styled-components'
import { PageWrapper } from 'components/PageWrapper'
import React from 'react'

export function Forbidden() {
    return (
        <PageWrapper>
            <Heading>アクセスが拒否されました</Heading>
            <Box marginTop={3}>
                <Text>このページの閲覧には管理者権限が必要です。</Text>
            </Box>
        </PageWrapper>
    )
}
