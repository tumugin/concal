import { createUserStore, initializeUserStoreReducers, UserStore, UserStoreReducers } from 'store/user'
import { createTopStore, TopStore } from 'store/top'
import { createProvider } from 'reactn'
import addReactNDevTools from 'reactn-devtools'
import { createGroupsStore, GroupsStore, GroupStoreReducers, initializeGroupStoreReducers } from 'store/groups'
import { createStoreStore, initializeStoreStoreReducers, StoreStore, StoreStoreReducers } from 'store/store'

export interface GlobalStore {
    user: UserStore
    top: TopStore
    groups: GroupsStore
    stores: StoreStore
}

function createInitialStore(): GlobalStore {
    return {
        user: createUserStore(),
        top: createTopStore(),
        groups: createGroupsStore(),
        stores: createStoreStore(),
    }
}

function initializeReducers() {
    initializeUserStoreReducers()
    initializeGroupStoreReducers()
    initializeStoreStoreReducers()
}

export type StoreReducers = UserStoreReducers & GroupStoreReducers & StoreStoreReducers

export type GlobalDispatch = unknown

export const StoreProvider = createProvider<GlobalStore, StoreReducers>(createInitialStore())

initializeReducers()

addReactNDevTools(StoreProvider)
