import { AdminBasicTable } from 'components/AdminBasicTable'
import React from 'react'
import { StoreData } from 'api/admin/store'
import { Box, Button } from 'rebass/styled-components'
import { PaginationController } from 'components/PaginationController'
import { unreachableCode } from 'types/util'

export function AdminStoreSelector({
    storeData,
    onStoreSelect,
    page,
    totalPages,
    setPage,
}: {
    storeData: StoreData[]
    onStoreSelect: (store: StoreData) => void
    page: number
    totalPages: number
    setPage: (pageNumber: number) => void
}) {
    const mappedStoreData = storeData.map((item) => ({
        id: item.id,
        storeName: item.storeName,
        storeGroupName: item.storeGroup.groupName,
    }))

    const createOperationNode = ({ id }: { id: number }) => {
        return (
            <Button
                variant="outline"
                onClick={() => onStoreSelect(storeData.find((item) => item.id === id) ?? unreachableCode())}
            >
                追加
            </Button>
        )
    }

    return (
        <>
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
            <Box mt={2}>
                <PaginationController currentPage={page} totalPages={totalPages} onPageChange={setPage} />
            </Box>
        </>
    )
}
