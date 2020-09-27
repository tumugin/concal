import { PageWrapper } from 'components/PageWrapper'
import { Heading } from 'rebass/styled-components'
import React, { useEffect, useState } from 'react'
import { useApiToken } from 'store/user'
import { getStores, StoreData } from 'api/admin/store'

export function AdminStores() {
    const apiToken = useApiToken()
    const [storeData, setStoreData] = useState<StoreData[]>([])
    const [totalPages, setTotalPages] = useState(0)
    const [page, setPage] = useState(1)

    useEffect(() => {
        if (!apiToken) {
            return
        }
        ;(async () => {
            const apiResult = await getStores({ apiToken }, { page })
            setStoreData(apiResult.stores)
            setTotalPages(apiResult.pageCount)
        })()
    }, [apiToken, page])

    return (
        <PageWrapper>
            <Heading>店舗一覧</Heading>
        </PageWrapper>
    )
}
