import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import React, { useDebugValue, useEffect, useState } from 'react'
import { getUsers, UserData } from 'api/admin/users'
import { useApiToken } from 'store/user'
import { PaginationController } from 'components/PaginationController'
import { AdminBasicTable } from 'components/AdminBasicTable'
import { Link } from 'react-router-dom'
import { Badge } from 'components/Badge'

export function AdminUsers() {
    const apiToken = useApiToken()
    const [userData, setUserData] = useState<UserData[]>([])
    const [totalPages, setTotalPages] = useState(0)
    const [page, setPage] = useState(1)
    useDebugValue(userData)
    const mappedUserData = userData.map((item) => ({
        id: item.id,
        userName: item.userName,
        name: item.name,
        email: item.email,
        userPrivilege:
            item.userPrivilege === 'admin' ? (
                <Badge type="danger">特権ユーザ</Badge>
            ) : (
                <Badge type="success">一般ユーザ</Badge>
            ),
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
                <Link to={`/admin/users/new`}>
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
