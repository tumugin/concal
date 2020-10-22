import React, { useEffect } from 'react'
import { Box, Flex, Heading, Text } from 'rebass/styled-components'
import { PageWrapper } from 'components/PageWrapper'
import { useLoadTopContents, useTop } from 'store/top'
import dayjs from 'dayjs'

export function Top() {
    const top = useTop()
    const loadTopContents = useLoadTopContents()
    useEffect(() => {
        if (!top.loaded) {
            void loadTopContents()
        }
    }, [loadTopContents, top.loaded])

    return <PageWrapper>{top.loaded && <TopContentsArea />}</PageWrapper>
}

function TopContentsArea() {
    const top = useTop()
    return (
        <>
            <Heading>最近更新された勤務情報</Heading>
            <Box
                sx={{
                    display: 'grid',
                    gridGap: 3,
                    gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))',
                }}
                marginY={3}
            >
                {top.recentUpdatedAttends.map((attend) => (
                    <Box
                        key={attend.id}
                        sx={{
                            borderRadius: '4px',
                            backgroundColor: 'muted',
                            borderLeft: (t) => `8px solid ${t.colors.primary}`,
                        }}
                        padding={2}
                        paddingLeft={3}
                    >
                        <Text fontSize={3} fontWeight="bold">
                            {attend.cast.castName}
                        </Text>
                        <Text fontSize={2}>
                            {dayjs(attend.startTime).format('MM/DD HH:mm')}～{dayjs(attend.endTime).format('HH:mm')}
                        </Text>
                        <Text>{attend.store.storeName}</Text>
                    </Box>
                ))}
            </Box>
            <Heading marginTop={4}>グループ・店舗</Heading>
            <Box marginY={3}>
                <Box
                    sx={{
                        display: 'grid',
                        gridGap: 3,
                    }}
                >
                    {top.storeGroups.map((group) => (
                        <Box key={group.id}>
                            <Heading as="h3" fontSize={3} marginBottom={3}>
                                {group.groupName}
                            </Heading>
                            <Box
                                sx={{
                                    display: 'grid',
                                    gridGap: 3,
                                    gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))',
                                }}
                            >
                                {group.stores.map((store) => (
                                    <Flex
                                        key={store.id}
                                        sx={{
                                            backgroundColor: 'muted',
                                            alignItems: 'center',
                                            justifyContent: 'center',
                                            textAlign: 'center',
                                            borderRadius: '6px',
                                        }}
                                        padding={3}
                                    >
                                        {store.storeName}
                                    </Flex>
                                ))}
                            </Box>
                        </Box>
                    ))}
                </Box>
            </Box>
        </>
    )
}
