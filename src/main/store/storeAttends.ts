import { getStoreAttend, StoreAttendData } from 'main/api/storeAttends'
import { GlobalDispatch, GlobalStore, StoreProvider, StoreProviderType } from 'main/store/index'
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

export function initializeStoreAttendsReducers(provider: StoreProviderType) {
    provider.addReducer(
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
        const startDate = dayjs()
            .year(year)
            .month(month - 1)
            .date(1)
            .hour(0)
            .minute(0)
            .second(0)
        const endDate = startDate.add(startDate.daysInMonth(), 'day')
        const result = await getStoreAttend({
            storeId,
            startDate: startDate.toISOString(),
            endDate: endDate.toISOString(),
        })
        await dispatchSetStoreAttendsOfMonth({ storeId, year, month, attends: result.data.attends })
    }, [dispatchSetStoreAttendsOfMonth, month, storeId, year])
}
