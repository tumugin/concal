import { PageWrapperForCalendar, PageWrapperForCalendarHeader } from 'components/PageWrapper'
import React, { useCallback, useEffect } from 'react'
import { Heading } from 'rebass/styled-components'
import dayjs from 'dayjs'
import { useHistory, useLocation, useParams } from 'react-router-dom'
import { useLoadStoreAttends, useStoreAttends } from 'main/store/storeAttends'
import { useLoadStore, useStore } from 'main/store/store'
import { CastAttendCalendar } from 'main/components/CastAttendCalendar'
import { useQueryNumber } from 'hooks/queryParam'

export default function StoreAttendsCalendar() {
    const location = useLocation()
    const history = useHistory()
    const { id } = useParams<{ id: string }>()
    const storeId = parseInt(id)
    const [year] = useQueryNumber('year', dayjs().year())
    const [month] = useQueryNumber('month', dayjs().month() + 1)
    const [date] = useQueryNumber('date', dayjs().date())

    const loadStoreAttends = useLoadStoreAttends({ storeId, year, month })
    const storeAttends = useStoreAttends({ storeId, year, month })
    const store = useStore(storeId)
    const loadStore = useLoadStore(storeId)

    const onYearMonthDateChange = useCallback(
        (yearMonth: { year: number; month: number; date: number }) => {
            history.push({
                ...location,
                search: new URLSearchParams({
                    year: `${yearMonth.year}`,
                    month: `${yearMonth.month}`,
                    date: `${yearMonth.date}`,
                }).toString(),
            })
        },
        [history, location]
    )

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
        <>
            <PageWrapperForCalendarHeader>
                <Heading>{store.storeName}の出勤カレンダー</Heading>
            </PageWrapperForCalendarHeader>
            <PageWrapperForCalendar paddingTop={3}>
                <CastAttendCalendar
                    attends={storeAttends ?? []}
                    onYearMonthDateChange={onYearMonthDateChange}
                    year={year}
                    month={month}
                    date={date}
                />
            </PageWrapperForCalendar>
        </>
    )
}
