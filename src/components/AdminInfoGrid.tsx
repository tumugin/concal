import React, { ReactNode } from 'react'
import styled from 'styled-components'
import { Text } from 'rebass/styled-components'

export function AdminInfoGrid({ data }: { data: { name: string; value: ReactNode }[] }) {
    return (
        <GridWrapper>
            {data.map((item, index) => (
                <React.Fragment key={index}>
                    <Text sx={{ fontWeight: 'bold' }}>{item.name}</Text>
                    <Text>{item.value}</Text>
                </React.Fragment>
            ))}
        </GridWrapper>
    )
}

const GridWrapper = styled.div`
    display: grid;
    width: fit-content;
    grid-auto-flow: row;
    grid-column-gap: 48px;
    grid-row-gap: 16px;
    grid-template-columns: auto auto;
    align-items: center;
`
