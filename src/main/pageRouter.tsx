import React, { Suspense } from 'react'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import { NavBar } from 'main/components/NavBar'

export default function PageRouter() {
    const Top = React.lazy(() => import('main/page/Top'))
    const Groups = React.lazy(() => import('main/page/Groups'))
    const Store = React.lazy(() => import('main/page/Stores/show'))
    const StoreAttendsCalendar = React.lazy(() => import('main/page/StoreAttendsCalendar'))
    const Cast = React.lazy(() => import('main/page/Casts/show'))
    const Login = React.lazy(() => import('main/page/Login'))
    const StoreAttendsByMonth = React.lazy(() => import('main/page/Stores/Attends/StoreAttendsByMonth'))
    const StoreAttendsByDate = React.lazy(() => import('main/page/Stores/Attends/StoreAttendsByDate'))

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
                    <Route path="/stores/:id/attends/:year/:month/:date" component={StoreAttendsByDate} exact />
                    <Route path="/casts/:id" component={Cast} exact />
                    <Route path="/login" component={Login} exact />
                </Switch>
            </Suspense>
        </BrowserRouter>
    )
}
