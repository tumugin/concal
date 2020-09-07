import * as ReactDOM from 'react-dom'
import React from 'react'
import PageRouter from 'pageRouter'
import { setAxiosApiHost } from 'api/config'

setAxiosApiHost()

ReactDOM.render(<PageRouter />, document.getElementById('root'))
