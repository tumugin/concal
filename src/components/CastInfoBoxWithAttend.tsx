import styled from 'styled-components'
import { Link } from 'react-router-dom'
import React, { useMemo } from 'react'
import dayjs from 'dayjs'
import { Box, Text } from 'rebass/styled-components'
import { StoreCastData } from 'api/store'

export function CastInfoBoxWithAttend({ cast, hideDate }: { cast: StoreCastData; hideDate?: boolean }) {
    const dayjsStartTime = useMemo(() => dayjs(cast.recentAttend?.startTime), [cast.recentAttend])
    const dayjsEndTime = useMemo(() => dayjs(cast.recentAttend?.endTime), [cast.recentAttend])

    return (
        <NoStyleLink to={`/casts/${cast.id}`}>
            <Box
                sx={{
                    borderRadius: '4px',
                    backgroundColor: 'muted',
                    borderLeft: (t) => `8px solid ${cast.castColor ?? t.colors.primary}`,
                }}
                padding={2}
                paddingLeft={3}
            >
                <Text fontSize={3} fontWeight="bold">
                    {cast.castName}
                </Text>
                {cast.recentAttend && (
                    <Text fontSize={2}>
                        {dayjsStartTime.format(hideDate ? 'HH:mm' : 'MM/DD(dd) HH:mm')}～{dayjsEndTime.format('HH:mm')}
                    </Text>
                )}
                {!cast.recentAttend && <Text fontSize={2}>直近の出勤なし</Text>}
            </Box>
        </NoStyleLink>
    )
}

const NoStyleLink = styled(Link)`
    color: white;
    text-decoration: none;
`
