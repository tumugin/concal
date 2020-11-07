import { getStoreGroups, StoreGroup } from 'api/storeGroups'
import { GlobalDispatch, GlobalStore, StoreProvider, StoreProviderType } from 'store'
import produce from 'immer'
import { useCallback } from 'react'

export interface GroupsStore {
    storeGroups: StoreGroup[]
    isLoading: boolean
    hasNextPage: boolean
    initialPageLoaded: boolean
    nextPage: number | null
}

export function createGroupsStore() {
    const store: GroupsStore = {
        storeGroups: [],
        isLoading: false,
        hasNextPage: true,
        initialPageLoaded: false,
        nextPage: 1,
    }
    return store
}

export interface GroupStoreReducers {
    'groups/setIsLoading': (global: GlobalStore, dispatch: GlobalDispatch, isLoading: boolean) => void
    'groups/appendStoreGroups': (
        global: GlobalStore,
        dispatch: GlobalDispatch,
        payload: { storeGroups: StoreGroup[]; nextPage: number | null }
    ) => void
}

export function initializeGroupStoreReducers(provider: StoreProviderType) {
    provider.addReducer('groups/setIsLoading', (global, _, isLoading: boolean) => {
        return produce(global, (draftState) => {
            draftState.groups.isLoading = isLoading
        })
    })
    provider.addReducer(
        'groups/appendStoreGroups',
        (global, _, { storeGroups, nextPage }: { storeGroups: StoreGroup[]; nextPage: number | null }) => {
            return produce(global, (draftState) => {
                draftState.groups.storeGroups = [...draftState.groups.storeGroups, ...storeGroups]
                draftState.groups.hasNextPage = nextPage !== null
                draftState.groups.nextPage = nextPage
                draftState.groups.initialPageLoaded = true
            })
        }
    )
}

export function useLoadNextPage() {
    const dispatchSetIsLoading = StoreProvider.useDispatch('groups/setIsLoading')
    const dispatchAppendStoreGroups = StoreProvider.useDispatch('groups/appendStoreGroups')
    const { nextPage } = useGroups()
    return useCallback(async () => {
        if (!nextPage) {
            return
        }
        await dispatchSetIsLoading(true)
        const result = await getStoreGroups({ page: nextPage })
        await dispatchAppendStoreGroups({
            storeGroups: result.data.storeGroups,
            nextPage: result.data.nextPage,
        })
        await dispatchSetIsLoading(false)
    }, [dispatchAppendStoreGroups, dispatchSetIsLoading, nextPage])
}

export function useGroups() {
    const [groups] = StoreProvider.useGlobal('groups')
    return groups
}
