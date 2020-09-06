import Axios from 'axios'
import { generateError } from 'api/error'
import { ApiKeyParam, getAuthHeader } from 'api/authUtils'

interface LoginResponse {
    apiToken: string
}

export async function login({ email, userName, password }: { email?: string; userName?: string; password: string }) {
    try {
        const response = await Axios.post<LoginResponse>('/api/login', {
            email: email,
            userName: userName,
            password: password,
        })
        return response.data
    } catch (e) {
        throw generateError(e)
    }
}

export async function revokeTokens({ apiKey }: ApiKeyParam) {
    await Axios.post<LoginResponse>(
        '/api/token/revoke',
        {},
        {
            headers: getAuthHeader(apiKey),
        }
    )
}
