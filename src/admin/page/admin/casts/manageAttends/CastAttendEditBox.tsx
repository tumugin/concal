import { AttendDataDetails } from 'admin/api/attends'
import React, { useMemo } from 'react'
import { Box, Button, Flex } from 'rebass/styled-components'
import dayjs from 'dayjs'

export function CastAttendEditBox({
    attendData,
    onDelete,
}: {
    attendData: AttendDataDetails
    onDelete: (attendData: AttendDataDetails) => void
}) {
    const attendStartDate = useMemo(() => dayjs(attendData.startTime).format('DD日 HH:mm'), [attendData.startTime])
    const attendEndDate = useMemo(() => dayjs(attendData.endTime).format('DD日 HH:mm'), [attendData.endTime])

    return (
        <Flex sx={{ alignItems: 'center' }}>
            <Button onClick={() => onDelete(attendData)}>🗑️</Button>
            <Box marginLeft={2} sx={{ fontWeight: 'bold' }}>
                {attendStartDate} ～ {attendEndDate}
            </Box>
            <Box marginLeft={3}>{attendData.store.storeName}</Box>
            <Box marginLeft={3}>{attendData.attendInfo}</Box>
        </Flex>
    )
}
