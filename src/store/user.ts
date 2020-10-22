import { login, selfInfo } from 'api/auth'
import { useCallback } from 'react'
import { deleteLocalStorageToken, getLocalStorageToken, setLocalStorageToken } from 'storage/tokenStorage'
import { LoginException } from 'api/error'
import { GlobalDispatch, GlobalStore, StoreProvider } from 'store/store'
import produce from 'immer'

export interface UserStore {
    isLoggedIn: boolean
    apiToken: string | null
    self: SelfState | null
}

interface SelfState {
    id: number
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

export interface UserStoreReducers {
    'user/SetSelfState': (global: GlobalStore, dispatch: GlobalDispatch, selfState: SelfState) => void
}

export function initializeUserStoreReducers() {
    StoreProvider.addReducer('user/SetSelfState', (global, _, selfState: SelfState) => {
        return produce(global, (draftState) => {
            draftState.user.self = selfState
        })
    })
}

export function useUserLogin() {
    const [, setUser] = StoreProvider.useGlobal('user')
    const fetchUserInfo = useFetchUserInfo()
    return useCallback(
        async ({ userIdentifier, password }: { userIdentifier: string; password: string }) => {
            const email = emailRegex.test(userIdentifier) ? userIdentifier : undefined
            const apiResult = await login({
                email,
                userName: email ? undefined : userIdentifier,
                password,
            })
            await setUser({
                isLoggedIn: true,
                apiToken: apiResult.apiToken,
                self: null,
            })
            setLocalStorageToken(apiResult.apiToken)
            await fetchUserInfo(apiResult.apiToken)
        },
        [fetchUserInfo, setUser]
    )
}

export function useSavedUserLogin() {
    const [, setUser] = StoreProvider.useGlobal('user')
    const fetchUserInfo = useFetchUserInfo()
    const logout = useUserLogout()
    return useCallback(async () => {
        const currentToken = getLocalStorageToken()
        if (currentToken === null) {
            return
        }
        await setUser({
            isLoggedIn: true,
            apiToken: currentToken,
            self: null,
        })
        try {
            await fetchUserInfo(currentToken)
        } catch (e) {
            if (e instanceof LoginException) {
                // TODO: どうにかしてエラーを出す
                await logout()
            } else {
                throw e
            }
        }
    }, [fetchUserInfo, logout, setUser])
}

export function useUserLogout() {
    const [, setUser] = StoreProvider.useGlobal('user')
    return useCallback(async () => {
        await setUser({
            isLoggedIn: false,
            apiToken: null,
            self: null,
        })
        deleteLocalStorageToken()
    }, [setUser])
}

export function useFetchUserInfo() {
    const dispatchSetSelfState = StoreProvider.useDispatch('user/SetSelfState')
    return useCallback(
        async (apiToken: string) => {
            const apiResult = await selfInfo({ apiToken })
            await dispatchSetSelfState(apiResult.info)
        },
        [dispatchSetSelfState]
    )
}

export function useApiToken() {
    const user = useUser()
    return user.apiToken
}

export function useUser() {
    const [user] = StoreProvider.useGlobal('user')
    return user
}
