import { ApiKeyParam, getAuthHeader } from 'api/authUtils'
import Axios from 'axios'

export interface StoreData {
    id: number
    storeName: string
    storeGroupId: number
    storeDisabled: boolean
}

export async function getStores({ apiToken }: ApiKeyParam, { page }: { page: number }) {
    const result = await Axios.get<{ stores: StoreData[]; pageCount: number }>(`/api/admin/stores`, {
        headers: getAuthHeader(apiToken),
        params: {
            page,
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

export function addStore(
    { apiToken }: ApiKeyParam,
    {
        storeName,
        storeGroupId,
    }: {
        storeName: string
        storeGroupId: number
    }
) {
    return Axios.post(
        `/api/admin/stores`,
        {
            storeName,
            storeGroupId,
        },
        {
            headers: getAuthHeader(apiToken),
        }
    )
}

export function updateStore(
    { apiToken }: ApiKeyParam,
    {
        storeId,
        storeName,
        storeGroupId,
    }: {
        storeId: number
        storeName: string
        storeGroupId: number
    }
) {
    return Axios.patch(
        `/api/admin/stores/${storeId}`,
        {
            storeName,
            storeGroupId,
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
