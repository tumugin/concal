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
import { ManageUser } from 'page/admin/users/manageUser'
import { CreateGroup } from 'page/admin/groups/createGroup'
import { ManageGroup } from 'page/admin/groups/manageGroup'
import { CreateStore } from 'page/admin/groups/createStore'
import { ManageStore } from 'page/admin/stores/manageStore'
import { CreateCast } from 'page/admin/casts/createCast'
import { ManageCast } from 'page/admin/casts/manageCast'
import { ManageBelongingStores } from 'page/admin/casts/manageBelongingStores'
import { ManageAttends } from 'page/admin/casts/manageAttends'
import { Groups } from 'page/Groups'
import { Store } from 'page/Stores'

export default function PageRouter() {
    return (
        <BrowserRouter>
            <NavBar />
            <Switch>
                <Route path="/" component={Top} exact />
                <Route path="/groups" component={Groups} exact />
                <Route path="/stores/:id" component={Store} exact />
                <Route path="/login" component={Login} exact />
                <Route path="/admin/users" component={AdminUsers} exact />
                <Route path="/admin/users/new" component={CreateUser} exact />
                <Route path="/admin/users/:id" component={ManageUser} exact />
                <Route path="/admin/casts" component={AdminCasts} exact />
                <Route path="/admin/casts/new" component={CreateCast} exact />
                <Route path="/admin/casts/:id" component={ManageCast} exact />
                <Route path="/admin/casts/:id/stores" component={ManageBelongingStores} exact />
                <Route path="/admin/casts/:id/attends" component={ManageAttends} exact />
                <Route path="/admin/groups" component={AdminGroups} exact />
                <Route path="/admin/groups/new" component={CreateGroup} exact />
                <Route path="/admin/groups/:id" component={ManageGroup} exact />
                <Route path="/admin/groups/:id/new_store" component={CreateStore} exact />
                <Route path="/admin/stores" component={AdminStores} exact />
                <Route path="/admin/stores/:id" component={ManageStore} exact />
            </Switch>
        </BrowserRouter>
    )
}
