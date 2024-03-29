import React, { useEffect } from 'react'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import { PageWrapper } from 'components/PageWrapper'
import { useLoadTopContents, useTop } from 'main/store/top'
import { CastAttendInfoBox } from 'main/components/CastAttendInfoBox'
import { GroupAndStoreList } from 'main/components/GroupAndStoreList'
import { Link } from 'react-router-dom'
import styled from 'styled-components'
import { Grid250 } from 'components/Grid250'

export default function Top() {
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
        <Box
            sx={{
                display: 'grid',
                gridGap: 4,
            }}
        >
            <Box>
                <Heading>グループ・店舗一覧</Heading>
                <Box marginY={3}>
                    <GroupAndStoreList groups={top.storeGroups} />
                </Box>
                <Flex sx={{ justifyContent: 'center' }}>
                    <StyledLink to="/groups">
                        <Button marginTop={3} sx={{ width: '100%' }}>
                            もっと見る
                        </Button>
                    </StyledLink>
                </Flex>
            </Box>
            <Box>
                <Heading>最近更新された勤務情報</Heading>
                <Grid250 marginTop={3}>
                    {top.recentUpdatedAttends.map((attend) => (
                        <CastAttendInfoBox key={attend.id} attend={attend} />
                    ))}
                </Grid250>
            </Box>
        </Box>
    )
}

const StyledLink = styled(Link)`
    margin-left: auto;
    margin-right: auto;
    width: 300px;
`
