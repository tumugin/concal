// 空欄にしておくとホスト元にそのままリクエストが飛ぶ
import Axios from 'axios'

export const API_HOST =
    typeof IS_WEBPACK_DEV_SERVER !== 'undefined' && IS_WEBPACK_DEV_SERVER ? 'http://localhost:9000' : undefined

export function setAxiosApiHost() {
    Axios.defaults.baseURL = API_HOST
}
