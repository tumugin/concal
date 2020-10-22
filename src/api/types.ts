export interface UserStoreGroup {
    id: number
    groupName: string
}

export interface UserStore {
    id: 1
    storeName: string
    storeGroupId: 1
    storeDisabled: false
}

export interface UserCastAttend {
    id: number
    castId: number
    storeId: 1
    attendInfo: string
    startTime: string
    endTime: string
}

export interface UserCast {
    id: number
    castName: string
    castShortName: string | null
    castTwitterId: string | null
    castDescription: string
    castColor: string | null
    castDisabled: boolean
}
