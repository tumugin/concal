import { useApiToken } from 'admin/store/user'
import React, { useCallback, useState } from 'react'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import { PageWrapper } from 'components/PageWrapper'
import { Input, Label } from '@rebass/forms/styled-components'
import { Radio } from '@rebass/forms'
import { Note } from 'components/Note'
import Generate from 'generate-password'
import { addUser } from 'admin/api/users'
import { unreachableCode } from 'types/util'
import { useHistory } from 'react-router-dom'
import toastr from 'toastr'
import Swal from 'sweetalert2'
import { AdminInfoBox } from 'admin/components/AdminInfoBox'

export default function CreateUser() {
    const history = useHistory()
    const apiToken = useApiToken()
    const [isLoading, setIsLoading] = useState(false)
    const [email, setEmail] = useState('')
    const [userName, setUserName] = useState('')
    const [name, setName] = useState('')
    const [password, setPassword] = useState('')
    const [userPrivilege, setUserPrivilege] = useState<'user'>('user')

    const resetAll = useCallback(() => {
        setEmail('')
        setUserName('')
        setName('')
        setPassword('')
        setUserPrivilege('user')
    }, [])

    const onGeneratePassword = useCallback(() => {
        setPassword(
            Generate.generate({
                length: 30,
                numbers: true,
                lowercase: true,
                uppercase: true,
                excludeSimilarCharacters: true,
            })
        )
    }, [])

    const onCreateUser = useCallback(
        async (moveToUsersPage: boolean) => {
            setIsLoading(true)
            try {
                const result = await addUser(
                    { apiToken: apiToken ?? unreachableCode() },
                    {
                        userName,
                        name,
                        password,
                        email,
                        userPrivilege,
                    }
                )
                toastr.success('ユーザを登録しました')
                if (moveToUsersPage) {
                    history.push(`/admin/users/${result.id}`)
                }
            } catch {
                await Swal.fire('エラー', 'ユーザを登録できませんでした。', 'error')
            } finally {
                setIsLoading(false)
            }
        },
        [apiToken, email, history, name, password, userName, userPrivilege]
    )

    return (
        <PageWrapper>
            <Heading mb={4}>ユーザ新規作成</Heading>
            <AdminInfoBox header="ユーザ情報入力フォーム">
                <Box>
                    <Label>メールアドレス</Label>
                    <Input
                        type="email"
                        placeholder="yuno@example.com"
                        value={email}
                        onChange={(event) => setEmail(event.target.value)}
                        disabled={isLoading}
                        width={500}
                        maxWidth={'100%'}
                    />
                    <Label mt={2}>ユーザID</Label>
                    <Input
                        placeholder="yuno_afilia"
                        value={userName}
                        onChange={(event) => setUserName(event.target.value)}
                        disabled={isLoading}
                        width={500}
                        maxWidth={'100%'}
                    />
                    <Label mt={2}>名前</Label>
                    <Input
                        placeholder="ユノ・デ・ココ"
                        value={name}
                        onChange={(event) => setName(event.target.value)}
                        disabled={isLoading}
                        width={500}
                        maxWidth={'100%'}
                    />
                    <Label mt={2}>パスワード</Label>
                    <Input
                        placeholder="yu_no_de_co_co"
                        value={password}
                        onChange={(event) => setPassword(event.target.value)}
                        disabled={isLoading}
                        width={500}
                        maxWidth={'100%'}
                    />
                    <Button mt={1} onClick={onGeneratePassword}>
                        ランダムなパスワードを生成
                    </Button>
                    <Note tight>あとから確認できないので注意！！！！必ずメモしておく！！！</Note>
                    <Label mt={2}>ユーザ権限</Label>
                    <Flex css={{ width: 'fit-content' }}>
                        <Label>
                            <Radio
                                name="userPrivilege"
                                onChange={() => setUserPrivilege('user')}
                                checked={userPrivilege === 'user'}
                            />
                            一般ユーザ
                        </Label>
                    </Flex>
                </Box>
                <Box mt={4}>
                    <Button variant="outline" onClick={resetAll}>
                        入力データ消去
                    </Button>
                </Box>
                <Flex mt={4}>
                    <Button onClick={() => onCreateUser(true)}>登録する</Button>
                    <Button ml={2} onClick={() => onCreateUser(false)}>
                        登録する(続けて入力)
                    </Button>
                </Flex>
            </AdminInfoBox>
        </PageWrapper>
    )
}
