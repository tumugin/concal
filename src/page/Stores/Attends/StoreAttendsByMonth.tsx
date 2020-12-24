import React, { useEffect, useMemo } from 'react'
import { useParams } from 'react-router-dom'
import { useLoadStoreAttends, useStoreAttends } from 'store/storeAttends'
import dayjs from 'dayjs'
import { range } from 'utils/range'
import DateAttendInfoBox from 'components/DateAttendInfoBox'
import { PageWrapper } from 'components/PageWrapper'
import { Heading } from 'rebass/styled-components'
import { useLoadStore, useStore } from 'store/store'

export default function StoreAttendsByMonth() {
    const params = useParams<{ id: string; year: string; month: string }>()
    const { id, year, month } = useMemo(() => {
        return {
            id: parseInt(params.id),
            year: parseInt(params.year),
            month: parseInt(params.month),
        }
    }, [params.id, params.month, params.year])
    const store = useStore(id)
    const loadStore = useLoadStore(id)
    const loadStoreAttends = useLoadStoreAttends({
        storeId: id,
        year: year,
        month: month,
    })
    const storeAttends = useStoreAttends({ storeId: id, year: year, month: month })
    const dayjsMonth = dayjs()
        .year(year)
        .month(month - 1)
    const daysInMonth = dayjsMonth.daysInMonth()
    const attendsOfDay = useMemo(
        () =>
            range(1, daysInMonth)
                .map((date) => dayjsMonth.date(date))
                .map((date) => ({
                    date,
                    attends: storeAttends?.filter((attend) => dayjs(attend.startTime).isSame(date, 'date')) ?? [],
                })),
        [dayjsMonth, daysInMonth, storeAttends]
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
                {store.storeName}の出勤情報({year}年{month}月)
            </Heading>
            {attendsOfDay.map((attend, index) => (
                <DateAttendInfoBox attends={attend.attends} date={attend.date} key={index} />
            ))}
        </PageWrapper>
    )
}
