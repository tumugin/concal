import React, { Suspense } from 'react'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import { Forbidden } from 'admin/page/Forbidden'
import { useUser } from 'admin/store/user'
import { AdminNavBar } from 'admin/components/AdminNavBar'
import AdminAdminUsers from 'admin/page/admin/admin-users'
import CreateAdminUser from 'admin/page/admin/admin-users/CreateAdminUser'
import ManageAdminUser from 'admin/page/admin/admin-users/ManageAdminUser'

export default function AdminPageRouter() {
    const isAdmin = useUser().self?.userPrivilege !== undefined
    const isSuperAdmin = useUser().self?.userPrivilege === 'super_admin'
    const AdminCasts = React.lazy(() => import('admin/page/admin/casts'))
    const AdminGroups = React.lazy(() => import('admin/page/admin/groups'))
    const AdminStores = React.lazy(() => import('admin/page/admin/stores'))
    const AdminUsers = React.lazy(() => import('admin/page/admin/users'))
    const CreateUser = React.lazy(() => import('admin/page/admin/users/createUser'))
    const ManageUser = React.lazy(() => import('admin/page/admin/users/manageUser'))
    const CreateGroup = React.lazy(() => import('admin/page/admin/groups/createGroup'))
    const ManageGroup = React.lazy(() => import('admin/page/admin/groups/manageGroup'))
    const CreateStore = React.lazy(() => import('admin/page/admin/groups/createStore'))
    const ManageStore = React.lazy(() => import('admin/page/admin/stores/manageStore'))
    const CreateCast = React.lazy(() => import('admin/page/admin/casts/createCast'))
    const ManageCast = React.lazy(() => import('admin/page/admin/casts/manageCast'))
    const ManageBelongingStores = React.lazy(() => import('admin/page/admin/casts/manageBelongingStores'))
    const ManageAttends = React.lazy(() => import('admin/page/admin/casts/manageAttends'))
    const AdminTop = React.lazy(() => import('admin/page/admin/AdminTop'))
    const AdminLogin = React.lazy(() => import('admin/page/admin/AdminLogin'))

    return (
        <BrowserRouter>
            <AdminNavBar />
            <Suspense fallback={null}>
                <Switch>
                    <Route path="/admin/" component={isAdmin ? AdminTop : AdminLogin} exact />
                    <Route path="/admin/login" component={AdminLogin} exact />
                    <Route path="/admin/users" component={isAdmin ? AdminUsers : Forbidden} exact />
                    <Route path="/admin/users/create" component={isAdmin ? CreateUser : Forbidden} exact />
                    <Route path="/admin/users/:id" component={isAdmin ? ManageUser : Forbidden} exact />
                    <Route path="/admin/admin_users" component={isSuperAdmin ? AdminAdminUsers : Forbidden} exact />
                    <Route
                        path="/admin/admin_users/create"
                        component={isSuperAdmin ? CreateAdminUser : Forbidden}
                        exact
                    />
                    <Route path="/admin/admin_users/:id" component={isSuperAdmin ? ManageAdminUser : Forbidden} exact />
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
