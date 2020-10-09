import React, { useCallback, useEffect, useMemo, useState } from 'react'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import { PageWrapper } from 'components/PageWrapper'
import { useParams } from 'react-router-dom'
import { useApiToken } from 'store/user'
import { CastData, getCast } from 'api/admin/casts'
import { AdminInfoBox } from 'components/AdminInfoBox'
import { AdminInfoBoxWrapper } from 'components/AdminInfoBoxWrapper'
import dayjs from 'dayjs'
import { getAttends } from 'api/admin/attends'

export function ManageAttends() {
    const { id } = useParams<{ id: string }>()
    const apiToken = useApiToken()
    const [castData, setCastData] = useState<CastData | null>(null)
    const [selectedYear, setSelectedYear] = useState(dayjs().year())
    const [selectedMonth, setSelectedMonth] = useState(dayjs().month())
    const currentDayJsDate = useMemo(
        () => dayjs().year(selectedYear).month(selectedMonth).date(1).hour(0).minute(0).second(0).millisecond(0),
        [selectedMonth, selectedYear]
    )

    const fetchCastData = useCallback(async () => {
        if (!apiToken) {
            return
        }
        setCastData((await getCast({ apiToken }, { castId: parseInt(id) })).cast)
    }, [apiToken, id])
    const fetchAttendData = useCallback(async () => {
        if (!apiToken) {
            return
        }
        await getAttends(
            { apiToken },
            {
                castId: parseInt(id),
                startTime: currentDayJsDate.toISOString(),
                endTime: currentDayJsDate.add(1, 'month').toISOString(),
            }
        )
    }, [apiToken, currentDayJsDate, id])
    const onNextMonth = useCallback(() => {
        const newDate = dayjs().year(selectedYear).month(selectedMonth).add(1, 'month')
        setSelectedYear(newDate.year())
        setSelectedMonth(newDate.month())
    }, [selectedMonth, selectedYear])
    const onPrevMonth = useCallback(() => {
        const newDate = dayjs().year(selectedYear).month(selectedMonth).subtract(1, 'month')
        setSelectedYear(newDate.year())
        setSelectedMonth(newDate.month())
    }, [selectedMonth, selectedYear])

    useEffect(() => {
        void fetchCastData()
    }, [fetchCastData])
    useEffect(() => {
        void fetchAttendData()
    }, [fetchAttendData])

    if (!castData) {
        return null
    }

    return (
        <PageWrapper>
            <Heading>キャスト出勤管理(表示中のキャスト: {castData.castName})</Heading>
            <AdminInfoBoxWrapper>
                <Box>
                    <Box fontSize={3}>{currentDayJsDate.format('YYYY年MM月')}度勤務予定</Box>
                    <Flex marginTop={2}>
                        <Button marginRight={2} onClick={onPrevMonth}>
                            前月
                        </Button>
                        <Button onClick={onNextMonth}>次月</Button>
                    </Flex>
                </Box>
                <AdminInfoBox header="キャスト出勤一覧"></AdminInfoBox>
                <AdminInfoBox header="キャスト出勤追加"></AdminInfoBox>
            </AdminInfoBoxWrapper>
        </PageWrapper>
    )
}
