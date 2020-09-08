import React from 'react'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import { Top } from 'page/Top'
import { NavBar } from 'components/NavBar'
import { Login } from 'page/Login'

export default function PageRouter() {
    return (
        <BrowserRouter>
            <NavBar />
            <Switch>
                <Route path="/" component={Top} exact />
                <Route path="/login" component={Login} exact />
            </Switch>
        </BrowserRouter>
    )
}
