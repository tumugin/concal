import React from 'react'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import { Top } from 'components/Top'

export function PageRouter() {
    return (
        <BrowserRouter>
            <Switch>
                <Route path="/" component={Top} exact />
            </Switch>
        </BrowserRouter>
    )
}
