import { AxiosError } from 'axios'

export interface ErrorResponse {
    error: string
}

export class LoginException extends Error {}

export function generateError(error: AxiosError<ErrorResponse>) {
    if (error.code !== '401') {
        return error
    }
    return LoginException
}
