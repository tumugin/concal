export interface ApiKeyParam {
    apiKey: string
}

export function getAuthHeader(apiKey: string) {
    return {
        Authorization: `Bearer ${apiKey}`,
    }
}
