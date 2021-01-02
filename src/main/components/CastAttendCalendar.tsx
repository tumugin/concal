import { StoreAttendData } from 'main/api/storeAttends'
import { getCalendarLocalizer } from 'utils/calendar'
import React, { useCallback, useMemo } from 'react'
import dayjs from 'dayjs'
import { Calendar } from 'react-big-calendar'
import styled from 'styled-components'
import { Box } from 'rebass/styled-components'
import 'main/styles/react-big-calendar-dark.scss'
import { responsiveMobileMaxWidth } from 'styles/responsive'
import { ReactSwal } from 'components/swal'
import { InfoGrid } from 'components/InfoGrid'

export function CastAttendCalendar({
    attends,
    onYearMonthDateChange,
    year,
    month,
    date,
}: {
    attends: StoreAttendData[]
    onYearMonthDateChange: (yearMonth: { year: number; month: number; date: number }) => void
    year: number
    month: number
    date: number
}) {
    const localizer = getCalendarLocalizer()
    const onNavigate = useCallback(
        (newDate: Date) => {
            onYearMonthDateChange({
                year: newDate.getFullYear(),
                month: newDate.getMonth() + 1,
                date: newDate.getDate(),
            })
        },
        [onYearMonthDateChange]
    )
    const events = useMemo(
        () =>
            attends.map((attend) => ({
                rawAttendData: attend,
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
    const onSelectEvent = useCallback((event: typeof events[0]) => {
        ReactSwal.fire({
            title: 'キャスト出勤情報',
            html: (
                <InfoGrid
                    data={[
                        {
                            name: 'キャスト名',
                            value: (
                                <DialogLink
                                    href={`/casts/${event.rawAttendData.cast.id}`}
                                    target="_blank"
                                    rel="noreferrer"
                                >
                                    {event.rawAttendData.cast.castName}
                                </DialogLink>
                            ),
                        },
                        {
                            name: '出勤開始時間',
                            value: dayjs(event.rawAttendData.startTime).format('YYYY/MM/DD HH:mm'),
                        },
                        { name: '出勤終了時間', value: dayjs(event.rawAttendData.endTime).format('YYYY/MM/DD HH:mm') },
                        {
                            name: '出勤情報',
                            value: event.rawAttendData.attendInfo === '' ? '(未設定)' : event.rawAttendData.attendInfo,
                        },
                    ]}
                />
            ),
            showCloseButton: true,
            showConfirmButton: false,
        })
    }, [])

    const calenderDate = dayjs()
        .year(year)
        .month(month - 1)
        .date(date)
        .toDate()

    return (
        <CalenderWrapper>
            <Calendar
                localizer={localizer}
                events={events}
                onNavigate={onNavigate}
                eventPropGetter={eventStyleGetter}
                date={calenderDate}
                onSelectEvent={onSelectEvent}
                popup
            />
        </CalenderWrapper>
    )
}

const DialogLink = styled.a`
    color: inherit;
`

const CalenderWrapper = styled(Box)`
    height: calc(100vh - 200px);

    @media screen and (max-width: ${responsiveMobileMaxWidth}) {
        height: 110vh;
    }
`
