import { useHistory } from 'react-router-dom'
import { useApiToken } from 'admin/store/user'
import React, { useCallback, useState } from 'react'
import { unreachableCode } from 'types/util'
import toastr from 'toastr'
import Swal from 'sweetalert2'
import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import { AdminInfoBox } from 'admin/components/AdminInfoBox'
import { Input, Label } from '@rebass/forms/styled-components'
import { Textarea } from '@rebass/forms'
import { addCast } from 'admin/api/casts'

export default function CreateCast() {
    const history = useHistory()
    const apiToken = useApiToken()
    const [isLoading, setIsLoading] = useState(false)

    const [castName, setCastName] = useState('')
    const [castShortName, setCastShortName] = useState('')
    const [castTwitterId, setCastTwitterId] = useState('')
    const [castDescription, setCastDescription] = useState('')
    const [castColor, setCastColor] = useState('')

    const resetAll = useCallback(() => {
        setCastName('')
        setCastShortName('')
        setCastTwitterId('')
        setCastDescription('')
        setCastColor('')
    }, [])

    const onCreateCast = useCallback(
        async (moveToGroupsPage: boolean) => {
            setIsLoading(true)
            try {
                const result = await addCast(
                    { apiToken: apiToken ?? unreachableCode() },
                    {
                        castName,
                        castShortName,
                        castTwitterId,
                        castDescription,
                        castColor,
                    }
                )
                toastr.success('キャストを登録しました')
                if (moveToGroupsPage) {
                    history.push(`/admin/casts/${result.id}`)
                }
            } catch {
                await Swal.fire('エラー', 'キャストを登録できませんでした。', 'error')
            } finally {
                setIsLoading(false)
            }
        },
        [apiToken, castColor, castDescription, castName, castShortName, castTwitterId, history]
    )

    return (
        <PageWrapper>
            <Heading mb={4}>キャスト新規作成</Heading>
            <AdminInfoBox header="キャスト情報入力フォーム">
                <Box>
                    <Label>キャスト名(必須)</Label>
                    <Input
                        placeholder="ウジュ・マッチャ・ミルク"
                        value={castName}
                        onChange={(event) => setCastName(event.target.value)}
                        disabled={isLoading}
                        width={500}
                        maxWidth="100%"
                    />
                    <Label>キャスト省略名称</Label>
                    <Input
                        placeholder="ウジュ"
                        value={castShortName}
                        onChange={(event) => setCastShortName(event.target.value)}
                        disabled={isLoading}
                        width={500}
                        maxWidth="100%"
                    />
                    <Label>TwitterID</Label>
                    <Input
                        placeholder="uju_afilia"
                        value={castTwitterId}
                        onChange={(event) => setCastTwitterId(event.target.value)}
                        disabled={isLoading}
                        width={500}
                        maxWidth="100%"
                    />
                    <Label>キャスト説明文</Label>
                    <Textarea
                        placeholder="水色とダンスが好きです。マッチャミルクって美味しいよね。"
                        value={castDescription}
                        onChange={(event) => setCastDescription(event.target.value)}
                        disabled={isLoading}
                        width={500}
                        maxWidth="100%"
                    />
                    <Label>キャストイメージカラー(16進数カラーコード)</Label>
                    <Input
                        placeholder="#00bfff"
                        value={castColor}
                        onChange={(event) => setCastColor(event.target.value)}
                        disabled={isLoading}
                        width={500}
                        maxWidth="100%"
                    />
                </Box>
                <Box mt={4}>
                    <Button variant="outline" onClick={resetAll}>
                        入力データ消去
                    </Button>
                </Box>
                <Flex mt={4}>
                    <Button onClick={() => onCreateCast(true)}>登録する</Button>
                    <Button ml={2} onClick={() => onCreateCast(false)}>
                        登録する(続けて入力)
                    </Button>
                </Flex>
            </AdminInfoBox>
        </PageWrapper>
    )
}
