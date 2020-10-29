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
    const result = await Axios.get<StoreGroupsResponse>('/api/store_groups', { params: { page } })
    return result.data
}

export interface StoreGroupResponse {
    data: {
        storeGroup: UserStoreGroup
        stores: UserStore[]
    }
}

export async function getStoreGroup({ id }: { page: number; id: number }) {
    const result = await Axios.get<StoreGroupResponse>(`/api/store_groups/${id}`)
    return result.data
}
