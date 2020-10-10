import { AdminInfoGrid } from 'components/AdminInfoGrid'
import { Input, Select } from '@rebass/forms/styled-components'
import { Box, Button, Flex } from 'rebass/styled-components'
import React from 'react'
import { StoreData } from 'api/admin/store'
import { AdminHorizontalButtons } from 'components/AdminInfoBoxWrapper'
import { Note } from 'components/Note'

export function CastAttendAddEditor({
    selectedYear,
    selectedMonth,
    castId,
    stores,
}: {
    selectedYear: number
    selectedMonth: number
    castId: number
    stores: StoreData[]
}) {
    return (
        <Box>
            <AdminInfoGrid
                data={[
                    {
                        name: '出勤店舗',
                        value: <Select sx={{ width: '500px' }}></Select>,
                    },
                    {
                        name: '出勤開始日時',
                        value: (
                            <Flex sx={{ alignItems: 'center' }}>
                                <Input sx={{ width: '100px' }} type="number" />
                                <Box marginLeft={1}>日</Box>
                                <Input sx={{ width: '100px' }} marginLeft={3} type="number" />
                                <Box marginLeft={1}>時</Box>
                                <Input sx={{ width: '100px' }} marginLeft={1} type="number" />
                                <Box marginLeft={1}>分</Box>
                            </Flex>
                        ),
                    },
                    {
                        name: '出勤終了日時',
                        value: (
                            <Flex sx={{ alignItems: 'center' }}>
                                <Input sx={{ width: '100px' }} type="number" />
                                <Box marginLeft={1}>時</Box>
                                <Input sx={{ width: '100px' }} marginLeft={1} type="number" />
                                <Box marginLeft={1}>分</Box>
                            </Flex>
                        ),
                    },
                    {
                        name: '出勤コメント',
                        value: <Input />,
                    },
                ]}
            />
            <AdminHorizontalButtons marginTop={3}>
                <Button variant="outline">カフェ店舗早番</Button>
                <Button variant="outline">カフェ店舗遅番</Button>
                <Button variant="outline">バー店舗早番</Button>
                <Button variant="outline">バー店舗遅番</Button>
            </AdminHorizontalButtons>
            <Button marginTop={3}>追加する</Button>
            <Box>
                <Note tight>終了時間が開始時間より前の場合、翌日の扱いで登録されます</Note>
            </Box>
        </Box>
    )
}
