import { useHistory, useParams } from 'react-router-dom'
import { useApiToken } from 'admin/store/user'
import React, { useCallback, useEffect, useState } from 'react'
import { unreachableCode } from 'types/util'
import { deleteStore, getStore, StoreData, updateStore } from 'admin/api/store'
import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Heading } from 'rebass/styled-components'
import { AdminInfoBoxWrapper } from 'admin/components/AdminInfoBoxWrapper'
import { AdminInfoBox } from 'admin/components/AdminInfoBox'
import { AdminInfoGrid } from 'admin/components/AdminInfoGrid'
import { Input, Label } from '@rebass/forms/styled-components'
import { BootstrapLikeColors } from 'utils/bootstrapLike'
import toastr from 'toastr'
import Swal from 'sweetalert2'
import { Badge } from 'components/Badge'

export default function ManageStore() {
    const history = useHistory()
    const apiToken = useApiToken()
    const [isLoading, setIsLoading] = useState(false)
    const { id } = useParams<{ id: string }>()
    const [storeData, setStoreData] = useState<StoreData | null>(null)

    const [storeName, setStoreName] = useState('')

    const fetchPageData = useCallback(
        async (id: string) => {
            const result = await getStore({ apiToken: apiToken ?? unreachableCode() }, { storeId: parseInt(id) })
            setStoreData(result.store)
            setStoreName(result.store.storeName)
        },
        [apiToken]
    )

    const updateStoreInfo = useCallback(async () => {
        if (!storeData) {
            unreachableCode()
        }
        setIsLoading(true)
        try {
            await updateStore(
                { apiToken: apiToken ?? unreachableCode() },
                {
                    storeId: storeData.id,
                    storeName: storeName,
                    storeGroupId: storeData.storeGroupId,
                    storeDisabled: false,
                }
            )
            await fetchPageData(id)
            toastr.success('更新しました')
        } catch {
            await Swal.fire('エラー', 'APIエラーが発生しました', 'error')
        }
        setIsLoading(false)
    }, [apiToken, fetchPageData, id, storeData, storeName])

    const onDeleteStore = useCallback(async () => {
        const dialogResult = await Swal.fire({
            icon: 'question',
            title: '本当に店舗を削除しますか？',
            text: '閉店の場合は閉店処理を使用することをおすすめします。',
            showCancelButton: true,
            showConfirmButton: true,
        })
        if (dialogResult.isConfirmed) {
            try {
                await deleteStore(
                    { apiToken: apiToken ?? unreachableCode() },
                    {
                        storeId: storeData?.id ?? unreachableCode(),
                    }
                )
                history.push('/admin/stores')
                toastr.success('削除しました')
            } catch {
                await Swal.fire('エラー', 'APIエラーが発生しました', 'error')
            }
        }
    }, [apiToken, history, storeData?.id])

    const onToggleDisableStore = useCallback(async () => {
        const dialogResult = await Swal.fire({
            icon: 'question',
            title: '本当に店舗の開店状態を切り替えますか？',
            showCancelButton: true,
            showConfirmButton: true,
        })
        if (storeData && dialogResult.isConfirmed) {
            try {
                await updateStore(
                    { apiToken: apiToken ?? unreachableCode() },
                    {
                        storeId: storeData.id,
                        storeName: storeName,
                        storeGroupId: storeData.storeGroupId,
                        storeDisabled: !storeData.storeDisabled,
                    }
                )
                await fetchPageData(id)
                toastr.success('状態を変更しました')
            } catch {
                await Swal.fire('エラー', 'APIエラーが発生しました', 'error')
            }
        }
    }, [apiToken, fetchPageData, id, storeData, storeName])

    useEffect(() => {
        if (apiToken) {
            void fetchPageData(id)
        }
    }, [apiToken, fetchPageData, id])

    if (!storeData) {
        return null
    }

    return (
        <PageWrapper>
            <Heading>店舗管理</Heading>
            <AdminInfoBoxWrapper>
                <AdminInfoBox header="店舗情報">
                    <AdminInfoGrid
                        data={[
                            {
                                name: '店舗名',
                                value: storeData.storeName,
                            },
                            {
                                name: '店舗グループ',
                                value: storeData.storeGroup.groupName,
                            },
                            {
                                name: '店舗状態',
                                value: storeData.storeDisabled ? (
                                    <Badge type="danger">閉店中</Badge>
                                ) : (
                                    <Badge type="success">開店中</Badge>
                                ),
                            },
                            {
                                name: '店舗ID',
                                value: storeData.id,
                            },
                            {
                                name: '店舗グループID',
                                value: storeData.storeGroup.id,
                            },
                        ]}
                    />
                </AdminInfoBox>
                <AdminInfoBox header="店舗情報変更">
                    <Label>店舗名</Label>
                    <Input
                        placeholder="王立アフィリア・クロニクルS"
                        value={storeName}
                        onChange={(event) => setStoreName(event.target.value)}
                        disabled={isLoading}
                        width={500}
                        maxWidth={'100%'}
                    />
                    <Button mt={3} onClick={updateStoreInfo}>
                        変更を反映する
                    </Button>
                </AdminInfoBox>
                <AdminInfoBox header="DANGER ZONE" type="danger">
                    <Button bg={BootstrapLikeColors.alert} onClick={onToggleDisableStore}>
                        店舗を閉店・開店させる
                    </Button>
                    <Box marginY={2}>
                        ※閉店した場合、店舗一覧から表示されなくなり、以前のURLを閲覧すると閉店状態で表示されます
                    </Box>
                    <Button bg={BootstrapLikeColors.danger} onClick={onDeleteStore}>
                        店舗を削除する
                    </Button>
                    <Box marginY={2}>※店舗を完全に消去します。この操作は元に戻せません。</Box>
                </AdminInfoBox>
            </AdminInfoBoxWrapper>
        </PageWrapper>
    )
}
