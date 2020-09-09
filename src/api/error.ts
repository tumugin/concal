import { AxiosError } from 'axios'

export interface ErrorResponse {
    error: string
}

export class LoginException extends Error {}

export function generateError(error: AxiosError<ErrorResponse>) {
    if (error.response?.status === 401) {
        return new LoginException()
    }
    return error
}
