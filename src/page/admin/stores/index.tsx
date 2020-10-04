import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Heading } from 'rebass/styled-components'
import React, { useEffect, useState } from 'react'
import { useApiToken } from 'store/user'
import { getStores, StoreData } from 'api/admin/store'
import { AdminBasicTable } from 'components/AdminBasicTable'
import { PaginationController } from 'components/PaginationController'
import { Link } from 'react-router-dom'

export function AdminStores() {
    const apiToken = useApiToken()
    const [storeData, setStoreData] = useState<StoreData[]>([])
    const [totalPages, setTotalPages] = useState(0)
    const [page, setPage] = useState(1)

    const mappedStoreData = storeData.map((item) => ({
        id: item.id,
        storeName: item.storeName,
        storeGroupName: item.storeGroup.groupName,
    }))

    const createOperationNode = (item: { id: number }) => {
        return (
            <Link to={`/admin/stores/${item.id}`}>
                <Button variant="outline">管理</Button>
            </Link>
        )
    }

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
            <Box mt={4}>
                <AdminBasicTable
                    columns={[
                        {
                            Header: '店舗名',
                            accessor: 'storeName',
                            width: 250,
                        },
                        {
                            Header: '店舗グループ',
                            accessor: 'storeGroupName',
                            width: 250,
                        },
                    ]}
                    data={mappedStoreData}
                    operationNode={createOperationNode}
                />
            </Box>
            <Box mt={4}>
                <PaginationController currentPage={page} totalPages={totalPages} onPageChange={setPage} />
            </Box>
        </PageWrapper>
    )
}
