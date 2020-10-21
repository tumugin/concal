import { UserCast, UserCastAttend, UserStore, UserStoreGroup } from 'api/types'
import Axios from 'axios'

interface TopContentsResponse {
    data: {
        storeGroups: (UserStoreGroup & { stores: UserStore[] })[]
        recentUpdatedAttends: (UserCastAttend & { cast: UserCast; store: UserStore; storeGroup: UserStoreGroup })[]
    }
}

export async function getTopContents() {
    const result = await Axios.get<TopContentsResponse>('/api/top_contents')
    return result.data.data
}
