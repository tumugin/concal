import { PageWrapper } from 'components/PageWrapper'
import { Heading } from 'rebass/styled-components'
import React, { useEffect, useState } from 'react'
import { useApiToken } from 'store/user'
import { CastData, getCasts } from 'api/admin/casts'

export function AdminCasts() {
    const apiToken = useApiToken()
    const [castData, setCastData] = useState<CastData[]>([])
    const [totalPages, setTotalPages] = useState(0)
    const [page, setPage] = useState(1)

    useEffect(() => {
        if (!apiToken) {
            return
        }
        ;(async () => {
            const apiResult = await getCasts({ apiToken }, { page })
            setCastData(apiResult.casts)
            setTotalPages(apiResult.pageCount)
        })()
    }, [apiToken, page])

    return (
        <PageWrapper>
            <Heading>キャスト一覧</Heading>
        </PageWrapper>
    )
}
