import { UserCast, UserCastAttend, UserStore, UserStoreGroup } from 'main/api/types'
import Axios from 'axios'

export interface Cast extends UserCast {
    stores: (UserStore & { storeGroup: UserStoreGroup })[]
    recentAttends: (UserCastAttend & { store: UserStore })[]
}

interface CastResponse {
    data: {
        cast: Cast
    }
}

export async function getCast({ castId }: { castId: number }) {
    const result = await Axios.get<CastResponse>(`/api/casts/${castId}`)
    return result.data
}
