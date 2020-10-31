import { UserCast, UserCastAttend, UserStore, UserStoreGroup } from 'api/types'
import Axios from 'axios'

export interface Store extends UserStore {
    storeGroup: UserStoreGroup
    casts: StoreCastData[]
}

export interface StoreCastData extends UserCast {
    recentAttend: UserCastAttend | null
}

interface StoreResponse {
    data: {
        store: Store
    }
}

export async function getStore({ storeId }: { storeId: number }) {
    const result = await Axios.get<StoreResponse>(`/api/stores/${storeId}`)
    return result.data.data
}
