// 空欄にしておくとホスト元にそのままリクエストが飛ぶ
import Axios from 'axios'

const API_HOST =
    typeof IS_WEBPACK_DEV_SERVER !== 'undefined' && IS_WEBPACK_DEV_SERVER ? 'http://localhost:9000' : undefined
const API_KEY = '6982b4ef46c19dcc846926f25d757f5c332783495aea1818a6340ce7ffb1bb52'

export function setAxiosApiHost() {
    Axios.defaults.baseURL = API_HOST
    Axios.defaults.headers = {
        'X-API-KEY': API_KEY,
    }
}
