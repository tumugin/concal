import { useGroups, useLoadNextPage } from 'store/groups'
import React, { useEffect } from 'react'
import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import { GroupAndStoreList } from 'components/GroupAndStoreList'

export function Groups() {
    const groups = useGroups()
    const loadNextPage = useLoadNextPage()
    useEffect(() => {
        if (!groups.initialPageLoaded) {
            void loadNextPage()
        }
    }, [groups.initialPageLoaded, loadNextPage])

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
