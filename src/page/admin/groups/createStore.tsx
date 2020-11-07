import { useHistory, useParams } from 'react-router-dom'
import { useApiToken } from 'store/user'
import React, { useCallback, useState } from 'react'
import { unreachableCode } from 'types/util'
import toastr from 'toastr'
import Swal from 'sweetalert2'
import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import { AdminInfoBox } from 'components/AdminInfoBox'
import { Input, Label } from '@rebass/forms/styled-components'
import { addStore } from 'api/admin/store'

export default function CreateStore() {
    const history = useHistory()
    const apiToken = useApiToken()
    const { id } = useParams<{ id: string }>()
    const [isLoading, setIsLoading] = useState(false)
    const [storeName, setStoreName] = useState('')

    const resetAll = useCallback(() => {
        setStoreName('')
    }, [])

    const onCreateStore = useCallback(
        async (moveToGroupsPage: boolean) => {
            setIsLoading(true)
            try {
                await addStore(
                    { apiToken: apiToken ?? unreachableCode() },
                    {
                        storeName,
                        storeGroupId: parseInt(id),
                    }
                )
                toastr.success('店舗を登録しました')
            } catch {
                await Swal.fire('エラー', '店舗を登録できませんでした。', 'error')
                return
            }
            setIsLoading(false)
            if (moveToGroupsPage) {
                history.push('/admin/stores')
            }
        },
        [apiToken, history, id, storeName]
    )

    return (
        <PageWrapper>
            <Heading mb={4}>店舗新規作成</Heading>
            <AdminInfoBox header="店舗情報入力フォーム">
                <Box>
                    <Label>店舗名</Label>
                    <Input
                        placeholder="王立アフィリア・ブルジュール"
                        value={storeName}
                        onChange={(event) => setStoreName(event.target.value)}
                        disabled={isLoading}
                        width={500}
                        maxWidth={'100%'}
                    />
                </Box>
                <Box mt={4}>
                    <Button variant="outline" onClick={resetAll}>
                        入力データ消去
                    </Button>
                </Box>
                <Flex mt={4}>
                    <Button onClick={() => onCreateStore(true)}>登録する</Button>
                    <Button ml={2} onClick={() => onCreateStore(false)}>
                        登録する(続けて入力)
                    </Button>
                </Flex>
            </AdminInfoBox>
        </PageWrapper>
    )
}
