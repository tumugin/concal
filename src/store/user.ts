import { useStoreContext } from './store'
import { login, selfInfo } from 'api/auth'
import produce from 'immer'
import { useCallback } from 'react'
import { deleteLocalStorageToken, getLocalStorageToken, setLocalStorageToken } from 'storage/tokenStorage'
import { LoginException } from 'api/error'

export interface UserStore {
    isLoggedIn: boolean
    apiToken: string | null
    self: SelfState | null
}

interface SelfState {
    id: string
    userName: string
    name: string
    email: string
    userPrivilege: 'admin' | 'user'
}

export function createUserStore(): UserStore {
    return {
        isLoggedIn: false,
        apiToken: null,
        self: null,
    }
}

const emailRegex = /^[a-zA-Z0-9.!#$%&'*+\\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/

export function useUserLogin() {
    const { setStore } = useStoreContext()
    const fetchUserInfo = useFetchUserInfo()
    return useCallback(
        async ({ userIdentifier, password }: { userIdentifier: string; password: string }) => {
            const email = emailRegex.test(userIdentifier) ? userIdentifier : undefined
            const apiResult = await login({
                email,
                userName: email ? undefined : userIdentifier,
                password,
            })
            setStore((store) =>
                produce(store, (draftStore) => {
                    draftStore.user.isLoggedIn = true
                    draftStore.user.apiToken = apiResult.apiToken
                })
            )
            setLocalStorageToken(apiResult.apiToken)
            await fetchUserInfo(apiResult.apiToken)
        },
        [fetchUserInfo, setStore]
    )
}

export function useSavedUserLogin() {
    const { setStore } = useStoreContext()
    const fetchUserInfo = useFetchUserInfo()
    const logout = useUserLogout()
    return useCallback(async () => {
        const currentToken = getLocalStorageToken()
        if (currentToken === null) {
            return
        }
        setStore((store) =>
            produce(store, (draftStore) => {
                draftStore.user.isLoggedIn = true
                draftStore.user.apiToken = currentToken
            })
        )
        try {
            await fetchUserInfo(currentToken)
        } catch (e) {
            if (e instanceof LoginException) {
                // TODO: どうにかしてエラーを出す
                logout()
            } else {
                throw e
            }
        }
    }, [fetchUserInfo, logout, setStore])
}

export function useUserLogout() {
    const { setStore } = useStoreContext()
    return useCallback(() => {
        setStore((store) =>
            produce(store, (draftStore) => {
                draftStore.user.self = null
                draftStore.user.apiToken = null
                draftStore.user.isLoggedIn = false
            })
        )
        deleteLocalStorageToken()
    }, [setStore])
}

export function useFetchUserInfo() {
    const { setStore } = useStoreContext()
    return useCallback(
        async (apiToken: string) => {
            const apiResult = await selfInfo({ apiToken })
            setStore((store) =>
                produce(store, (draftStore) => {
                    draftStore.user.self = apiResult.info
                })
            )
        },
        [setStore]
    )
}
