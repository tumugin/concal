import Axios from 'axios'
import { generateError } from 'api/error'
import { ApiKeyParam, getAuthHeader } from 'api/authUtils'

interface LoginResponse {
    apiToken: string
}

interface SelfResponse {
    info: {
        id: number
        userName: string
        name: string
        email: string
        userPrivilege: 'admin' | 'super_admin'
    }
}

export async function login({ email, userName, password }: { email?: string; userName?: string; password: string }) {
    try {
        const response = await Axios.post<LoginResponse>('/api/admin/login', {
            email: email,
            userName: userName,
            password: password,
        })
        return response.data
    } catch (e) {
        throw generateError(e)
    }
}

export async function proxyLogin() {
    try {
        const response = await Axios.post<LoginResponse>('/api/admin/proxy_login', {})
        return response.data
    } catch (e) {
        throw generateError(e)
    }
}

export async function selfInfo({ apiToken }: ApiKeyParam) {
    try {
        const response = await Axios.get<SelfResponse>('/api/admin/self', {
            headers: getAuthHeader(apiToken),
        })
        return response.data
    } catch (e) {
        throw generateError(e)
    }
}
