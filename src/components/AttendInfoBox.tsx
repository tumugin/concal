import { UserCastAttend, UserStore } from 'api/types'
import React, { useMemo } from 'react'
import dayjs from 'dayjs'
import { Box, Text } from 'rebass/styled-components'
import styled from 'styled-components'
import { Link } from 'react-router-dom'

export function AttendInfoBox({
    attend,
    color,
}: {
    attend: UserCastAttend & { store: UserStore }
    color?: string | null
}) {
    const dayjsStartTime = useMemo(() => dayjs(attend.startTime), [attend.startTime])
    const dayjsEndTime = useMemo(() => dayjs(attend.endTime), [attend.endTime])

    return (
        <NoStyleLink to={`/stores/${attend.store.id}`}>
            <Box
                sx={{
                    borderRadius: '4px',
                    backgroundColor: 'muted',
                    borderLeft: (t) => `8px solid ${color ?? t.colors.primary}`,
                }}
                padding={2}
                paddingLeft={3}
            >
                <Text fontSize={3} fontWeight="bold">
                    {attend.store.storeName}
                </Text>
                <Text fontSize={2}>
                    {dayjsStartTime.format('MM/DD HH:mm')}～{dayjsEndTime.format('HH:mm')}
                </Text>
            </Box>
        </NoStyleLink>
    )
}

const NoStyleLink = styled(Link)`
    color: white;
    text-decoration: none;
`
