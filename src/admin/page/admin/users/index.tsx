import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import React, { useDebugValue, useEffect, useState } from 'react'
import { getUsers, UserData } from 'admin/api/users'
import { useApiToken } from 'admin/store/user'
import { PaginationController } from 'components/PaginationController'
import { AdminBasicTable } from 'admin/components/AdminBasicTable'
import { Link } from 'react-router-dom'
import { Badge } from 'components/Badge'
import { useQueryNumber } from 'hooks/queryParam'

export default function AdminUsers() {
    const apiToken = useApiToken()
    const [userData, setUserData] = useState<UserData[]>([])
    const [totalPages, setTotalPages] = useState(0)
    const [page, setPage] = useQueryNumber('page', 1)
    useDebugValue(userData)
    const mappedUserData = userData.map((item) => ({
        id: item.id,
        userName: item.userName,
        name: item.name,
        email: item.email,
        userPrivilege: <Badge type="success">一般ユーザ</Badge>,
    }))
    const createOperationNode = (item: { id: number }) => {
        return (
            <Link to={`/admin/users/${item.id}`}>
                <Button variant="outline">管理</Button>
            </Link>
        )
    }

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
            <Flex mt={4}>
                <Link to={`/admin/users/create`}>
                    <Button>新規追加</Button>
                </Link>
            </Flex>
            <Box mt={4}>
                <AdminBasicTable
                    columns={[
                        {
                            Header: '名前',
                            accessor: 'name',
                            width: 250,
                        },
                        {
                            Header: 'ユーザID',
                            accessor: 'userName',
                            width: 200,
                        },
                        {
                            Header: 'メールアドレス',
                            accessor: 'email',
                            width: 300,
                        },
                        {
                            Header: 'ユーザ権限',
                            accessor: 'userPrivilege',
                        },
                    ]}
                    data={mappedUserData}
                    operationNode={createOperationNode}
                />
            </Box>
            <Box mt={4}>
                <PaginationController currentPage={page} totalPages={totalPages} onPageChange={setPage} />
            </Box>
        </PageWrapper>
    )
}
