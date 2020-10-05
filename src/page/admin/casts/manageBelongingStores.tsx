import { PageWrapper } from 'components/PageWrapper'
import { Button, Flex, Heading } from 'rebass/styled-components'
import React, { useCallback, useEffect, useState } from 'react'
import { AdminInfoBoxWrapper } from 'components/AdminInfoBoxWrapper'
import { AdminInfoBox } from 'components/AdminInfoBox'
import { Input } from '@rebass/forms/styled-components'
import { AdminStoreSelector } from 'components/AdminStoreSelector'
import { getStores, StoreData } from 'api/admin/store'
import { useParams } from 'react-router-dom'
import { useApiToken } from 'store/user'
import { CastData, getCast } from 'api/admin/casts'
import { unreachableCode } from 'types/util'

export function ManageBelongingStores() {
    const { id } = useParams<{ id: string }>()
    const apiToken = useApiToken()
    const [draftStoreId, setDraftStoreId] = useState('')
    const [castData, setCastData] = useState<CastData | null>(null)
    const [storeList, setStoreList] = useState<{ storeName: string; storeId: number }[]>([])

    const onStoreSelect = useCallback((storeData: StoreData) => {
        setStoreList((store) => [...store, { storeName: storeData.storeName, storeId: storeData.id }])
    }, [])

    const fetchPageData = useCallback(async () => {
        const { cast } = await getCast({ apiToken: apiToken ?? unreachableCode() }, { castId: parseInt(id) })
        setCastData(cast)
        setStoreList(cast.stores.map((store) => ({ storeName: store.storeName, storeId: store.id })))
    }, [apiToken, id])

    useEffect(() => {
        if (apiToken) {
            void fetchPageData()
        }
    }, [apiToken, fetchPageData])

    if (!castData) {
        return null
    }

    return (
        <PageWrapper>
            <Heading>キャスト在籍店舗管理(編集中: {castData.castName})</Heading>
            <AdminInfoBoxWrapper>
                <AdminInfoBox header="在籍店舗一覧編集">
                    <Button>反映する</Button>
                </AdminInfoBox>
                <AdminInfoBox header="店舗IDから追加">
                    <Flex>
                        <Input
                            placeholder="店舗IDをここに入力"
                            value={draftStoreId}
                            onChange={(event) => setDraftStoreId(event.target.value)}
                            width={200}
                            maxWidth="100%"
                        />
                        <Button marginLeft={2}>追加する</Button>
                    </Flex>
                </AdminInfoBox>
                <AdminInfoBox header="店舗一覧から追加する">
                    <StoreSelector onStoreSelect={onStoreSelect} />
                </AdminInfoBox>
            </AdminInfoBoxWrapper>
        </PageWrapper>
    )
}

function StoreSelector({ onStoreSelect }: { onStoreSelect: (storeId: StoreData) => void }) {
    const apiToken = useApiToken()
    const [storeData, setStoreData] = useState<StoreData[]>([])
    const [totalPages, setTotalPages] = useState(0)
    const [page, setPage] = useState(1)

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
        <AdminStoreSelector
            onStoreSelect={onStoreSelect}
            storeData={storeData}
            page={page}
            totalPages={totalPages}
            setPage={setPage}
        />
    )
}
