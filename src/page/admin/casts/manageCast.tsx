import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Flex, Heading, Link as RebassLink } from 'rebass/styled-components'
import React, { useCallback, useEffect, useState } from 'react'
import { unreachableCode } from 'types/util'
import { useApiToken } from 'store/user'
import { CastData, deleteCast, getCast, updateCast } from 'api/admin/casts'
import { useHistory, useParams } from 'react-router-dom'
import { AdminInfoBox } from 'components/AdminInfoBox'
import { AdminInfoGrid } from 'components/AdminInfoGrid'
import { AdminInfoBoxWrapper, AdminVerticalButtonLink, AdminVerticalButtons } from 'components/AdminInfoBoxWrapper'
import { Badge } from 'components/Badge'
import { Input, Label } from '@rebass/forms/styled-components'
import { Textarea } from '@rebass/forms'
import { BootstrapLikeColors } from 'utils/bootstrapLike'
import { Note } from 'components/Note'
import Swal from 'sweetalert2'
import { CastColorBlock } from 'components/CastColorBlock'
import toastr from 'toastr'
import { RebassRouterLink } from 'components/RebassRouterLink'

export default function ManageCast() {
    const history = useHistory()
    const { id } = useParams<{ id: string }>()
    const apiToken = useApiToken()
    const [castData, setCastData] = useState<CastData | null>(null)

    const [castName, setCastName] = useState('')
    const [castShortName, setCastShortName] = useState('')
    const [castTwitterId, setCastTwitterId] = useState('')
    const [castDescription, setCastDescription] = useState('')
    const [castColor, setCastColor] = useState('')

    const fetchPageData = useCallback(
        async (id: string) => {
            const result = await getCast({ apiToken: apiToken ?? unreachableCode() }, { castId: parseInt(id) })
            setCastData(result.cast)
            setCastName(result.cast.castName)
            setCastShortName(result.cast.castShortName ?? '')
            setCastTwitterId(result.cast.castTwitterId ?? '')
            setCastDescription(result.cast.castDescription)
            setCastColor(result.cast.castColor ?? '')
        },
        [apiToken]
    )

    const updateCastData = useCallback(async () => {
        await updateCast(
            { apiToken: apiToken ?? unreachableCode() },
            {
                castColor,
                castDescription,
                castId: castData?.id ?? unreachableCode(),
                castName,
                castShortName,
                castTwitterId,
            }
        )
        await fetchPageData(id)
        toastr.success('更新しました')
    }, [apiToken, castColor, castData?.id, castDescription, castName, castShortName, castTwitterId, fetchPageData, id])
    const toggleCastStatus = useCallback(async () => {
        if (!castData) {
            return
        }
        const dialogResult = await Swal.fire({
            icon: 'question',
            title: '本当にキャストを卒業させますか？',
            showCancelButton: true,
            showConfirmButton: true,
        })
        if (dialogResult.isConfirmed) {
            await updateCast(
                { apiToken: apiToken ?? unreachableCode() },
                {
                    ...castData,
                    castId: castData.id,
                    castDisabled: !castData.castDisabled,
                }
            )
            await fetchPageData(id)
            toastr.success('更新しました')
        }
    }, [apiToken, castData, fetchPageData, id])
    const onDeleteCast = useCallback(async () => {
        const dialogResult = await Swal.fire({
            icon: 'question',
            title: '本当にキャストを削除しますか？',
            showCancelButton: true,
            showConfirmButton: true,
        })
        if (dialogResult.isConfirmed) {
            await deleteCast({ apiToken: apiToken ?? unreachableCode() }, { castId: castData?.id ?? unreachableCode() })
            history.push('/admin/casts')
            toastr.success('キャストを削除しました')
        }
    }, [apiToken, castData?.id, history])

    useEffect(() => {
        if (apiToken) {
            void fetchPageData(id)
        }
    }, [apiToken, fetchPageData, id])

    if (!castData) {
        return null
    }

    return (
        <PageWrapper>
            <Heading>キャスト管理</Heading>
            <AdminInfoBoxWrapper>
                <AdminInfoBox header="キャスト情報">
                    <AdminInfoGrid
                        data={[
                            {
                                name: 'ID',
                                value: castData.id,
                            },
                            {
                                name: '名前',
                                value: castData.castName,
                            },
                            {
                                name: 'キャスト省略名称',
                                value: castData.castShortName ?? '未登録',
                            },
                            {
                                name: '在籍店舗',
                                value: castData.stores.map((store, index) => (
                                    <div key={index}>
                                        <RebassRouterLink to={`/admin/stores/${store.id}`}>
                                            {store.storeName}
                                        </RebassRouterLink>
                                    </div>
                                )),
                            },
                            {
                                name: 'Twitter',
                                value: castData.castTwitterId ? (
                                    <RebassLink href={`https://twitter.com/${castData.castTwitterId}`} target="_blank">
                                        {castData.castTwitterId}
                                    </RebassLink>
                                ) : (
                                    '未登録'
                                ),
                            },
                            {
                                name: 'キャストイメージカラー',
                                value: castData.castColor ? (
                                    <Flex sx={{ alignItems: 'center' }}>
                                        <CastColorBlock color={castData.castColor} />
                                        <Box marginLeft={2}>{castData.castColor}</Box>
                                    </Flex>
                                ) : (
                                    '未登録'
                                ),
                            },
                            {
                                name: 'キャスト登録状態',
                                value: castData.castDisabled ? (
                                    <Badge type="alert">卒業済み</Badge>
                                ) : (
                                    <Badge type="success">現役</Badge>
                                ),
                            },
                            {
                                name: '説明文',
                                value: castData.castDescription,
                            },
                        ]}
                    />
                    <AdminVerticalButtons mt={3}>
                        <AdminVerticalButtonLink to={`/admin/casts/${id}/attends`}>
                            <Button>このキャストの出勤を管理する</Button>
                        </AdminVerticalButtonLink>
                        <AdminVerticalButtonLink to={`/admin/casts/${id}/stores`}>
                            <Button>このキャストの在籍店舗を管理する</Button>
                        </AdminVerticalButtonLink>
                    </AdminVerticalButtons>
                </AdminInfoBox>
                <AdminInfoBox header="キャスト情報変更">
                    <Box>
                        <Label>キャスト名(必須)</Label>
                        <Input
                            placeholder="ウジュ・マッチャ・ミルク"
                            value={castName}
                            onChange={(event) => setCastName(event.target.value)}
                            width={500}
                            maxWidth="100%"
                        />
                        <Label>キャスト省略名称</Label>
                        <Input
                            placeholder="ウジュ"
                            value={castShortName}
                            onChange={(event) => setCastShortName(event.target.value)}
                            width={500}
                            maxWidth="100%"
                        />
                        <Label>TwitterID</Label>
                        <Input
                            placeholder="uju_afilia"
                            value={castTwitterId}
                            onChange={(event) => setCastTwitterId(event.target.value)}
                            width={500}
                            maxWidth="100%"
                        />
                        <Label>キャスト説明文</Label>
                        <Textarea
                            placeholder="水色とダンスが好きです。マッチャミルクって美味しいよね。"
                            value={castDescription}
                            onChange={(event) => setCastDescription(event.target.value)}
                            width={500}
                            maxWidth="100%"
                        />
                        <Label>キャストイメージカラー(16進数カラーコード)</Label>
                        <Input
                            placeholder="#00bfff"
                            value={castColor}
                            onChange={(event) => setCastColor(event.target.value)}
                            width={500}
                            maxWidth="100%"
                        />
                    </Box>
                    <Button mt={3} onClick={updateCastData}>
                        変更を反映する
                    </Button>
                </AdminInfoBox>
                <AdminInfoBox header="DANGER ZONE" type="danger">
                    <Button bg={BootstrapLikeColors.alert} onClick={toggleCastStatus}>
                        {castData.castDisabled ? 'キャストを在籍状態に戻す' : 'キャストを卒業させる'}
                    </Button>
                    <Note tight>
                        キャスト一覧などに表示されなくなります。出勤状態などはそのまま残るため基本的にこちらを使ってください。
                    </Note>
                    <Button bg={BootstrapLikeColors.danger} onClick={onDeleteCast}>
                        キャストを削除する(元に戻せない)
                    </Button>
                    <Note tight>卒業の場合は基本的に卒業処理の方を使ってください。</Note>
                </AdminInfoBox>
            </AdminInfoBoxWrapper>
        </PageWrapper>
    )
}
