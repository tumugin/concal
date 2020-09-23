import { useHistory, useParams } from 'react-router-dom'
import { useApiToken } from 'store/user'
import React, { useCallback, useEffect, useState } from 'react'
import { unreachableCode } from 'types/util'
import toastr from 'toastr'
import Swal from 'sweetalert2'
import { PageWrapper } from 'components/PageWrapper'
import { Button, Heading } from 'rebass/styled-components'
import { AdminInfoBoxWrapper } from 'components/AdminInfoBoxWrapper'
import { AdminInfoBox } from 'components/AdminInfoBox'
import { AdminInfoGrid } from 'components/AdminInfoGrid'
import { Input, Label } from '@rebass/forms/styled-components'
import { Note } from 'components/Note'
import { BootstrapLikeColors } from 'utils/bootstrapLike'
import { deleteStoreGroup, getStoreGroup, StoreGroupData, updateStoreGroup } from 'api/admin/storeGroup'

export function ManageGroup() {
    const history = useHistory()
    const apiToken = useApiToken()
    const [isLoading, setIsLoading] = useState(false)
    const { id } = useParams<{ id: string }>()
    const [storeGroupData, setStoreGroupData] = useState<StoreGroupData | null>(null)

    const [storeGroupName, setStoreGroupName] = useState('')

    const fetchPageData = useCallback(
        async (id: string) => {
            const result = await getStoreGroup(
                { apiToken: apiToken ?? unreachableCode() },
                { storeGroupId: parseInt(id) }
            )
            setStoreGroupData(result.storeGroup)
            setStoreGroupName(result.storeGroup.groupName)
        },
        [apiToken]
    )

    const updateUserBasicInfo = useCallback(async () => {
        setIsLoading(true)
        try {
            await updateStoreGroup(
                { apiToken: apiToken ?? unreachableCode() },
                { storeGroupId: storeGroupData?.id ?? unreachableCode(), groupName: storeGroupName }
            )
            await fetchPageData(id)
            toastr.success('更新しました')
        } catch {
            await Swal.fire('エラー', 'APIエラーが発生しました', 'error')
        }
        setIsLoading(false)
    }, [apiToken, fetchPageData, id, storeGroupData?.id, storeGroupName])
    const deleteStoreGroupConfirmAndDelete = useCallback(async () => {
        setIsLoading(true)
        const dialogResult = await Swal.fire({
            icon: 'question',
            title: '本当に店舗グループを削除しますか？',
            text: '※在籍している店舗がある場合のみ削除することが出来ます',
            showCancelButton: true,
            showConfirmButton: true,
        })
        if (dialogResult.isConfirmed) {
            try {
                await deleteStoreGroup(
                    { apiToken: apiToken ?? unreachableCode() },
                    { storeGroupId: storeGroupData?.id ?? unreachableCode() }
                )
                history.push('/admin/groups')
                toastr.success('店舗グループを削除しました')
            } catch {
                await Swal.fire('エラー', 'APIエラーが発生しました', 'error')
            }
        }
        setIsLoading(false)
    }, [apiToken, history, storeGroupData?.id])

    useEffect(() => {
        if (apiToken) {
            void fetchPageData(id)
        }
    }, [apiToken, fetchPageData, id])

    if (!storeGroupData) {
        return null
    }

    return (
        <PageWrapper>
            <Heading>店舗グループ管理</Heading>
            <AdminInfoBoxWrapper>
                <AdminInfoBox header="店舗グループ情報">
                    <AdminInfoGrid
                        data={[
                            {
                                name: '名前',
                                value: storeGroupData.groupName,
                            },
                        ]}
                    />
                </AdminInfoBox>
                <AdminInfoBox header="店舗グループ情報変更">
                    <Label>店舗グループ名</Label>
                    <Input
                        placeholder="めいどりーみん"
                        value={storeGroupName}
                        onChange={(event) => setStoreGroupName(event.target.value)}
                        disabled={isLoading}
                        width={500}
                        maxWidth={'100%'}
                    />
                    <Button mt={3} onClick={updateUserBasicInfo}>
                        変更を反映する
                    </Button>
                </AdminInfoBox>
                <AdminInfoBox header="DANGER ZONE" type="danger">
                    <Button bg={BootstrapLikeColors.danger} onClick={deleteStoreGroupConfirmAndDelete}>
                        店舗グループを削除する
                    </Button>
                    <Note tight>在籍店舗が0店舗の場合のみ削除できます</Note>
                </AdminInfoBox>
            </AdminInfoBoxWrapper>
        </PageWrapper>
    )
}
