import { AdminInfoGrid } from 'components/AdminInfoGrid'
import { Input, Select } from '@rebass/forms/styled-components'
import { Box, Button, Flex } from 'rebass/styled-components'
import React, { ChangeEvent, useCallback, useEffect, useState } from 'react'
import { StoreData } from 'api/admin/store'
import { AdminHorizontalButtons } from 'components/AdminInfoBoxWrapper'
import { Note } from 'components/Note'
import styled from 'styled-components'
import dayjs from 'dayjs'
import { addAttend } from 'api/admin/attends'
import { useApiToken } from 'store/user'
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
    const [selectedStartDay, setSelectedStartDay] = useState('1')
    const [selectedStartHour, setSelectedStartHour] = useState('0')
    const [selectedStartMin, setSelectedStartMin] = useState('0')
    const [selectedEndHour, setSelectedEndHour] = useState('0')
    const [selectedEndMin, setSelectedEndMin] = useState('0')
    const [attendComment, setAttendComment] = useState('')

    const onSelectStore = useCallback(
        (event: ChangeEvent<HTMLSelectElement>) => {
            setSelectedStore(stores.find((item) => item.id.toString() === event.target.value) ?? null)
        },
        [stores]
    )

    const onSetTimes = useCallback((startTime: string, endTime: string) => {
        const startTimeDayJs = dayjs(`2020/01/01 ${startTime}`)
        const endTimeDayJs = dayjs(`2020/01/01 ${endTime}`)
        setSelectedStartHour(startTimeDayJs.hour().toString())
        setSelectedStartMin(startTimeDayJs.minute().toString())
        setSelectedEndHour(endTimeDayJs.hour().toString())
        setSelectedEndMin(endTimeDayJs.minute().toString())
    }, [])

    const onAddAttend = useCallback(async () => {
        if (!apiToken || !selectedStore) {
            return
        }
        const startTime = dayjs()
            .year(selectedYear)
            .month(selectedMonth)
            .hour(parseInt(selectedStartHour))
            .minute(parseInt(selectedStartMin))
        let endTime = startTime.hour(parseInt(selectedEndHour)).minute(parseInt(selectedEndMin))
        if (startTime.isAfter(endTime)) {
            endTime = endTime.add(1, 'day')
        }
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
    }, [
        apiToken,
        attendComment,
        castId,
        onCastAttendAdd,
        selectedEndHour,
        selectedEndMin,
        selectedMonth,
        selectedStartHour,
        selectedStartMin,
        selectedStore,
        selectedYear,
    ])

    useEffect(() => {
        if (stores[0]) {
            setSelectedStore(stores[0])
        }
    }, [stores])

    return (
        <Box>
            <AdminInfoGrid
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
                        value: (
                            <Flex sx={{ alignItems: 'center' }}>
                                <Input
                                    sx={{ width: '100px' }}
                                    type="number"
                                    value={selectedStartDay}
                                    onChange={(event) => setSelectedStartDay(event.target.value)}
                                />
                                <Box marginLeft={1}>日</Box>
                                <Input
                                    sx={{ width: '100px' }}
                                    marginLeft={3}
                                    type="number"
                                    value={selectedStartHour}
                                    onChange={(event) => setSelectedStartHour(event.target.value)}
                                />
                                <Box marginLeft={1}>時</Box>
                                <Input
                                    sx={{ width: '100px' }}
                                    marginLeft={1}
                                    type="number"
                                    value={selectedStartMin}
                                    onChange={(event) => setSelectedStartMin(event.target.value)}
                                />
                                <Box marginLeft={1}>分</Box>
                            </Flex>
                        ),
                    },
                    {
                        name: '出勤終了日時',
                        value: (
                            <Flex sx={{ alignItems: 'center' }}>
                                <Input
                                    sx={{ width: '100px' }}
                                    type="number"
                                    value={selectedEndHour}
                                    onChange={(event) => setSelectedEndHour(event.target.value)}
                                />
                                <Box marginLeft={1}>時</Box>
                                <Input
                                    sx={{ width: '100px' }}
                                    marginLeft={1}
                                    type="number"
                                    value={selectedEndMin}
                                    onChange={(event) => setSelectedEndMin(event.target.value)}
                                />
                                <Box marginLeft={1}>分</Box>
                            </Flex>
                        ),
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
