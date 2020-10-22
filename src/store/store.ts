import { createUserStore, initializeUserStoreReducers, UserStore, UserStoreReducers } from 'store/user'
import { createTopStore, TopStore } from 'store/top'
import { createProvider } from 'reactn'
import addReactNDevTools from 'reactn-devtools'

export interface GlobalStore {
    user: UserStore
    top: TopStore
}

function createInitialStore(): GlobalStore {
    return {
        user: createUserStore(),
        top: createTopStore(),
    }
}

function initializeReducers() {
    initializeUserStoreReducers()
}

export type StoreReducers = UserStoreReducers

export type GlobalDispatch = unknown

export const StoreProvider = createProvider<GlobalStore, StoreReducers>(createInitialStore())

initializeReducers()

addReactNDevTools(StoreProvider)
