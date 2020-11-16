import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import React, { useEffect, useState } from 'react'
import { useApiToken } from 'admin/store/user'
import { getStoreGroups, StoreGroupData } from 'admin/api/storeGroup'
import { Link } from 'react-router-dom'
import { AdminBasicTable } from 'admin/components/AdminBasicTable'
import { PaginationController } from 'components/PaginationController'

export default function AdminGroups() {
    const apiToken = useApiToken()
    const [storeGroups, setStoreGroups] = useState<StoreGroupData[]>([])
    const [totalPages, setTotalPages] = useState(0)
    const [page, setPage] = useState(1)

    const mappedStoreGroups = storeGroups.map((item) => ({
        id: item.id,
        groupName: item.groupName,
    }))

    const createOperationNode = (item: { id: number }) => {
        return (
            <Link to={`/admin/groups/${item.id}`}>
                <Button variant="outline">管理</Button>
            </Link>
        )
    }

    useEffect(() => {
        if (!apiToken) {
            return
        }
        ;(async () => {
            const apiResult = await getStoreGroups({ apiToken }, { page })
            setStoreGroups(apiResult.storeGroups)
            setTotalPages(apiResult.pageCount)
        })()
    }, [apiToken, page])

    return (
        <PageWrapper>
            <Heading>店舗グループ一覧</Heading>
            <Flex mt={4}>
                <Link to={`/admin/groups/create`}>
                    <Button>新規追加</Button>
                </Link>
            </Flex>
            <Box mt={4}>
                <AdminBasicTable
                    columns={[
                        {
                            Header: '店舗グループ名',
                            accessor: 'groupName',
                            width: 250,
                        },
                    ]}
                    data={mappedStoreGroups}
                    operationNode={createOperationNode}
                />
            </Box>
            <Box mt={4}>
                <PaginationController currentPage={page} totalPages={totalPages} onPageChange={setPage} />
            </Box>
        </PageWrapper>
    )
}
