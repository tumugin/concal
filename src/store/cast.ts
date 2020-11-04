import { Cast, getCast } from 'api/cast'
import { GlobalDispatch, GlobalStore, StoreProvider } from 'store/index'
import produce from 'immer'
import { useCallback } from 'react'

export interface CastStore {
    casts: {
        [castId: number]: Cast | undefined
    }
}

export function createCastStore() {
    const store: CastStore = {
        casts: {},
    }
    return store
}

export interface CastStoreReducers {
    'casts/setCast': (global: GlobalStore, dispatch: GlobalDispatch, cast: Cast) => void
}

export function initializeCastStoreReducers() {
    StoreProvider.addReducer('casts/setCast', (global, _, cast: Cast) => {
        return produce(global, (draftState) => {
            draftState.casts.casts[cast.id] = cast
        })
    })
}

export function useCast(castId: number) {
    const [casts] = StoreProvider.useGlobal('casts')
    return casts.casts[castId]
}

export function useLoadCast(castId: number) {
    const dispatchSetCast = StoreProvider.useDispatch('casts/setCast')
    return useCallback(async () => {
        const castResponse = await getCast({ castId })
        await dispatchSetCast(castResponse.data.cast)
    }, [castId, dispatchSetCast])
}
