import { PageWrapper } from 'components/PageWrapper'
import React, { useEffect, useState } from 'react'
import { Box, Heading } from 'rebass/styled-components'
import dayjs from 'dayjs'
import { useParams } from 'react-router-dom'
import { useLoadStoreAttends, useStoreAttends } from 'store/storeAttends'
import { Calendar, dateFnsLocalizer } from 'react-big-calendar'
import format from 'date-fns/format'
import parse from 'date-fns/parse'
import startOfWeek from 'date-fns/startOfWeek'
import getDay from 'date-fns/getDay'
import 'react-big-calendar/lib/css/react-big-calendar.css'
import styled from 'styled-components'
import ja from 'date-fns/locale/ja'

export function StoreAttends() {
    const { id } = useParams<{ id: string }>()
    const storeId = parseInt(id)
    const [year, setYear] = useState(dayjs().year())
    const [month, setMonth] = useState(dayjs().month() + 1)

    const loadStoreAttends = useLoadStoreAttends({ storeId, year, month })
    const storeAttends = useStoreAttends({ storeId, year, month })

    useEffect(() => {
        if (storeAttends === null) {
            void loadStoreAttends()
        }
    }, [loadStoreAttends, storeAttends])

    return (
        <PageWrapper>
            <Heading>店舗ごとの出勤一覧</Heading>
            <Box marginTop={3}>
                <CalenderArea />
            </Box>
        </PageWrapper>
    )
}

function wrappedLocaleFunction(
    date: Date | number,
    ft: string,
    options: {
        weekStartsOn?: 0 | 1 | 2 | 3 | 4 | 5 | 6
        firstWeekContainsDate?: number
        useAdditionalWeekYearTokens?: boolean
        useAdditionalDayOfYearTokens?: boolean
    }
) {
    // どうしてこうなった.... オプションで指定させてくれ....
    return format(date, ft, { ...options, locale: ja })
}

function CalenderArea() {
    const locales = {
        ja: ja,
    }
    const localizer = dateFnsLocalizer({
        format: wrappedLocaleFunction,
        parse,
        startOfWeek,
        getDay,
        locales,
    })
    return (
        <CalenderWrapper>
            <Calendar localizer={localizer} events={[]} startAccessor="start" endAccessor="end" />
        </CalenderWrapper>
    )
}

const CalenderWrapper = styled(Box)`
    height: calc(100vh - 300px);
`
