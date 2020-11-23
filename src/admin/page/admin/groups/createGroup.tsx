import { useHistory } from 'react-router-dom'
import { useApiToken } from 'admin/store/user'
import React, { useCallback, useState } from 'react'
import { unreachableCode } from 'types/util'
import toastr from 'toastr'
import Swal from 'sweetalert2'
import { addStoreGroup } from 'admin/api/storeGroup'
import { PageWrapper } from 'components/PageWrapper'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import { AdminInfoBox } from 'admin/components/AdminInfoBox'
import { Input, Label } from '@rebass/forms/styled-components'

export default function CreateGroup() {
    const history = useHistory()
    const apiToken = useApiToken()
    const [isLoading, setIsLoading] = useState(false)
    const [groupName, setGroupName] = useState('')

    const resetAll = useCallback(() => {
        setGroupName('')
    }, [])

    const onCreateStoreGroup = useCallback(
        async (moveToGroupsPage: boolean) => {
            setIsLoading(true)
            try {
                const result = await addStoreGroup(
                    { apiToken: apiToken ?? unreachableCode() },
                    {
                        groupName,
                    }
                )
                toastr.success('店舗グループを登録しました')
                if (moveToGroupsPage) {
                    history.push(`/admin/groups/${result.id}`)
                }
            } catch {
                await Swal.fire('エラー', '店舗グループを登録できませんでした。', 'error')
                return
            } finally {
                setIsLoading(false)
            }
        },
        [apiToken, groupName, history]
    )

    return (
        <PageWrapper>
            <Heading mb={4}>店舗グループ新規作成</Heading>
            <AdminInfoBox header="店舗グループ情報入力フォーム">
                <Box>
                    <Label>店舗グループ名</Label>
                    <Input
                        placeholder="アフィリア魔法学院"
                        value={groupName}
                        onChange={(event) => setGroupName(event.target.value)}
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
                    <Button onClick={() => onCreateStoreGroup(true)}>登録する</Button>
                    <Button ml={2} onClick={() => onCreateStoreGroup(false)}>
                        登録する(続けて入力)
                    </Button>
                </Flex>
            </AdminInfoBox>
        </PageWrapper>
    )
}
