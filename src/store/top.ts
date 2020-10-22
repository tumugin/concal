import { TopContentsRecentUpdatedAttends, TopContentsStoreGroup } from 'api/topContents'

export interface TopStore {
    storeGroups: TopContentsStoreGroup[]
    recentUpdatedAttends: TopContentsRecentUpdatedAttends[]
}

export function createTopStore() {
    const store: TopStore = {
        storeGroups: [],
        recentUpdatedAttends: [],
    }
    return store
}
