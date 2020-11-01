import { createUserStore, initializeUserStoreReducers, UserStore, UserStoreReducers } from 'store/user'
import { createTopStore, TopStore } from 'store/top'
import { createProvider } from 'reactn'
import addReactNDevTools from 'reactn-devtools'
import { createGroupsStore, GroupsStore, GroupStoreReducers, initializeGroupStoreReducers } from 'store/groups'
import { createStoreStore, initializeStoreStoreReducers, StoreStore, StoreStoreReducers } from 'store/store'
import {
    createStoreAttendsStore,
    initializeStoreAttendsReducers,
    StoreAttendsReducers,
    StoreAttendsStore,
} from 'store/storeAttends'

export interface GlobalStore {
    user: UserStore
    top: TopStore
    groups: GroupsStore
    stores: StoreStore
    storeAttends: StoreAttendsStore
}

function createInitialStore(): GlobalStore {
    return {
        user: createUserStore(),
        top: createTopStore(),
        groups: createGroupsStore(),
        stores: createStoreStore(),
        storeAttends: createStoreAttendsStore(),
    }
}

function initializeReducers() {
    initializeUserStoreReducers()
    initializeGroupStoreReducers()
    initializeStoreStoreReducers()
    initializeStoreAttendsReducers()
}

export type StoreReducers = UserStoreReducers & GroupStoreReducers & StoreStoreReducers & StoreAttendsReducers

export type GlobalDispatch = unknown

export const StoreProvider = createProvider<GlobalStore, StoreReducers>(createInitialStore())

initializeReducers()

addReactNDevTools(StoreProvider)
