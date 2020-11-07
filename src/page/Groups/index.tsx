import { useGroups, useLoadNextPage } from 'store/groups'
import React, { useEffect } from 'react'
import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import { GroupAndStoreList } from 'components/GroupAndStoreList'

export default function Groups() {
    const groups = useGroups()
    const loadNextPage = useLoadNextPage()
    useEffect(() => {
        if (!groups.initialPageLoaded && !groups.isLoading) {
            void loadNextPage()
        }
    }, [groups.initialPageLoaded, groups.isLoading, loadNextPage])

    return (
        <PageWrapper>
            <Heading>グループ・店舗一覧</Heading>
            <Box marginY={3}>
                <GroupAndStoreList groups={groups.storeGroups} />
            </Box>
            {groups.hasNextPage && (
                <Flex sx={{ justifyContent: 'center' }}>
                    <Button
                        marginTop={3}
                        sx={{ width: '300px', marginLeft: 'auto', marginRight: 'auto' }}
                        onClick={loadNextPage}
                        disabled={groups.isLoading}
                    >
                        もっと見る
                    </Button>
                </Flex>
            )}
        </PageWrapper>
    )
}
