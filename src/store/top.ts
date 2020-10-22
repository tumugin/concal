import { getTopContents, TopContentsRecentUpdatedAttends, TopContentsStoreGroup } from 'api/topContents'
import { StoreProvider } from 'store/store'
import { useCallback } from 'react'

export interface TopStore {
    loaded: boolean
    storeGroups: TopContentsStoreGroup[]
    recentUpdatedAttends: TopContentsRecentUpdatedAttends[]
}

export function createTopStore() {
    const store: TopStore = {
        loaded: false,
        storeGroups: [],
        recentUpdatedAttends: [],
    }
    return store
}

export function useLoadTopContents() {
    const [, setTop] = StoreProvider.useGlobal('top')
    return useCallback(async () => {
        const result = await getTopContents()
        await setTop({
            loaded: true,
            storeGroups: result.storeGroups,
            recentUpdatedAttends: result.recentUpdatedAttends,
        })
    }, [setTop])
}

export function useTop() {
    const [top] = StoreProvider.useGlobal('top')
    return top
}
