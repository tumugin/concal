import { createUserStore, initializeUserStoreReducers, UserStore, UserStoreReducers } from 'main/store/user'
import { createTopStore, TopStore } from 'main/store/top'
import { createProvider } from 'reactn'
import addReactNDevTools from 'reactn-devtools'
import { createGroupsStore, GroupsStore, GroupStoreReducers, initializeGroupStoreReducers } from 'main/store/groups'
import { createStoreStore, initializeStoreStoreReducers, StoreStore, StoreStoreReducers } from 'main/store/store'
import {
    createStoreAttendsStore,
    initializeStoreAttendsReducers,
    StoreAttendsReducers,
    StoreAttendsStore,
} from 'main/store/storeAttends'
import { CastStore, CastStoreReducers, createCastStore, initializeCastStoreReducers } from 'main/store/cast'

export interface GlobalStore {
    user: UserStore
    top: TopStore
    groups: GroupsStore
    stores: StoreStore
    storeAttends: StoreAttendsStore
    casts: CastStore
}

function createInitialStore(): GlobalStore {
    return {
        user: createUserStore(),
        top: createTopStore(),
        groups: createGroupsStore(),
        stores: createStoreStore(),
        storeAttends: createStoreAttendsStore(),
        casts: createCastStore(),
    }
}

export function initializeStore() {
    addReactNDevTools(StoreProvider)
    initializeReducers()
}

function initializeReducers() {
    initializeUserStoreReducers(StoreProvider)
    initializeGroupStoreReducers(StoreProvider)
    initializeStoreStoreReducers(StoreProvider)
    initializeStoreAttendsReducers(StoreProvider)
    initializeCastStoreReducers(StoreProvider)
}

export type StoreReducers = UserStoreReducers &
    GroupStoreReducers &
    StoreStoreReducers &
    StoreAttendsReducers &
    CastStoreReducers

export type GlobalDispatch = unknown

export const StoreProvider = createProvider<GlobalStore, StoreReducers>(createInitialStore())
export type StoreProviderType = typeof StoreProvider

initializeStore()
