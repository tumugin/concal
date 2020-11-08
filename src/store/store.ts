import { getStore, Store } from 'api/store'
import { GlobalDispatch, GlobalStore, StoreProvider, StoreProviderType } from 'store/index'
import { useCallback } from 'react'
import produce from 'immer'

export interface StoreStore {
    stores: {
        [storeId: number]: Store | undefined
    }
}

export function createStoreStore() {
    const store: StoreStore = {
        stores: {},
    }
    return store
}

export interface StoreStoreReducers {
    'stores/setStore': (global: GlobalStore, dispatch: GlobalDispatch, store: Store) => void
}

export function initializeStoreStoreReducers(provider: StoreProviderType) {
    provider.addReducer('stores/setStore', (global, _, store: Store) => {
        return produce(global, (draftState) => {
            draftState.stores.stores[store.id] = store
        })
    })
}

export function useStore(storeId: number) {
    const [stores] = StoreProvider.useGlobal('stores')
    return stores.stores[storeId]
}

export function useLoadStore(storeId: number) {
    const dispatchStoresSetStore = StoreProvider.useDispatch('stores/setStore')
    return useCallback(async () => {
        const storeResponse = await getStore({ storeId })
        await dispatchStoresSetStore(storeResponse.store)
    }, [dispatchStoresSetStore, storeId])
}
