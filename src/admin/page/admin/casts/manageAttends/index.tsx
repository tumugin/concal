import React, { useCallback, useEffect, useState } from 'react'
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
import { Input } from '@rebass/forms/styled-components'

export default function ManageAttends() {
    const { id } = useParams<{ id: string }>()
    const castId = parseInt(id)
    const apiToken = useApiToken()
    const [castData, setCastData] = useState<CastData | null>(null)
    const [currentDayJsDate, setCurrentDayJsDate] = useState(dayjs())
    const [selectedYearMonth, setSelectedYearMonth] = useState(dayjs().format('YYYY-MM'))
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
        const currentDayJs = dayjs(selectedYearMonth).date(1).hour(0).minute(0).second(0).millisecond(0)
        setAttends(
            (
                await getAttends(
                    { apiToken },
                    {
                        castId,
                        startTime: currentDayJs.toISOString(),
                        endTime: currentDayJs.add(1, 'month').toISOString(),
                    }
                )
            ).attends
        )
        setCurrentDayJsDate(currentDayJs)
    }, [apiToken, castId, selectedYearMonth])
    const onAttendDelete = useCallback(
        async (attendData: AttendDataDetails) => {
            await deleteAttend({ apiToken: apiToken ?? unreachableCode() }, { attendId: attendData.id })
            await fetchAttendData()
            toastr.success('出勤を削除しました')
        },
        [apiToken, fetchAttendData]
    )

    useEffect(() => {
        void fetchCastData()
    }, [fetchCastData])
    useEffect(() => {
        void fetchAttendData()
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [])

    if (!castData) {
        return null
    }

    return (
        <PageWrapper>
            <Heading>{castData.castName}の出勤管理</Heading>
            <AdminInfoBoxWrapper>
                <Flex>
                    <Input
                        type="month"
                        sx={{ maxWidth: '300px' }}
                        value={selectedYearMonth}
                        onChange={(event) => setSelectedYearMonth(event.target.value)}
                    />
                    <Button ml={2} onClick={fetchAttendData}>
                        表示
                    </Button>
                </Flex>
                <AdminInfoBox header={`${currentDayJsDate.format('YYYY年MM月')}度勤務予定`}>
                    {attends.length === 0 && <Box>出勤がありません</Box>}
                    <AttendsGrid>
                        {attends.map((item, index) => (
                            <CastAttendEditBox attendData={item} key={index} onDelete={onAttendDelete} />
                        ))}
                    </AttendsGrid>
                </AdminInfoBox>
                <AdminInfoBox header="キャスト出勤追加">
                    <CastAttendAddEditor castId={castId} stores={castData.stores} onCastAttendAdd={fetchAttendData} />
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
