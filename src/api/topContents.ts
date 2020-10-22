import { UserCast, UserCastAttend, UserStore, UserStoreGroup } from 'api/types'
import Axios from 'axios'

export interface TopContentsStoreGroup extends UserStoreGroup {
    stores: UserStore[]
}

export interface TopContentsRecentUpdatedAttends extends UserCastAttend {
    cast: UserCast
    store: UserStore
    storeGroup: UserStoreGroup
}

interface TopContentsResponse {
    data: {
        storeGroups: TopContentsStoreGroup[]
        recentUpdatedAttends: TopContentsRecentUpdatedAttends[]
    }
}

export async function getTopContents() {
    const result = await Axios.get<TopContentsResponse>('/api/top_contents')
    return result.data.data
}
