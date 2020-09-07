import { useState } from 'react'
import constate from 'constate'
import { createUserStore, UserStore } from 'store/user'

interface Store {
    user: UserStore
}

function createInitialStore(): Store {
    return {
        user: createUserStore(),
    }
}

export const [StoreProvider, useStoreContext] = constate(() => {
    const [store, setStore] = useState(createInitialStore())
    return { store, setStore }
})
