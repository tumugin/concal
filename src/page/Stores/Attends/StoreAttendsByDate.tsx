import { useParams } from 'react-router-dom'
import React, { useEffect, useMemo } from 'react'
import { useLoadStore, useStore } from 'store/store'
import { useLoadStoreAttends, useStoreAttends } from 'store/storeAttends'
import dayjs from 'dayjs'
import { PageWrapper } from 'components/PageWrapper'
import { Heading } from 'rebass/styled-components'
import DateAttendInfoBox from 'components/DateAttendInfoBox'

export default function StoreAttendsByDate() {
    const params = useParams<{ id: string; year: string; month: string; date: string }>()
    const { id, year, month, date } = useMemo(() => {
        return {
            id: parseInt(params.id),
            year: parseInt(params.year),
            month: parseInt(params.month),
            date: parseInt(params.date),
        }
    }, [params.date, params.id, params.month, params.year])
    const store = useStore(id)
    const loadStore = useLoadStore(id)
    const loadStoreAttends = useLoadStoreAttends({
        storeId: id,
        year: year,
        month: month,
    })
    const dateDayJs = dayjs()
        .year(year)
        .month(month - 1)
        .date(date)
    const storeAttends = useStoreAttends({ storeId: id, year: year, month: month })
    const todayAttends = useMemo(
        () => storeAttends?.filter((atttend) => dayjs(atttend.startTime).isSame(dateDayJs, 'date')) ?? [],
        [storeAttends, dateDayJs]
    )

    useEffect(() => {
        if (!storeAttends) {
            void loadStoreAttends()
        }
        if (!store) {
            void loadStore()
        }
    }, [loadStore, loadStoreAttends, store, storeAttends])

    if (!storeAttends || !store) {
        return null
    }

    return (
        <PageWrapper>
            <Heading>
                {store.storeName}の出勤情報({year}年{month}月{date}日)
            </Heading>
            <DateAttendInfoBox attends={todayAttends} date={dateDayJs} storeId={id} />
        </PageWrapper>
    )
}
