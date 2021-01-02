import { ApiKeyParam, getAuthHeader } from 'utils/authUtils'
import Axios from 'axios'
import { StoreGroupData } from 'admin/api/storeGroup'
import { AddAPIBasicResponse } from 'admin/api/types'

export interface StoreData {
    id: number
    storeName: string
    storeGroupId: number
    storeDisabled: boolean
    storeGroup: StoreGroupData
}

export async function getStores(
    { apiToken }: ApiKeyParam,
    { page, storeGroupId }: { page: number; storeGroupId?: number }
) {
    const result = await Axios.get<{ stores: StoreData[]; pageCount: number }>(`/api/admin/stores`, {
        headers: getAuthHeader(apiToken),
        params: {
            page,
            storeGroupId,
        },
    })
    return result.data
}

export async function getStore({ apiToken }: ApiKeyParam, { storeId }: { storeId: number }) {
    const result = await Axios.get<{ store: StoreData }>(`/api/admin/stores/${storeId}`, {
        headers: getAuthHeader(apiToken),
    })
    return result.data
}

export async function addStore(
    { apiToken }: ApiKeyParam,
    {
        storeName,
        storeGroupId,
    }: {
        storeName: string
        storeGroupId: number
    }
) {
    const result = await Axios.post<AddAPIBasicResponse>(
        `/api/admin/stores`,
        {
            storeName,
            storeGroupId,
        },
        {
            headers: getAuthHeader(apiToken),
        }
    )
    return result.data
}

export function updateStore(
    { apiToken }: ApiKeyParam,
    {
        storeId,
        storeName,
        storeGroupId,
        storeDisabled,
    }: {
        storeId: number
        storeName: string
        storeGroupId: number
        storeDisabled: boolean
    }
) {
    return Axios.patch(
        `/api/admin/stores/${storeId}`,
        {
            storeName,
            storeGroupId,
            storeDisabled: storeDisabled.toString(),
        },
        {
            headers: getAuthHeader(apiToken),
        }
    )
}

export function deleteStore({ apiToken }: ApiKeyParam, { storeId }: { storeId: number }) {
    return Axios.delete(`/api/admin/stores/${storeId}`, {
        headers: getAuthHeader(apiToken),
    })
}
