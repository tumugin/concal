import React from 'react'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import { Top } from 'components/Top'
import { NavBar } from 'components/NavBar'

export default function PageRouter() {
    return (
        <BrowserRouter>
            <NavBar />
            <Switch>
                <Route path="/" component={Top} exact />
            </Switch>
        </BrowserRouter>
    )
}
