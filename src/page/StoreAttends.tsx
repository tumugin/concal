import { PageWrapper } from 'components/PageWrapper'
import React, { useCallback, useEffect, useMemo, useState } from 'react'
import { Box, Heading } from 'rebass/styled-components'
import dayjs from 'dayjs'
import { useParams } from 'react-router-dom'
import { useLoadStoreAttends, useStoreAttends } from 'store/storeAttends'
import { Calendar } from 'react-big-calendar'
import 'styles/react-big-calendar-dark.scss'
import styled from 'styled-components'
import { getCalendarLocalizer } from 'utils/calendar'
import { StoreAttendData } from 'api/storeAttends'
import { useLoadStore, useStore } from 'store/store'

type YearMonth = { year: number; month: number }

export function StoreAttends() {
    const { id } = useParams<{ id: string }>()
    const storeId = parseInt(id)
    const [year, setYear] = useState(dayjs().year())
    const [month, setMonth] = useState(dayjs().month() + 1)

    const loadStoreAttends = useLoadStoreAttends({ storeId, year, month })
    const storeAttends = useStoreAttends({ storeId, year, month })
    const store = useStore(storeId)
    const loadStore = useLoadStore(storeId)

    const onYearMonthChange = useCallback((yearMonth: YearMonth) => {
        setYear(yearMonth.year)
        setMonth(yearMonth.month)
    }, [])

    useEffect(() => {
        if (!storeAttends) {
            void loadStoreAttends()
        }
        if (!store) {
            void loadStore()
        }
    }, [loadStore, loadStoreAttends, store, storeAttends])

    if (!store) {
        return null
    }

    return (
        <PageWrapper>
            <Heading>{store.storeName}の出勤カレンダー</Heading>
            <Box marginTop={3}>
                <CalenderArea attends={storeAttends ?? []} onYearMonthChange={onYearMonthChange} />
            </Box>
        </PageWrapper>
    )
}

function CalenderArea({
    attends,
    onYearMonthChange,
}: {
    attends: StoreAttendData[]
    onYearMonthChange: (yearMonth: YearMonth) => void
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
            })),
        [attends]
    )
    return (
        <CalenderWrapper>
            <Calendar localizer={localizer} events={events} onNavigate={onNavigate} popup />
        </CalenderWrapper>
    )
}

const CalenderWrapper = styled(Box)`
    height: calc(100vh - 300px);
`
