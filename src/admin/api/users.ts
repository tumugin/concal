import { ApiKeyParam, getAuthHeader } from 'utils/authUtils'
import Axios from 'axios'
import { AddAPIBasicResponse } from 'admin/api/types'

export interface UserData {
    id: number
    userName: string
    name: string
    email: string
    userPrivilege: 'user'
}

export async function getUsers({ apiToken }: ApiKeyParam, { page }: { page: number }) {
    const result = await Axios.get<{ users: UserData[]; pageCount: number }>(`/api/admin/users`, {
        headers: getAuthHeader(apiToken),
        params: {
            page,
        },
    })
    return result.data
}

export async function getUser({ apiToken }: ApiKeyParam, { userId }: { userId: number }) {
    const result = await Axios.get<{ user: UserData }>(`/api/admin/users/${userId}`, {
        headers: getAuthHeader(apiToken),
    })
    return result.data
}

export async function addUser(
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
    const response = await Axios.post<AddAPIBasicResponse>(
        `/api/admin/users`,
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
    return response.data
}

export function updateUser(
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
        `/api/admin/users/${userId}`,
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

export function deleteUser({ apiToken }: ApiKeyParam, { userId }: { userId: number }) {
    return Axios.delete(`/api/admin/users/${userId}`, {
        headers: getAuthHeader(apiToken),
    })
}
