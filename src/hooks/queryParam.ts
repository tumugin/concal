import { useCallback, useMemo } from 'react'
import { useQuery } from 'utils/query'
import { useHistory, useLocation } from 'react-router-dom'

export function useQueryString<T = undefined | string>(paramName: string, defaultValue?: T) {
    const location = useLocation()
    const history = useHistory()
    const query = useQuery()
    const currentParam = query.get(paramName)
    const setParam = useCallback(
        (newValue: string) => {
            const newQuery = new URLSearchParams(query)
            newQuery.set(paramName, newValue)
            history.push({
                ...location,
                search: newQuery.toString(),
            })
        },
        [history, location, paramName, query]
    )
    return [currentParam ?? (defaultValue as T), setParam] as const
}

export function useQueryNumber<T = undefined | number>(paramName: string, defaultValue?: T) {
    const location = useLocation()
    const history = useHistory()
    const query = useQuery()
    const currentParam = query.get(paramName)
    const parsedCurrentParam = useMemo(() => (currentParam ? parseInt(currentParam) : undefined), [currentParam])
    const setParam = useCallback(
        (newValue: number) => {
            const newQuery = new URLSearchParams(query)
            newQuery.set(paramName, newValue.toString())
            history.push({
                ...location,
                search: newQuery.toString(),
            })
        },
        [history, location, paramName, query]
    )
    return [parsedCurrentParam ?? (defaultValue as T), setParam] as const
}
