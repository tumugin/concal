import { useApiToken } from 'admin/store/user'
import React, { useDebugValue, useEffect, useState } from 'react'
import { Badge } from 'components/Badge'
import { Link } from 'react-router-dom'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import { PageWrapper } from 'components/PageWrapper'
import { AdminBasicTable } from 'admin/components/AdminBasicTable'
import { PaginationController } from 'components/PaginationController'
import { AdminUserData, getAdminUsers } from 'admin/api/admin-users'

export default function AdminAdminUsers() {
    const apiToken = useApiToken()
    const [userData, setUserData] = useState<AdminUserData[]>([])
    const [totalPages, setTotalPages] = useState(0)
    const [page, setPage] = useState(1)
    useDebugValue(userData)
    const mappedUserData = userData.map((item) => ({
        id: item.id,
        userName: item.userName,
        name: item.name,
        email: item.email,
        userPrivilege:
            item.userPrivilege === 'super_admin' ? (
                <Badge type="danger">特権管理者ユーザ</Badge>
            ) : (
                <Badge type="success">管理者ユーザ</Badge>
            ),
    }))
    const createOperationNode = (item: { id: number }) => {
        return (
            <Link to={`/admin/admin_users/${item.id}`}>
                <Button variant="outline">管理</Button>
            </Link>
        )
    }

    useEffect(() => {
        if (!apiToken) {
            return
        }
        ;(async () => {
            const apiResult = await getAdminUsers({ apiToken }, { page })
            setUserData(apiResult.users)
            setTotalPages(apiResult.pageCount)
        })()
    }, [apiToken, page])

    return (
        <PageWrapper>
            <Heading>管理者ユーザ一覧</Heading>
            <Flex mt={4}>
                <Link to={`/admin/admin_users/create`}>
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
