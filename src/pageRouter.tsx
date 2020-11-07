import React, { Suspense } from 'react'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import { NavBar } from 'components/NavBar'
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
import { useUser } from 'store/user'
import { Forbidden } from 'page/Forbidden'

export default function PageRouter() {
    const isAdmin = useUser().self?.userPrivilege === 'admin'

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
                    {/* 管理者ページ */}
                    <Route path="/admin/users" component={isAdmin ? AdminUsers : Forbidden} exact />
                    <Route path="/admin/users/create" component={isAdmin ? CreateUser : Forbidden} exact />
                    <Route path="/admin/users/:id" component={isAdmin ? ManageUser : Forbidden} exact />
                    <Route path="/admin/casts" component={isAdmin ? AdminCasts : Forbidden} exact />
                    <Route path="/admin/casts/create" component={isAdmin ? CreateCast : Forbidden} exact />
                    <Route path="/admin/casts/:id" component={isAdmin ? ManageCast : Forbidden} exact />
                    <Route
                        path="/admin/casts/:id/stores"
                        component={isAdmin ? ManageBelongingStores : Forbidden}
                        exact
                    />
                    <Route path="/admin/casts/:id/attends" component={isAdmin ? ManageAttends : Forbidden} exact />
                    <Route path="/admin/groups" component={isAdmin ? AdminGroups : Forbidden} exact />
                    <Route path="/admin/groups/create" component={isAdmin ? CreateGroup : Forbidden} exact />
                    <Route path="/admin/groups/:id" component={isAdmin ? ManageGroup : Forbidden} exact />
                    <Route path="/admin/groups/:id/stores/create" component={isAdmin ? CreateStore : Forbidden} exact />
                    <Route path="/admin/stores" component={isAdmin ? AdminStores : Forbidden} exact />
                    <Route path="/admin/stores/:id" component={isAdmin ? ManageStore : Forbidden} exact />
                </Switch>
            </Suspense>
        </BrowserRouter>
    )
}
