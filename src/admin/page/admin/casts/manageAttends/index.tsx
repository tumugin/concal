import React, { useCallback, useEffect, useMemo, useState } from 'react'
import { Box, Button, Flex, Heading } from 'rebass/styled-components'
import { PageWrapper } from 'components/PageWrapper'
import { useParams } from 'react-router-dom'
import { useApiToken } from 'admin/store/user'
import { CastData, getCast } from 'admin/api/casts'
import { AdminInfoBox } from 'admin/components/AdminInfoBox'
import { AdminInfoBoxWrapper } from 'admin/components/AdminInfoBoxWrapper'
import dayjs from 'dayjs'
import { AttendDataDetails, deleteAttend, getAttends } from 'admin/api/attends'
import { CastAttendAddEditor } from 'admin/page/admin/casts/manageAttends/CastAttendAddEditor'
import { CastAttendEditBox } from 'admin/page/admin/casts/manageAttends/CastAttendEditBox'
import styled from 'styled-components'
import { unreachableCode } from 'types/util'
import toastr from 'toastr'

export default function ManageAttends() {
    const { id } = useParams<{ id: string }>()
    const castId = parseInt(id)
    const apiToken = useApiToken()
    const [castData, setCastData] = useState<CastData | null>(null)
    const [selectedYear, setSelectedYear] = useState(dayjs().year())
    const [selectedMonth, setSelectedMonth] = useState(dayjs().month())
    const currentDayJsDate = useMemo(
        () => dayjs().year(selectedYear).month(selectedMonth).date(1).hour(0).minute(0).second(0).millisecond(0),
        [selectedMonth, selectedYear]
    )
    const [attends, setAttends] = useState<AttendDataDetails[]>([])

    const fetchCastData = useCallback(async () => {
        if (!apiToken) {
            return
        }
        setCastData((await getCast({ apiToken }, { castId })).cast)
    }, [apiToken, castId])
    const fetchAttendData = useCallback(async () => {
        if (!apiToken) {
            return
        }
        setAttends(
            (
                await getAttends(
                    { apiToken },
                    {
                        castId,
                        startTime: currentDayJsDate.toISOString(),
                        endTime: currentDayJsDate.add(1, 'month').toISOString(),
                    }
                )
            ).attends
        )
    }, [apiToken, castId, currentDayJsDate])
    const onAttendDelete = useCallback(
        async (attendData: AttendDataDetails) => {
            await deleteAttend({ apiToken: apiToken ?? unreachableCode() }, { attendId: attendData.id })
            await fetchAttendData()
            toastr.success('出勤を削除しました')
        },
        [apiToken, fetchAttendData]
    )
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
                <AdminInfoBox header="キャスト出勤一覧">
                    {attends.length === 0 && <Box>出勤がありません</Box>}
                    <AttendsGrid>
                        {attends.map((item, index) => (
                            <CastAttendEditBox attendData={item} key={index} onDelete={onAttendDelete} />
                        ))}
                    </AttendsGrid>
                </AdminInfoBox>
                <AdminInfoBox header="キャスト出勤追加">
                    <CastAttendAddEditor
                        selectedYear={selectedYear}
                        selectedMonth={selectedMonth}
                        castId={castId}
                        stores={castData.stores}
                        onCastAttendAdd={fetchAttendData}
                    />
                </AdminInfoBox>
            </AdminInfoBoxWrapper>
        </PageWrapper>
    )
}

const AttendsGrid = styled.div`
    display: grid;
    grid-auto-flow: row;
    grid-row-gap: 8px;
`
