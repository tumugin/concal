import { Link, useParams } from 'react-router-dom'
import { useLoadStore, useStore } from 'store/store'
import React, { useEffect, useMemo } from 'react'
import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Heading } from 'rebass/styled-components'
import { CastInfoBoxWithAttend } from 'components/CastInfoBoxWithAttend'
import dayjs from 'dayjs'
import { Grid250 } from 'components/Grid250'
import { Note } from 'components/Note'
import { BootstrapLikeColors } from 'utils/bootstrapLike'

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
                const startTimeDayDiff = Math.abs(dayjs(cast.recentAttend.startTime).diff(dayjs(), 'day'))
                const endTimeDayDiff = Math.abs(dayjs(cast.recentAttend.endTime).diff(dayjs(), 'day'))
                return startTimeDayDiff === 0 || endTimeDayDiff === 0
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
            {todayAttendCasts.length > 0 && (
                <>
                    <Heading as="h3" marginTop={3} fontSize={3}>
                        本日の出勤キャスト
                    </Heading>
                    <Grid250 marginTop={3}>
                        {todayAttendCasts.map((cast) => (
                            <CastInfoBoxWithAttend cast={cast} key={cast.id} />
                        ))}
                    </Grid250>
                </>
            )}
            <Heading as="h3" marginTop={3} fontSize={3}>
                在籍キャスト
            </Heading>
            <Grid250 marginTop={3}>
                {store.casts.map((cast) => (
                    <CastInfoBoxWithAttend cast={cast} key={cast.id} />
                ))}
            </Grid250>
        </PageWrapper>
    )
}
