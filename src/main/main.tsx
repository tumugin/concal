import * as ReactDOM from 'react-dom'
import React from 'react'
import { setAxiosApiHost } from 'config'
import { App } from 'main/App'
import dayjs from 'dayjs'
import 'dayjs/locale/ja'

setAxiosApiHost()
dayjs.locale('ja')

ReactDOM.render(<App />, document.getElementById('root'))
