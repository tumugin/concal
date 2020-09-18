import { PageWrapper } from 'components/PageWrapper'
import { Heading } from 'rebass/styled-components'
import React, { useDebugValue, useEffect, useState } from 'react'
import { getUsers, UserData } from 'api/admin/users'
import { useApiToken } from 'store/user'

export function AdminUsers() {
    const apiToken = useApiToken()
    const [userData, setUserData] = useState<UserData[]>([])
    const [page, setPage] = useState(1)
    useDebugValue(userData)

    useEffect(() => {
        if (!apiToken) {
            return
        }
        ;(async () => {
            setUserData((await getUsers({ apiToken }, { page })).users)
        })()
    }, [apiToken, page])

    return (
        <PageWrapper>
            <Heading>ユーザ一覧</Heading>
        </PageWrapper>
    )
}
