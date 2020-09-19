import { PageWrapper } from 'components/PageWrapper'
import { Box, Heading } from 'rebass/styled-components'
import React, { useDebugValue, useEffect, useState } from 'react'
import { getUsers, UserData } from 'api/admin/users'
import { useApiToken } from 'store/user'
import { PaginationController } from 'components/PaginationController'
import { AdminBasicTable } from 'components/AdminBasicTable'

export function AdminUsers() {
    const apiToken = useApiToken()
    const [userData, setUserData] = useState<UserData[]>([])
    const [totalPages, setTotalPages] = useState(0)
    const [page, setPage] = useState(1)
    useDebugValue(userData)

    useEffect(() => {
        if (!apiToken) {
            return
        }
        ;(async () => {
            const apiResult = await getUsers({ apiToken }, { page })
            setUserData(apiResult.users)
            setTotalPages(apiResult.pageCount)
        })()
    }, [apiToken, page])

    return (
        <PageWrapper>
            <Heading>ユーザ一覧</Heading>
            <AdminBasicTable
                columns={[
                    {
                        Header: '名前',
                        accessor: 'name',
                    },
                ]}
            />
            <Box mt={4}>
                <PaginationController currentPage={page} totalPages={totalPages} onPageChange={setPage} />
            </Box>
        </PageWrapper>
    )
}
