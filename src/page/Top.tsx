import React, { useEffect } from 'react'
import { Box, Heading } from 'rebass/styled-components'
import { PageWrapper } from 'components/PageWrapper'
import { useLoadTopContents, useTop } from 'store/top'
import { CastAttendInfoBox } from 'components/CastAttendInfoBox'
import { GroupAndStoreList } from 'components/GroupAndStoreList'

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
                    <CastAttendInfoBox key={attend.id} attend={attend} />
                ))}
            </Box>
            <Heading marginTop={4}>グループ・店舗</Heading>
            <Box marginY={3}>
                <GroupAndStoreList groups={top.storeGroups} />
            </Box>
        </>
    )
}
