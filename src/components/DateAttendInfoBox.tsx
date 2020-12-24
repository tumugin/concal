import { StoreAttendData } from 'api/storeAttends'
import dayjs from 'dayjs'
import React from 'react'
import { Box, Heading } from 'rebass/styled-components'
import { Grid250 } from 'components/Grid250'
import { CastInfoBoxWithAttend } from 'components/CastInfoBoxWithAttend'

export default function DateAttendInfoBox({ attends, date }: { attends: StoreAttendData[]; date: dayjs.Dayjs }) {
    return (
        <>
            <Heading as="h3" marginTop={3} fontSize={2}>
                {date.format('DD日(dd)')}
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
