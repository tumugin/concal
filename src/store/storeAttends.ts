import { getStoreAttend, StoreAttendData } from 'api/storeAttends'
import { GlobalDispatch, GlobalStore, StoreProvider } from 'store/index'
import { useCallback } from 'react'
import dayjs from 'dayjs'
import produce from 'immer'

export interface StoreAttendsStore {
    stores: {
        [storeId: number]:
            | {
                  [year: number]:
                      | {
                            [month: number]: StoreAttendData[] | undefined
                        }
                      | undefined
              }
            | undefined
    }
}

export function createStoreAttendsStore() {
    const store: StoreAttendsStore = {
        stores: {},
    }
    return store
}

interface SetStoreAttendsOfMonthPayload {
    storeId: number
    year: number
    month: number
    attends: StoreAttendData[]
}

export interface StoreAttendsReducers {
    'storeAttends/setStoreAttendsOfMonth': (
        global: GlobalStore,
        dispatch: GlobalDispatch,
        payload: SetStoreAttendsOfMonthPayload
    ) => void
}

export function initializeStoreAttendsReducers() {
    StoreProvider.addReducer(
        'storeAttends/setStoreAttendsOfMonth',
        (global, _, { storeId, year, month, attends }: SetStoreAttendsOfMonthPayload) => {
            return produce(global, (draftState) => {
                const storesData = draftState.storeAttends.stores[storeId]
                const yearData = storesData ? storesData[year] : undefined
                draftState.storeAttends.stores[storeId] = {
                    ...draftState.storeAttends.stores[storeId],
                    [year]: {
                        ...yearData,
                        [month]: attends,
                    },
                }
            })
        }
    )
}

export function useStoreAttends({ storeId, year, month }: { storeId: number; year: number; month: number }) {
    const [storeAttends] = StoreProvider.useGlobal('storeAttends')
    const store = storeAttends.stores[storeId]
    if (!store) {
        return null
    }
    const storeYear = store[year]
    if (!storeYear) {
        return null
    }
    const storeMonth = storeYear[month]
    if (!storeMonth) {
        return null
    }
    return storeMonth
}

export function useLoadStoreAttends({ storeId, year, month }: { storeId: number; year: number; month: number }) {
    const dispatchSetStoreAttendsOfMonth = StoreProvider.useDispatch('storeAttends/setStoreAttendsOfMonth')
    return useCallback(async () => {
        const startDate = dayjs().year(year).month(month).date(1)
        const endDate = startDate.add(startDate.daysInMonth(), 'day')
        const result = await getStoreAttend({
            storeId,
            startDate: startDate.format('YYYY-MM-DD'),
            endDate: endDate.format('YYYY-MM-DD'),
        })
        await dispatchSetStoreAttendsOfMonth({ storeId, year, month, attends: result.data.attends })
    }, [dispatchSetStoreAttendsOfMonth, month, storeId, year])
}
