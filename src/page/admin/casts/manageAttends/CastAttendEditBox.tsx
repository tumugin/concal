import { AttendDataDetails } from 'api/admin/attends'
import React, { useMemo } from 'react'
import { Box, Button, Flex } from 'rebass/styled-components'
import dayjs from 'dayjs'

export function CastAttendEditBox({ attendData }: { attendData: AttendDataDetails }) {
    const attendStartDate = useMemo(() => dayjs(attendData.startTime).format('DD日 HH:MM'), [attendData.startTime])
    const attendEndDate = useMemo(() => dayjs(attendData.endTime).format('DD日 HH:MM'), [attendData.endTime])

    return (
        <Flex>
            <Button>削除</Button>
            <Button>修正</Button>
            <Box>
                {attendStartDate}～{attendEndDate}
            </Box>
            <Box>
                {attendData.storeName}({attendData.groupName})
            </Box>
            <Box>{attendData.attendInfo}</Box>
        </Flex>
    )
}
