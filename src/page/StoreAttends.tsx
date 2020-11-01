import { PageWrapper } from 'components/PageWrapper'
import React, { useEffect, useState } from 'react'
import { Heading } from 'rebass/styled-components'
import dayjs from 'dayjs'
import { useParams } from 'react-router-dom'
import { useLoadStoreAttends, useStoreAttends } from 'store/storeAttends'

export function StoreAttends() {
    const { id } = useParams<{ id: string }>()
    const storeId = parseInt(id)
    const [year, setYear] = useState(dayjs().year())
    const [month, setMonth] = useState(dayjs().month() + 1)

    const loadStoreAttends = useLoadStoreAttends({ storeId, year, month })
    const storeAttends = useStoreAttends({ storeId, year, month })

    useEffect(() => {
        if (storeAttends === null) {
            void loadStoreAttends()
        }
    }, [loadStoreAttends, storeAttends])

    return (
        <PageWrapper>
            <Heading>店舗ごとの出勤一覧</Heading>
        </PageWrapper>
    )
}
