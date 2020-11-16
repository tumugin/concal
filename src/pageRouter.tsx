import React, { Suspense } from 'react'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import { NavBar } from 'components/NavBar'

export default function PageRouter() {
    const Top = React.lazy(() => import('page/Top'))
    const Groups = React.lazy(() => import('page/Groups'))
    const Store = React.lazy(() => import('page/Stores/show'))
    const StoreAttends = React.lazy(() => import('page/StoreAttends'))
    const Cast = React.lazy(() => import('page/Casts/show'))
    const Login = React.lazy(() => import('page/Login'))

    return (
        <BrowserRouter>
            <NavBar />
            <Suspense fallback={null}>
                <Switch>
                    <Route path="/" component={Top} exact />
                    <Route path="/groups" component={Groups} exact />
                    <Route path="/stores/:id" component={Store} exact />
                    <Route path="/stores/:id/attends" component={StoreAttends} exact />
                    <Route path="/casts/:id" component={Cast} exact />
                    <Route path="/login" component={Login} exact />
                </Switch>
            </Suspense>
        </BrowserRouter>
    )
}
