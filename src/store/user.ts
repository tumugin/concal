import { useStoreContext } from './store'
import { login, selfInfo } from 'api/auth'
import produce from 'immer'
import { unreachableCode } from 'types/util'
import { useCallback } from 'react'

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

const emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/

export function useUserLogin() {
    const { store, setStore } = useStoreContext()
    return useCallback(
        async ({ userIdentifier, password }: { userIdentifier: string; password: string }) => {
            const email = emailRegex.test(userIdentifier) ? userIdentifier : undefined
            const apiResult = await login({
                email,
                userName: email ? undefined : userIdentifier,
                password,
            })
            setStore(
                produce(store, (draftStore) => {
                    draftStore.user.isLoggedIn = true
                    draftStore.user.apiToken = apiResult.apiToken
                })
            )
        },
        [setStore, store]
    )
}

export function useUserLogout() {
    const { store, setStore } = useStoreContext()
    return useCallback(() => {
        setStore(
            produce(store, (draftStore) => {
                draftStore.user.self = null
                draftStore.user.apiToken = null
                draftStore.user.isLoggedIn = false
            })
        )
    }, [setStore, store])
}

export function useFetchUserInfo() {
    const { store, setStore } = useStoreContext()
    return useCallback(async () => {
        const apiResult = await selfInfo({ apiToken: store.user.apiToken ?? unreachableCode() })
        setStore(
            produce(store, (draftStore) => {
                draftStore.user.self = apiResult.info
            })
        )
    }, [setStore, store])
}
