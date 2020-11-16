import { PageWrapper } from 'components/PageWrapper'
import { Button, Flex, Heading } from 'rebass/styled-components'
import React, { useCallback, useEffect, useState } from 'react'
import { useHistory, useParams } from 'react-router-dom'
import { deleteUser, getUser, updateUser, UserData } from 'admin/api/users'
import { useApiToken } from 'admin/store/user'
import { unreachableCode } from 'types/util'
import { AdminInfoGrid } from 'admin/components/AdminInfoGrid'
import { Badge } from 'components/Badge'
import { AdminInfoBox } from 'admin/components/AdminInfoBox'
import { AdminInfoBoxWrapper } from 'admin/components/AdminInfoBoxWrapper'
import { Input, Label } from '@rebass/forms/styled-components'
import { Note } from 'components/Note'
import { Radio } from '@rebass/forms'
import Generate from 'generate-password'
import { BootstrapLikeColors } from 'utils/bootstrapLike'
import toastr from 'toastr'
import Swal from 'sweetalert2'

export default function ManageUser() {
    const history = useHistory()
    const apiToken = useApiToken()
    const { id } = useParams<{ id: string }>()
    const [userData, setUserData] = useState<UserData | null>(null)

    const [isLoading, setIsLoading] = useState(false)
    const [email, setEmail] = useState('')
    const [userName, setUserName] = useState('')
    const [name, setName] = useState('')
    const [password, setPassword] = useState('')
    const [userPrivilege, setUserPrivilege] = useState<'user'>('user')

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

    const fetchPageData = useCallback(
        async (id: string) => {
            const result = await getUser({ apiToken: apiToken ?? unreachableCode() }, { userId: parseInt(id) })
            setUserData(result.user)
            setEmail(result.user.email)
            setUserName(result.user.userName)
            setName(result.user.name)
            setUserPrivilege(result.user.userPrivilege)
        },
        [apiToken]
    )

    const updateUserBasicInfo = useCallback(async () => {
        setIsLoading(true)
        try {
            await updateUser(
                { apiToken: apiToken ?? unreachableCode() },
                {
                    userId: userData?.id ?? unreachableCode(),
                    userName,
                    name,
                    email,
                }
            )
            await fetchPageData(id)
            toastr.success('更新しました')
        } catch {
            await Swal.fire('エラー', 'APIエラーが発生しました', 'error')
        }
        setIsLoading(false)
    }, [apiToken, email, fetchPageData, id, name, userData?.id, userName])
    const updateUserAuthInfo = useCallback(async () => {
        setIsLoading(true)
        try {
            await updateUser(
                { apiToken: apiToken ?? unreachableCode() },
                {
                    userId: userData?.id ?? unreachableCode(),
                    password,
                }
            )
            await fetchPageData(id)
            toastr.success('更新しました')
        } catch {
            await Swal.fire('エラー', 'APIエラーが発生しました', 'error')
        }
        setIsLoading(false)
    }, [apiToken, fetchPageData, id, password, userData?.id])
    const updateUserPrivilege = useCallback(async () => {
        setIsLoading(true)
        try {
            await updateUser(
                { apiToken: apiToken ?? unreachableCode() },
                {
                    userId: userData?.id ?? unreachableCode(),
                    userPrivilege,
                }
            )
            await fetchPageData(id)
            toastr.success('更新しました')
        } catch {
            await Swal.fire('エラー', 'APIエラーが発生しました', 'error')
        }
        setIsLoading(false)
    }, [apiToken, fetchPageData, id, userData?.id, userPrivilege])
    const deleteUserConfirmAndDelete = useCallback(async () => {
        setIsLoading(true)
        const dialogResult = await Swal.fire({
            icon: 'question',
            title: '本当にユーザを削除しますか？',
            text: '論理削除ではなく物理削除です。本当に消えます。',
            showCancelButton: true,
            showConfirmButton: true,
        })
        if (dialogResult.isConfirmed) {
            try {
                await deleteUser(
                    { apiToken: apiToken ?? unreachableCode() },
                    { userId: userData?.id ?? unreachableCode() }
                )
                history.push('/admin/users')
                toastr.success('ユーザを削除しました')
            } catch {
                await Swal.fire('エラー', 'APIエラーが発生しました', 'error')
            }
        }
        setIsLoading(false)
    }, [apiToken, history, userData?.id])

    useEffect(() => {
        if (apiToken) {
            void fetchPageData(id)
        }
    }, [apiToken, fetchPageData, id])

    if (!userData) {
        return null
    }

    return (
        <PageWrapper>
            <Heading>ユーザ管理</Heading>
            <AdminInfoBoxWrapper>
                <AdminInfoBox header="ユーザ情報">
                    <AdminInfoGrid
                        data={[
                            {
                                name: 'DB上のID',
                                value: userData.id,
                            },
                            {
                                name: 'ユーザID',
                                value: userData.userName,
                            },
                            {
                                name: '名前',
                                value: userData.name,
                            },
                            {
                                name: 'メールアドレス',
                                value: userData.email,
                            },
                            {
                                name: 'ユーザ権限',
                                value: <Badge type="success">一般ユーザ</Badge>,
                            },
                        ]}
                    />
                </AdminInfoBox>
                <AdminInfoBox header="ユーザ基本情報変更">
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
                    <Button mt={3} onClick={updateUserBasicInfo}>
                        変更を反映する
                    </Button>
                </AdminInfoBox>
                <AdminInfoBox header="ユーザ認証情報変更">
                    <Label>パスワード</Label>
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
                    <Button onClick={updateUserAuthInfo}>変更を反映する</Button>
                </AdminInfoBox>
                <AdminInfoBox header="ユーザレベル変更" type="danger">
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
                    <Note tight>特権ユーザとして登録すると、何でも出来るユーザとして登録されるので注意！！！！</Note>
                    <Button bg={BootstrapLikeColors.danger} onClick={updateUserPrivilege}>
                        変更を反映する
                    </Button>
                </AdminInfoBox>
                <AdminInfoBox header="DANGER ZONE" type="danger">
                    <Button bg={BootstrapLikeColors.danger} onClick={deleteUserConfirmAndDelete}>
                        ユーザを削除する
                    </Button>
                    <Note tight>論理削除ではなく物理削除なので気をつけて実行すること！！！！</Note>
                </AdminInfoBox>
            </AdminInfoBoxWrapper>
        </PageWrapper>
    )
}
