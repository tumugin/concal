import React, { Suspense } from 'react'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import { NavBar } from 'components/NavBar'

export default function PageRouter() {
    const Top = React.lazy(() => import('page/Top'))
    const Groups = React.lazy(() => import('page/Groups'))
    const Store = React.lazy(() => import('page/Stores/show'))
    const StoreAttendsCalendar = React.lazy(() => import('page/StoreAttendsCalendar'))
    const Cast = React.lazy(() => import('page/Casts/show'))
    const Login = React.lazy(() => import('page/Login'))
    const StoreAttendsByMonth = React.lazy(() => import('page/Stores/Attends/StoreAttendsByMonth'))

    return (
        <BrowserRouter>
            <NavBar />
            <Suspense fallback={null}>
                <Switch>
                    <Route path="/" component={Top} exact />
                    <Route path="/groups" component={Groups} exact />
                    <Route path="/stores/:id" component={Store} exact />
                    <Route path="/stores/:id/attends" component={StoreAttendsCalendar} exact />
                    <Route path="/stores/:id/attends/:year/:month" component={StoreAttendsByMonth} exact />
                    <Route path="/casts/:id" component={Cast} exact />
                    <Route path="/login" component={Login} exact />
                </Switch>
            </Suspense>
        </BrowserRouter>
    )
}
