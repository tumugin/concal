import * as ReactDOM from 'react-dom'
import React from 'react'
import { setAxiosApiHost } from 'api/config'
import { App } from 'App'

setAxiosApiHost()

ReactDOM.render(<App />, document.getElementById('root'))
