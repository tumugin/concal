import { Column, useTable } from 'react-table'
import React, { ReactNode } from 'react'
import styled from 'styled-components'

export function AdminBasicTable<D extends Record<string, string | number | ReactNode>>({
    columns,
    data,
    operationNode,
}: {
    columns: Column<D>[]
    data: D[]
    operationNode?: (data: D) => ReactNode
}) {
    const { getTableProps, getTableBodyProps, headerGroups, rows, prepareRow } = useTable({ columns, data })

    return (
        <Wrapper>
            <StyledTable {...getTableProps()}>
                <thead>
                    {headerGroups.map((headerGroup, index) => (
                        <StyledTrHeader {...headerGroup.getHeaderGroupProps()} key={index}>
                            {operationNode && <StyledThHeader role="columnheader" />}
                            {headerGroup.headers.map((column, columnIndex) => (
                                <StyledThHeader key={columnIndex}>{column.render('Header')}</StyledThHeader>
                            ))}
                            <th colSpan={1} role="columnheader" />
                        </StyledTrHeader>
                    ))}
                </thead>
                <tbody {...getTableBodyProps()}>
                    {rows.map((row, rowIndex) => {
                        prepareRow(row)
                        return (
                            <StyledTrBody {...row.getRowProps()} key={rowIndex}>
                                {operationNode && (
                                    <StyledOperationNodeTdBody role="cell" style={{ width: 1, minWidth: 1 }}>
                                        {operationNode(row.original)}
                                    </StyledOperationNodeTdBody>
                                )}
                                {row.cells.map((cell, cellIndex) => {
                                    return (
                                        <StyledTdBody
                                            {...cell.getCellProps()}
                                            key={cellIndex}
                                            style={{ width: cell.column.width, minWidth: cell.column.width }}
                                        >
                                            {cell.render('Cell')}
                                        </StyledTdBody>
                                    )
                                })}
                                <td />
                            </StyledTrBody>
                        )
                    })}
                </tbody>
            </StyledTable>
        </Wrapper>
    )
}

const Wrapper = styled.div`
    overflow: auto;
`

const StyledThHeader = styled.th`
    text-align: left;
    padding-left: 8px;
`

const StyledTrHeader = styled.tr`
    border-bottom: ${({ theme }) => theme.colors.secondary} 1px solid;
`

const StyledTrBody = styled.tr`
    border-bottom: ${({ theme }) => theme.colors.muted} 1px solid;
`

const StyledTdBody = styled.td`
    padding: 8px;
`

const StyledOperationNodeTdBody = styled(StyledTdBody)`
    white-space: nowrap;
`

const StyledTable = styled.table`
    table-layout: fixed;
    border-collapse: collapse;
    min-width: 100%;
`
