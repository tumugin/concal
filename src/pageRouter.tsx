import React, { Suspense } from 'react'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import { NavBar } from 'components/NavBar'
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
    const AdminCasts = React.lazy(() => import('page/admin/casts'))
    const AdminGroups = React.lazy(() => import('page/admin/groups'))
    const AdminStores = React.lazy(() => import('page/admin/stores'))
    const AdminUsers = React.lazy(() => import('page/admin/users'))
    const CreateUser = React.lazy(() => import('page/admin/users/createUser'))
    const ManageUser = React.lazy(() => import('page/admin/users/manageUser'))
    const CreateGroup = React.lazy(() => import('page/admin/groups/createGroup'))
    const ManageGroup = React.lazy(() => import('page/admin/groups/manageGroup'))
    const CreateStore = React.lazy(() => import('page/admin/groups/createStore'))
    const ManageStore = React.lazy(() => import('page/admin/stores/manageStore'))
    const CreateCast = React.lazy(() => import('page/admin/casts/createCast'))
    const ManageCast = React.lazy(() => import('page/admin/casts/manageCast'))
    const ManageBelongingStores = React.lazy(() => import('page/admin/casts/manageBelongingStores'))
    const ManageAttends = React.lazy(() => import('page/admin/casts/manageAttends'))

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
