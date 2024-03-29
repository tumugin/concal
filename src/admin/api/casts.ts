import { ApiKeyParam, getAuthHeader } from 'utils/authUtils'
import Axios from 'axios'
import { StoreData } from 'admin/api/store'
import { AddAPIBasicResponse } from 'admin/api/types'
import { AdminAttendData } from 'admin/api/attends'

export interface CastData {
    id: number
    castName: string
    castShortName: string
    castTwitterId: string
    castDescription: string
    castColor: string
    castDisabled: boolean
    stores: StoreData[]
    latestCastAttend: AdminAttendData | null
}

export async function getCasts({ apiToken }: ApiKeyParam, { page, storeId }: { page: number; storeId?: number }) {
    const result = await Axios.get<{ casts: CastData[]; pageCount: number }>(`/api/admin/casts`, {
        headers: getAuthHeader(apiToken),
        params: {
            page,
            storeId,
        },
    })
    return result.data
}

export async function getCast({ apiToken }: ApiKeyParam, { castId }: { castId: number }) {
    const result = await Axios.get<{ cast: CastData }>(`/api/admin/casts/${castId}`, {
        headers: getAuthHeader(apiToken),
    })
    return result.data
}

export async function addCast(
    { apiToken }: ApiKeyParam,
    {
        castName,
        castShortName,
        castTwitterId,
        castDescription,
        castColor,
    }: {
        castName: string
        castShortName: string
        castTwitterId: string
        castDescription: string
        castColor: string | null
    }
) {
    const result = await Axios.post<AddAPIBasicResponse>(
        `/api/admin/casts`,
        {
            castName,
            castShortName,
            castTwitterId,
            castDescription,
            castColor,
        },
        {
            headers: getAuthHeader(apiToken),
        }
    )
    return result.data
}

export function updateCast(
    { apiToken }: ApiKeyParam,
    {
        castId,
        castName,
        castShortName,
        castTwitterId,
        castDescription,
        castColor,
        storeIds,
        castDisabled,
    }: {
        castId: number
        castName: string
        castShortName: string
        castTwitterId: string
        castDescription: string
        castColor: string | null
        storeIds?: number[]
        castDisabled?: boolean
    }
) {
    return Axios.patch(
        `/api/admin/casts/${castId}`,
        {
            castName,
            castShortName,
            castTwitterId,
            castDescription,
            castColor,
            storeIds: storeIds !== undefined ? storeIds.join() : undefined,
            castDisabled: castDisabled ? 'true' : 'false',
        },
        {
            headers: getAuthHeader(apiToken),
        }
    )
}

export function deleteCast({ apiToken }: ApiKeyParam, { castId }: { castId: number }) {
    return Axios.delete(`/api/admin/casts/${castId}`, {
        headers: getAuthHeader(apiToken),
    })
}
