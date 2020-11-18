import React, { ReactNode } from 'react'
import styled from 'styled-components'
import { Text } from 'rebass/styled-components'

export function InfoGrid({ data }: { data: { name: string; value: ReactNode }[] }) {
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
    /* FIXME: Swalの中で使うとthemeが虚無になっていておかしくなるので暫定処置としてthemeが無ければ値を直打ちする */
    grid-column-gap: ${({ theme }) => (theme.space ? theme.space[4] : 32)}px;
    grid-row-gap: ${({ theme }) => (theme.space ? theme.space[3] : 16)}px;
    grid-template-columns: auto auto;
    align-items: center;
`
