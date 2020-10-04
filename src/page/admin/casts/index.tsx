import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Flex, Heading, Link as RebassLink } from 'rebass/styled-components'
import React, { useEffect, useState } from 'react'
import { useApiToken } from 'store/user'
import { CastData, getCasts } from 'api/admin/casts'
import { Link } from 'react-router-dom'
import { AdminBasicTable } from 'components/AdminBasicTable'
import { PaginationController } from 'components/PaginationController'
import { Badge } from 'components/Badge'

export function AdminCasts() {
    const apiToken = useApiToken()
    const [castData, setCastData] = useState<CastData[]>([])
    const [totalPages, setTotalPages] = useState(0)
    const [page, setPage] = useState(1)

    const mappedCastData = castData.map((item) => ({
        id: item.id,
        castName: item.castName,
        castShortName: item.castShortName,
        castTwitterId: item.castTwitterId ? (
            <RebassLink href={`https://twitter.com/${item.castTwitterId}`} target="_blank">
                {item.castTwitterId}
            </RebassLink>
        ) : (
            ''
        ),
        castStatus: item.castDisabled ? <Badge type="alert">卒業済み</Badge> : <Badge type="success">現役</Badge>,
        stores: item.stores.map((store, index) => <div key={index}>{store.storeName}</div>),
    }))

    const createOperationNode = (item: { id: number }) => {
        return (
            <Flex>
                <Link to={`/admin/casts/${item.id}`}>
                    <Button variant="outline">管理</Button>
                </Link>
                <Box marginLeft={1}>
                    <Link to={`/admin/casts/${item.id}/attends`}>
                        <Button variant="outline">出勤管理</Button>
                    </Link>
                </Box>
            </Flex>
        )
    }

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
            <Flex mt={4}>
                <Link to={`/admin/casts/new`}>
                    <Button>新規追加</Button>
                </Link>
            </Flex>
            <Box mt={4}>
                <AdminBasicTable
                    columns={[
                        {
                            Header: 'キャスト名',
                            accessor: 'castName',
                            width: 250,
                        },
                        {
                            Header: 'キャスト省略名称',
                            accessor: 'castShortName',
                            width: 250,
                        },
                        {
                            Header: '在籍店舗',
                            accessor: 'stores',
                            width: 250,
                        },
                        {
                            Header: 'キャストTwitter',
                            accessor: 'castTwitterId',
                            width: 200,
                        },
                        {
                            Header: '在籍状態',
                            accessor: 'castStatus',
                            width: 200,
                        },
                    ]}
                    data={mappedCastData}
                    operationNode={createOperationNode}
                />
            </Box>
            <Box mt={4}>
                <PaginationController currentPage={page} totalPages={totalPages} onPageChange={setPage} />
            </Box>
        </PageWrapper>
    )
}
