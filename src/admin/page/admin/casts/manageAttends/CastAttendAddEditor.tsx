import { InfoGrid } from 'components/InfoGrid'
import { Input, Select } from '@rebass/forms/styled-components'
import { Box, Button } from 'rebass/styled-components'
import React, { ChangeEvent, useCallback, useEffect, useState } from 'react'
import { StoreData } from 'admin/api/store'
import { AdminHorizontalButtons } from 'admin/components/AdminInfoBoxWrapper'
import styled from 'styled-components'
import dayjs from 'dayjs'
import { addAttend } from 'admin/api/attends'
import { useApiToken } from 'admin/store/user'
import toastr from 'toastr'
import { unreachableCode } from 'types/util'

export function CastAttendAddEditor({
    castId,
    stores,
    onCastAttendAdd,
}: {
    castId: number
    stores: StoreData[]
    onCastAttendAdd: () => void
}) {
    const apiToken = useApiToken()

    const [selectedStore, setSelectedStore] = useState<StoreData | null>(null)
    const [selectedStoreId, setSelectedStoreId] = useState('')
    const [selectedStartDateTime, setSelectedStartDateTime] = useState('')
    const [selectedEndDateTime, setSelectedEndDateTime] = useState('')
    const [attendComment, setAttendComment] = useState('')

    const onSelectStore = useCallback(
        (event: ChangeEvent<HTMLSelectElement>) => {
            setSelectedStore(stores.find((item) => item.id.toString() === event.target.value) ?? null)
        },
        [stores]
    )

    const onAddAttend = useCallback(async () => {
        if (!apiToken) {
            return
        }
        const startTime = dayjs(selectedStartDateTime)
        const endTime = dayjs(selectedEndDateTime)
        try {
            await addAttend(
                { apiToken },
                {
                    castId,
                    storeId:
                        (selectedStoreId ? parseInt(selectedStoreId) : null) || selectedStore?.id || unreachableCode(),
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
        selectedEndDateTime,
        selectedStartDateTime,
        selectedStore?.id,
        selectedStoreId,
    ])

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
                        name: 'or 出勤店舗ID',
                        value: (
                            <Input
                                type="number"
                                value={selectedStoreId}
                                placeholder="指定する店舗のIDを直接指定(例:10)"
                                onChange={(event) => setSelectedStoreId(event.target.value)}
                            />
                        ),
                    },
                    {
                        name: '出勤開始日時',
                        value: (
                            <Input
                                type="datetime-local"
                                value={selectedStartDateTime}
                                onChange={(event) => setSelectedStartDateTime(event.target.value)}
                            />
                        ),
                    },
                    {
                        name: '出勤終了日時',
                        value: (
                            <Input
                                type="datetime-local"
                                value={selectedEndDateTime}
                                onChange={(event) => setSelectedEndDateTime(event.target.value)}
                            />
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
            <Button marginTop={3} onClick={onAddAttend}>
                追加する
            </Button>
        </Box>
    )
}

const SelectOptionStyle = styled.option`
    background-color: ${({ theme }) => theme.colors.background};
`
