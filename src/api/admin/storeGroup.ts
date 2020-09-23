import { ApiKeyParam, getAuthHeader } from 'api/authUtils'
import Axios from 'axios'

export interface StoreGroupData {
    id: number
    groupName: string
}

export async function getStoreGroups({ apiToken }: ApiKeyParam, { page }: { page: number }) {
    const result = await Axios.get<{ storeGroups: StoreGroupData[]; pageCount: number }>(`/api/admin/groups`, {
        headers: getAuthHeader(apiToken),
        params: {
            page,
        },
    })
    return result.data
}

export async function getStoreGroup({ apiToken }: ApiKeyParam, { storeGroupId }: { storeGroupId: number }) {
    const result = await Axios.get<{ storeGroup: StoreGroupData }>(`/api/admin/groups/${storeGroupId}`, {
        headers: getAuthHeader(apiToken),
    })
    return result.data
}

export function addStoreGroup(
    { apiToken }: ApiKeyParam,
    {
        groupName,
    }: {
        groupName: string
    }
) {
    return Axios.post(
        `/api/admin/groups`,
        {
            groupName,
        },
        {
            headers: getAuthHeader(apiToken),
        }
    )
}

export function updateStoreGroup(
    { apiToken }: ApiKeyParam,
    {
        storeGroupId,
        groupName,
    }: {
        storeGroupId: number
        groupName: string
    }
) {
    return Axios.patch(
        `/api/admin/groups/${storeGroupId}`,
        {
            groupName,
        },
        {
            headers: getAuthHeader(apiToken),
        }
    )
}

export function deleteStoreGroup({ apiToken }: ApiKeyParam, { storeGroupId }: { storeGroupId: number }) {
    return Axios.delete(`/api/admin/groups/${storeGroupId}`, {
        headers: getAuthHeader(apiToken),
    })
}
