const storageKey = 'concal_token'

export function getLocalStorageToken() {
    return localStorage.getItem(storageKey)
}

export function setLocalStorageToken(token: string) {
    return localStorage.setItem(storageKey, token)
}
