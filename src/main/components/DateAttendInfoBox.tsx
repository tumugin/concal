import { StoreAttendData } from 'main/api/storeAttends'
import dayjs from 'dayjs'
import React from 'react'
import { Box, Heading } from 'rebass/styled-components'
import { Grid250 } from 'components/Grid250'
import { CastInfoBoxWithAttend } from 'main/components/CastInfoBoxWithAttend'
import { NoStyleLink } from 'components/Link'

export default function DateAttendInfoBox({
    attends,
    date,
    storeId,
}: {
    attends: StoreAttendData[]
    date: dayjs.Dayjs
    storeId: number
}) {
    return (
        <>
            <Heading as="h3" marginTop={3} fontSize={2}>
                <NoStyleLink to={`/stores/${storeId}/attends/${date.year()}/${date.month() + 1}/${date.date()}`}>
                    {date.format('DD日(dd)')}
                </NoStyleLink>
            </Heading>
            {attends.length === 0 && <Box>出勤情報がありません</Box>}
            <Grid250 marginTop={3}>
                {attends.map((attend) => (
                    <CastInfoBoxWithAttend cast={{ ...attend.cast, recentAttend: attend }} key={attend.id} hideDate />
                ))}
            </Grid250>
        </>
    )
}
