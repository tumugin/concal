import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import React, { useCallback, useEffect, useState } from 'react'
import { AdminInfoBoxWrapper } from 'admin/components/AdminInfoBoxWrapper'
import { AdminInfoBox } from 'admin/components/AdminInfoBox'
import { Input } from '@rebass/forms/styled-components'
import { AdminStoreSelector } from 'admin/components/AdminStoreSelector'
import { getStore, getStores, StoreData } from 'admin/api/store'
import { useParams } from 'react-router-dom'
import { useApiToken } from 'admin/store/user'
import { CastData, getCast, updateCast } from 'admin/api/casts'
import { unreachableCode } from 'types/util'
import { AdminStoresEditorListView } from 'admin/components/AdminStoresEditorListView'
import produce from 'immer'
import toastr from 'toastr'
import Swal from 'sweetalert2'

export default function ManageBelongingStores() {
    const { id } = useParams<{ id: string }>()
    const apiToken = useApiToken()
    const [draftStoreId, setDraftStoreId] = useState('')
    const [castData, setCastData] = useState<CastData | null>(null)
    const [storeList, setStoreList] = useState<{ storeName: string; storeId: number }[]>([])

    const onStoreSelect = useCallback(
        (storeData: StoreData) => {
            if (!storeList.find((store) => store.storeId === storeData.id)) {
                setStoreList((store) => [...store, { storeName: storeData.storeName, storeId: storeData.id }])
            }
        },
        [storeList]
    )

    const fetchPageData = useCallback(async () => {
        const { cast } = await getCast({ apiToken: apiToken ?? unreachableCode() }, { castId: parseInt(id) })
        setCastData(cast)
        setStoreList(cast.stores.map((store) => ({ storeName: store.storeName, storeId: store.id })))
    }, [apiToken, id])

    const onStoreDelete = useCallback((storeId: number) => {
        setStoreList((storeList) =>
            produce(storeList, (draftStoreList) => {
                draftStoreList.splice(
                    draftStoreList.findIndex((store) => store.storeId === storeId),
                    1
                )
            })
        )
    }, [])

    const applyStore = useCallback(async () => {
        if (!castData) {
            return
        }
        await updateCast(
            { apiToken: apiToken ?? unreachableCode() },
            { ...castData, castId: castData.id, storeIds: storeList.map((store) => store.storeId) }
        )
        await fetchPageData()
        toastr.success('更新しました')
    }, [apiToken, castData, fetchPageData, storeList])

    const addFromStoreId = useCallback(async () => {
        try {
            const storeData = await getStore(
                { apiToken: apiToken ?? unreachableCode() },
                { storeId: parseInt(draftStoreId) }
            )
            onStoreSelect(storeData.store)
        } catch {
            await Swal.fire('エラー', 'APIエラーが発生しました', 'error')
        }
    }, [apiToken, draftStoreId, onStoreSelect])

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
                    <Box marginBottom={3}>
                        {storeList.length > 0 && (
                            <AdminStoresEditorListView stores={storeList} onDelete={onStoreDelete} />
                        )}
                        {storeList.length === 0 && <Box>在籍店舗なし</Box>}
                    </Box>
                    <Button onClick={applyStore}>反映する</Button>
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
                        <Button marginLeft={2} onClick={addFromStoreId}>
                            追加する
                        </Button>
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
