import { Box, Text } from 'rebass/styled-components'
import dayjs from 'dayjs'
import React from 'react'
import { TopContentsRecentUpdatedAttends } from 'api/topContents'

export function CastAttendInfoBox({ attend }: { attend: TopContentsRecentUpdatedAttends }) {
    return (
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
    )
}
