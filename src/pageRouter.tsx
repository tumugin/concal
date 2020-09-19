import React from 'react'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import { Top } from 'page/Top'
import { NavBar } from 'components/NavBar'
import { Login } from 'page/Login'
import { AdminCasts } from 'page/admin/casts'
import { AdminGroups } from 'page/admin/groups'
import { AdminStores } from 'page/admin/stores'
import { AdminUsers } from 'page/admin/users'
import { CreateUser } from 'page/admin/users/createUser'

export default function PageRouter() {
    return (
        <BrowserRouter>
            <NavBar />
            <Switch>
                <Route path="/" component={Top} exact />
                <Route path="/login" component={Login} exact />
                <Route path="/admin/users" component={AdminUsers} exact />
                <Route path="/admin/users/new" component={CreateUser} exact />
                <Route path="/admin/casts" component={AdminCasts} exact />
                <Route path="/admin/groups" component={AdminGroups} exact />
                <Route path="/admin/stores" component={AdminStores} exact />
            </Switch>
        </BrowserRouter>
    )
}
