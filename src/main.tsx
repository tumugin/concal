import * as ReactDOM from 'react-dom'
import React from 'react'
import PageRouter from 'pageRouter'
import { setAxiosApiHost } from 'api/config'
import { StoreProvider } from 'store/store'

setAxiosApiHost()

ReactDOM.render(
    <StoreProvider>
        <PageRouter />
    </StoreProvider>,
    document.getElementById('root')
)
