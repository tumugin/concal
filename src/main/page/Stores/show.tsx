import { Link, useParams } from 'react-router-dom'
import { useLoadStore, useStore } from 'main/store/store'
import React, { useEffect, useMemo } from 'react'
import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import { CastInfoBoxWithAttend } from 'main/components/CastInfoBoxWithAttend'
import dayjs from 'dayjs'
import { Grid250 } from 'components/Grid250'
import { Note } from 'components/Note'
import { BootstrapLikeColors } from 'utils/bootstrapLike'
import styled from 'styled-components'
import NotFoundBox from 'components/NotFoundBox'

export default function Store() {
    const { id } = useParams<{ id: string }>()
    const parsedId = parseInt(id)
    const store = useStore(parsedId)
    const loadStore = useLoadStore(parsedId)
    const todayAttendCasts = useMemo(
        () =>
            store?.casts.filter((cast) => {
                if (!cast.recentAttend) {
                    return false
                }
                return (
                    dayjs(cast.recentAttend.startTime).date() === dayjs().date() ||
                    dayjs(cast.recentAttend.endTime).date() === dayjs().date()
                )
            }) ?? [],
        [store]
    )

    useEffect(() => {
        if (!store) {
            void loadStore()
        }
    }, [loadStore, store])

    if (!store) {
        return null
    }

    return (
        <PageWrapper>
            <Heading>{store.storeName}</Heading>
            <Box fontSize={2} marginTop={1}>
                {store.storeGroup.groupName}
            </Box>
            {store.storeDisabled && <Note color={BootstrapLikeColors.alert}>この店舗は既に閉店しています</Note>}
            <Box marginTop={3}>
                <Link to={`/stores/${id}/attends`}>
                    <Button>この店舗の出勤カレンダーを表示</Button>
                </Link>
            </Box>
            <Heading as="h3" marginTop={3} fontSize={3}>
                本日の出勤キャスト
            </Heading>
            {todayAttendCasts.length > 0 ? (
                <Grid250 marginTop={3}>
                    {todayAttendCasts.map((cast) => (
                        <CastInfoBoxWithAttend cast={cast} key={cast.id} />
                    ))}
                </Grid250>
            ) : (
                <NotFoundBox marginTop={3}>本日の出勤情報がありません</NotFoundBox>
            )}
            <Flex sx={{ justifyContent: 'center' }}>
                <StyledLink to={`/stores/${id}/attends/${dayjs().year()}/${dayjs().month() + 1}`}>
                    <Button marginTop={3} sx={{ width: '100%' }}>
                        今月の出勤情報を見る
                    </Button>
                </StyledLink>
            </Flex>
            <Heading as="h3" marginTop={3} fontSize={3}>
                在籍キャスト
            </Heading>
            {store.casts.length > 0 ? (
                <Grid250 marginTop={3}>
                    {store.casts.map((cast) => (
                        <CastInfoBoxWithAttend cast={cast} key={cast.id} />
                    ))}
                </Grid250>
            ) : (
                <NotFoundBox marginTop={3}>在籍キャスト情報がありません</NotFoundBox>
            )}
        </PageWrapper>
    )
}

const StyledLink = styled(Link)`
    margin-left: auto;
    margin-right: auto;
    width: 300px;
`
