import Axios from 'axios'
import { ApiKeyParam, getAuthHeader } from 'utils/authUtils'
import { StoreData } from 'admin/api/store'
import { AddAPIBasicResponse } from 'admin/api/types'

export interface AdminAttendData {
    id: number
    castId: number
    storeId: number
    startTime: string
    endTime: string
    attendInfo: string
    addedByUserId: number
}

export interface AttendDataDetails extends AdminAttendData {
    store: StoreData
}

export async function getAttends(
    { apiToken }: ApiKeyParam,
    { castId, startTime, endTime }: { castId: number; startTime: string; endTime: string }
) {
    const result = await Axios.get<{ attends: AttendDataDetails[]; pageCount: number }>(
        `/api/admin/casts/${castId}/attends`,
        {
            headers: getAuthHeader(apiToken),
            params: {
                startTime,
                endTime,
            },
        }
    )
    return result.data
}

export async function getAttend({ apiToken }: ApiKeyParam, { attendId }: { attendId: number }) {
    const result = await Axios.get<{ attend: AttendDataDetails }>(`/api/admin/attends/${attendId}`, {
        headers: getAuthHeader(apiToken),
    })
    return result.data
}

export async function addAttend(
    { apiToken }: ApiKeyParam,
    {
        castId,
        storeId,
        startTime,
        endTime,
        attendInfo,
    }: { castId: number; storeId: number; startTime: string; endTime: string; attendInfo: string }
) {
    const result = await Axios.post<AddAPIBasicResponse>(
        `/api/admin/casts/${castId}/attends`,
        {
            storeId,
            startTime,
            endTime,
            attendInfo,
        },
        {
            headers: getAuthHeader(apiToken),
        }
    )
    return result.data
}

export function updateAttend(
    { apiToken }: ApiKeyParam,
    {
        attendId,
        storeId,
        startTime,
        endTime,
        attendInfo,
    }: { attendId: number; storeId: number; startTime: string; endTime: string; attendInfo: string }
) {
    return Axios.patch(
        `/api/admin/attends/${attendId}`,
        {
            storeId,
            startTime,
            endTime,
            attendInfo,
        },
        {
            headers: getAuthHeader(apiToken),
        }
    )
}

export function deleteAttend({ apiToken }: ApiKeyParam, { attendId }: { attendId: number }) {
    return Axios.delete(`/api/admin/attends/${attendId}`, {
        headers: getAuthHeader(apiToken),
    })
}
