import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Heading } from 'rebass/styled-components'
import React, { useEffect, useState } from 'react'
import { useApiToken } from 'admin/store/user'
import { getStores, StoreData } from 'admin/api/store'
import { AdminBasicTable } from 'admin/components/AdminBasicTable'
import { PaginationController } from 'components/PaginationController'
import { Link } from 'react-router-dom'
import { Badge } from 'components/Badge'
import { useQueryNumber } from 'hooks/queryParam'

export default function AdminStores() {
    const apiToken = useApiToken()
    const [storeData, setStoreData] = useState<StoreData[]>([])
    const [totalPages, setTotalPages] = useState(0)
    const [page, setPage] = useQueryNumber('page', 1)
    const [storeGroupId] = useQueryNumber('storeGroupId')

    const mappedStoreData = storeData.map((item) => ({
        id: item.id,
        storeName: item.storeName,
        storeGroupName: item.storeGroup.groupName,
        storeStatus: item.storeDisabled ? <Badge type="danger">閉店中</Badge> : <Badge type="success">開店中</Badge>,
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
            const apiResult = await getStores({ apiToken }, { page, storeGroupId })
            setStoreData(apiResult.stores)
            setTotalPages(apiResult.pageCount)
        })()
    }, [apiToken, page, storeGroupId])

    return (
        <PageWrapper>
            <Heading>店舗一覧</Heading>
            <Box mt={4}>
                <AdminBasicTable
                    columns={[
                        {
                            Header: '店舗名',
                            accessor: 'storeName',
                            width: 300,
                        },
                        {
                            Header: '店舗グループ',
                            accessor: 'storeGroupName',
                            width: 250,
                        },
                        {
                            Header: '店舗状態',
                            accessor: 'storeStatus',
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
