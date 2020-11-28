import { InfoGrid } from 'components/InfoGrid'
import { Input, Select } from '@rebass/forms/styled-components'
import { Box, Button, Flex } from 'rebass/styled-components'
import React, { ChangeEvent, useCallback, useEffect, useState } from 'react'
import { StoreData } from 'admin/api/store'
import { AdminHorizontalButtons } from 'admin/components/AdminInfoBoxWrapper'
import { Note } from 'components/Note'
import styled from 'styled-components'
import dayjs from 'dayjs'
import { addAttend } from 'admin/api/attends'
import { useApiToken } from 'admin/store/user'
import toastr from 'toastr'

export function CastAttendAddEditor({
    selectedYear,
    selectedMonth,
    castId,
    stores,
    onCastAttendAdd,
}: {
    selectedYear: number
    selectedMonth: number
    castId: number
    stores: StoreData[]
    onCastAttendAdd: () => void
}) {
    const apiToken = useApiToken()

    const [selectedStore, setSelectedStore] = useState<StoreData | null>(null)
    const [selectedStartDateTime, setSelectedStartDateTime] = useState('')
    const [selectedEndDateTime, setSelectedEndDateTime] = useState('')
    const [attendComment, setAttendComment] = useState('')

    const onSelectStore = useCallback(
        (event: ChangeEvent<HTMLSelectElement>) => {
            setSelectedStore(stores.find((item) => item.id.toString() === event.target.value) ?? null)
        },
        [stores]
    )

    const onSetTimes = useCallback((startTime: string, endTime: string) => {
        const startTimeDayJs = dayjs(startTime)
        const endTimeDayJs = dayjs(endTime)
        setSelectedStartDateTime(startTimeDayJs.toISOString())
        setSelectedEndDateTime(endTimeDayJs.toISOString())
    }, [])

    const onAddAttend = useCallback(async () => {
        if (!apiToken || !selectedStore) {
            return
        }
        const startTime = dayjs(selectedStartDateTime)
        const endTime = dayjs(selectedEndDateTime)
        try {
            await addAttend(
                { apiToken },
                {
                    castId,
                    storeId: selectedStore.id,
                    startTime: startTime.toISOString(),
                    endTime: endTime.toISOString(),
                    attendInfo: attendComment,
                }
            )
            onCastAttendAdd()
            toastr.success('キャストの出勤を追加しました')
        } catch {
            toastr.error('エラーが発生しました')
        }
    }, [apiToken, attendComment, castId, onCastAttendAdd, selectedEndDateTime, selectedStartDateTime, selectedStore])

    useEffect(() => {
        if (stores[0]) {
            setSelectedStore(stores[0])
        }
    }, [stores])

    return (
        <Box>
            <InfoGrid
                data={[
                    {
                        name: '出勤店舗',
                        value: (
                            <Select sx={{ width: '500px' }} onChange={onSelectStore} value={selectedStore?.id}>
                                {stores.map((item, index) => (
                                    <SelectOptionStyle key={index} value={item.id}>
                                        {item.storeName}
                                    </SelectOptionStyle>
                                ))}
                            </Select>
                        ),
                    },
                    {
                        name: '出勤開始日時',
                        value: <Input type="datetime-local" />,
                    },
                    {
                        name: '出勤終了日時',
                        value: <Input type="datetime-local" />,
                    },
                    {
                        name: '出勤コメント',
                        value: (
                            <Input value={attendComment} onChange={(event) => setAttendComment(event.target.value)} />
                        ),
                    },
                ]}
            />
            <Box marginTop={3} fontSize={3}>
                アフィリア用プリセット
            </Box>
            <AdminHorizontalButtons marginTop={3}>
                <Button variant="outline" onClick={() => onSetTimes('18:00', '23:00')}>
                    カフェ店舗平日
                </Button>
                <Button variant="outline" onClick={() => onSetTimes('15:00', '19:00')}>
                    カフェ店舗早番休日
                </Button>
                <Button variant="outline" onClick={() => onSetTimes('19:00', '23:00')}>
                    カフェ店舗遅番休日
                </Button>
                <Button variant="outline" onClick={() => onSetTimes('15:00', '23:00')}>
                    カフェ店舗通し休日
                </Button>
                <Button variant="outline" onClick={() => onSetTimes('18:00', '23:00')}>
                    バー店舗早番
                </Button>
                <Button variant="outline" onClick={() => onSetTimes('23:00', '19:00')}>
                    バー店舗遅番
                </Button>
            </AdminHorizontalButtons>
            <Button marginTop={3} onClick={onAddAttend}>
                追加する
            </Button>
            <Box>
                <Note tight>終了時間が開始時間より前の場合、翌日の扱いで登録されます</Note>
            </Box>
        </Box>
    )
}

const SelectOptionStyle = styled.option`
    background-color: ${({ theme }) => theme.colors.background};
`
