import { createUserStore, initializeUserStoreReducers, UserStore, UserStoreReducers } from 'admin/store/user'
import { createProvider } from 'reactn'
import addReactNDevTools from 'reactn-devtools'

export interface AdminGlobalStore {
    user: UserStore
}

function createAdminInitialStore(): AdminGlobalStore {
    return {
        user: createUserStore(),
    }
}

function initializeAdminStore() {
    addReactNDevTools(AdminStoreProvider)
    initializeReducers()
}

function initializeReducers() {
    initializeUserStoreReducers(AdminStoreProvider)
}

export type StoreReducers = UserStoreReducers

export type GlobalDispatch = unknown

export const AdminStoreProvider = createProvider<AdminGlobalStore, StoreReducers>(createAdminInitialStore())
export type StoreProviderType = typeof AdminStoreProvider

initializeAdminStore()
