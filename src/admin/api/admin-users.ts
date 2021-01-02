import { ApiKeyParam, getAuthHeader } from 'utils/authUtils'
import Axios from 'axios'
import { AddAPIBasicResponse } from 'admin/api/types'

export interface AdminUserData {
    id: number
    userName: string
    name: string
    email: string
    userPrivilege: 'admin' | 'super_admin'
}

export async function getAdminUsers({ apiToken }: ApiKeyParam, { page }: { page: number }) {
    const result = await Axios.get<{ users: AdminUserData[]; pageCount: number }>(`/api/admin/admin_users`, {
        headers: getAuthHeader(apiToken),
        params: {
            page,
        },
    })
    return result.data
}

export async function getAdminUser({ apiToken }: ApiKeyParam, { userId }: { userId: number }) {
    const result = await Axios.get<{ user: AdminUserData }>(`/api/admin/admin_users/${userId}`, {
        headers: getAuthHeader(apiToken),
    })
    return result.data
}

export async function addAdminUser(
    { apiToken }: ApiKeyParam,
    {
        userName,
        name,
        password,
        email,
        userPrivilege,
    }: {
        userName: string
        name: string
        password: string
        email: string
        userPrivilege: string
    }
) {
    const result = await Axios.post<AddAPIBasicResponse>(
        `/api/admin/admin_users`,
        {
            userName,
            name,
            password,
            email,
            userPrivilege,
        },
        {
            headers: getAuthHeader(apiToken),
        }
    )
    return result.data
}

export function updateAdminUser(
    { apiToken }: ApiKeyParam,
    {
        userId,
        userName,
        name,
        password,
        email,
        userPrivilege,
    }: {
        userId?: number
        userName?: string
        name?: string
        password?: string
        email?: string
        userPrivilege?: string
    }
) {
    return Axios.patch(
        `/api/admin/admin_users/${userId}`,
        {
            userName,
            name,
            password,
            email,
            userPrivilege,
        },
        {
            headers: getAuthHeader(apiToken),
        }
    )
}

export function deleteAdminUser({ apiToken }: ApiKeyParam, { userId }: { userId: number }) {
    return Axios.delete(`/api/admin/admin_users/${userId}`, {
        headers: getAuthHeader(apiToken),
    })
}
