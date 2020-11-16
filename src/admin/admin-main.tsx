import * as ReactDOM from 'react-dom'
import React from 'react'
import { setAxiosApiHost } from 'api/config'
import { AdminApp } from 'admin/AdminApp'

setAxiosApiHost()

ReactDOM.render(<AdminApp />, document.getElementById('root'))
