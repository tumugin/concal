import { StoreAttendData } from 'api/storeAttends'
import { getCalendarLocalizer } from 'utils/calendar'
import React, { useCallback, useMemo } from 'react'
import dayjs from 'dayjs'
import { Calendar } from 'react-big-calendar'
import styled from 'styled-components'
import { Box } from 'rebass/styled-components'
import 'styles/react-big-calendar-dark.scss'
import { responsiveMobileMaxWidth } from 'styles/responsive'

export function CastAttendCalendar({
    attends,
    onYearMonthChange,
}: {
    attends: StoreAttendData[]
    onYearMonthChange: (yearMonth: { year: number; month: number }) => void
}) {
    const localizer = getCalendarLocalizer()
    const onNavigate = useCallback(
        (newDate: Date) => {
            onYearMonthChange({ year: newDate.getFullYear(), month: newDate.getMonth() + 1 })
        },
        [onYearMonthChange]
    )
    const events = useMemo(
        () =>
            attends.map((attend) => ({
                title: attend.cast.castShortName ?? attend.cast.castName,
                start: dayjs(attend.startTime).toDate(),
                end: dayjs(attend.endTime).toDate(),
                color: attend.cast.castColor,
            })),
        [attends]
    )
    const eventStyleGetter = useCallback((event: typeof events[0]) => {
        return {
            style: {
                backgroundColor: event.color ?? undefined,
            },
        }
    }, [])

    return (
        <CalenderWrapper>
            <Calendar
                localizer={localizer}
                events={events}
                onNavigate={onNavigate}
                eventPropGetter={eventStyleGetter}
                popup
            />
        </CalenderWrapper>
    )
}

const CalenderWrapper = styled(Box)`
    height: calc(100vh - 300px);

    @media screen and (max-width: ${responsiveMobileMaxWidth}) {
        height: 110vh;
    }
`
