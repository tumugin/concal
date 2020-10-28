import { UserStore, UserStoreGroup } from 'api/types'
import Axios from 'axios'

export interface StoreGroup extends UserStoreGroup {
    stores: UserStore[]
}

export interface StoreGroupsResponse {
    data: {
        storeGroups: StoreGroup[]
        pageCount: number
        nextPage: number
    }
}

export async function getStoreGroups({ page }: { page: number }) {
    const result = await Axios.get<StoreGroupsResponse>('/api/top_contents', { params: { page } })
    return result.data
}

export interface StoreGroupResponse {
    data: {
        storeGroup: UserStoreGroup
        stores: UserStore[]
    }
}

export async function getStoreGroup({ id }: { page: number; id: number }) {
    const result = await Axios.get<StoreGroupResponse>(`/api/top_contents/${id}`)
    return result.data
}
