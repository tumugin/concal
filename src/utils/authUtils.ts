export interface ApiKeyParam {
    apiToken: string
}

export function getAuthHeader(apiToken: string) {
    return {
        Authorization: `Bearer ${apiToken}`,
    }
}
