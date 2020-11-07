import { PageWrapperForCalendar, PageWrapperForCalendarHeader } from 'components/PageWrapper'
import React, { useCallback, useEffect, useState } from 'react'
import { Heading } from 'rebass/styled-components'
import dayjs from 'dayjs'
import { useParams } from 'react-router-dom'
import { useLoadStoreAttends, useStoreAttends } from 'store/storeAttends'
import { useLoadStore, useStore } from 'store/store'
import { CastAttendCalendar } from 'components/CastAttendCalendar'

export default function StoreAttends() {
    const { id } = useParams<{ id: string }>()
    const storeId = parseInt(id)
    const [year, setYear] = useState(dayjs().year())
    const [month, setMonth] = useState(dayjs().month() + 1)

    const loadStoreAttends = useLoadStoreAttends({ storeId, year, month })
    const storeAttends = useStoreAttends({ storeId, year, month })
    const store = useStore(storeId)
    const loadStore = useLoadStore(storeId)

    const onYearMonthChange = useCallback((yearMonth: { year: number; month: number }) => {
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
        <>
            <PageWrapperForCalendarHeader>
                <Heading>{store.storeName}の出勤カレンダー</Heading>
            </PageWrapperForCalendarHeader>
            <PageWrapperForCalendar paddingTop={3}>
                <CastAttendCalendar attends={storeAttends ?? []} onYearMonthChange={onYearMonthChange} />
            </PageWrapperForCalendar>
        </>
    )
}
