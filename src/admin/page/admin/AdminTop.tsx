import { PageWrapper } from 'components/PageWrapper'
import { Heading, Text } from 'rebass/styled-components'
import React from 'react'

export default function AdminTop() {
    return (
        <PageWrapper>
            <Heading mb={4}>管理画面トップ</Heading>
            <Text>上部メニューから管理したい項目を選んでください</Text>
        </PageWrapper>
    )
}
