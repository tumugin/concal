import { createUserStore, initializeUserStoreReducers, UserStore, UserStoreReducers } from 'store/user'
import { createTopStore, TopStore } from 'store/top'
import { createProvider } from 'reactn'
import addReactNDevTools from 'reactn-devtools'
import { createGroupsStore, GroupsStore, GroupStoreReducers, initializeGroupStoreReducers } from 'store/groups'

export interface GlobalStore {
    user: UserStore
    top: TopStore
    groups: GroupsStore
}

function createInitialStore(): GlobalStore {
    return {
        user: createUserStore(),
        top: createTopStore(),
        groups: createGroupsStore(),
    }
}

function initializeReducers() {
    initializeUserStoreReducers()
    initializeGroupStoreReducers()
}

export type StoreReducers = UserStoreReducers & GroupStoreReducers

export type GlobalDispatch = unknown

export const StoreProvider = createProvider<GlobalStore, StoreReducers>(createInitialStore())

initializeReducers()

addReactNDevTools(StoreProvider)
