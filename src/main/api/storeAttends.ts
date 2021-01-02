import { UserCast, UserCastAttend } from 'main/api/types'
import Axios from 'axios'

export interface StoreAttendData extends UserCastAttend {
    cast: UserCast
}

export interface StoreAttendResponse {
    data: {
        attends: StoreAttendData[]
    }
}

export async function getStoreAttend({
    storeId,
    startDate,
    endDate,
}: {
    storeId: number
    startDate: string
    endDate: string
}) {
    const result = await Axios.get<StoreAttendResponse>(`/api/stores/${storeId}/attends`, {
        params: {
            startDate,
            endDate,
        },
    })
    return result.data
}
