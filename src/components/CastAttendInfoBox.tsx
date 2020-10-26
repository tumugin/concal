import { Box, Text } from 'rebass/styled-components'
import dayjs from 'dayjs'
import React, { useMemo } from 'react'
import { TopContentsRecentUpdatedAttends } from 'api/topContents'
import styled from 'styled-components'
import { Link } from 'react-router-dom'

export function CastAttendInfoBox({ attend }: { attend: TopContentsRecentUpdatedAttends }) {
    const dayjsStartTime = useMemo(() => dayjs(attend.startTime), [attend.startTime])
    const dayjsEndTime = useMemo(() => dayjs(attend.endTime), [attend.endTime])

    return (
        <NoStyleLink to={`/casts/${attend.cast.id}`}>
            <Box
                key={attend.id}
                sx={{
                    borderRadius: '4px',
                    backgroundColor: 'muted',
                    borderLeft: (t) => `8px solid ${attend.cast.castColor ?? t.colors.primary}`,
                }}
                padding={2}
                paddingLeft={3}
            >
                <Text fontSize={3} fontWeight="bold">
                    {attend.cast.castName}
                </Text>
                <Text fontSize={2}>
                    {dayjsStartTime.format('MM/DD HH:mm')}～{dayjsEndTime.format('HH:mm')}
                </Text>
                <Text>{attend.store.storeName}</Text>
            </Box>
        </NoStyleLink>
    )
}

const NoStyleLink = styled(Link)`
    color: white;
    text-decoration: none;
`
