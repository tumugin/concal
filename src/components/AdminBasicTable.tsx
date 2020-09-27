import { Column, useTable } from 'react-table'
import React, { ReactNode } from 'react'
import styled from 'styled-components'

export function AdminBasicTable<D extends Record<string, string | number | ReactNode>>({
    columns,
    data,
    operationNode,
    operationWidth,
}: {
    columns: Column<D>[]
    data: D[]
    operationNode?: (data: D) => ReactNode
    operationWidth?: number
}) {
    const { getTableProps, getTableBodyProps, headerGroups, rows, prepareRow } = useTable({ columns, data })

    return (
        <Wrapper>
            <StyledTable {...getTableProps()}>
                <thead>
                    {headerGroups.map((headerGroup, index) => (
                        <StyledTrHeader {...headerGroup.getHeaderGroupProps()} key={index}>
                            {operationNode && (
                                <StyledThHeader colSpan={1} role="columnheader" style={{ width: operationWidth }} />
                            )}
                            {headerGroup.headers.map((column, columnIndex) => (
                                <StyledThHeader
                                    {...column.getHeaderProps()}
                                    key={columnIndex}
                                    style={{ width: column.width, maxWidth: column.maxWidth }}
                                >
                                    {column.render('Header')}
                                </StyledThHeader>
                            ))}
                        </StyledTrHeader>
                    ))}
                </thead>
                <tbody {...getTableBodyProps()}>
                    {rows.map((row, rowIndex) => {
                        prepareRow(row)
                        return (
                            <StyledTrBody {...row.getRowProps()} key={rowIndex}>
                                {operationNode && (
                                    <StyledTdBody role="cell" style={{ width: operationWidth }}>
                                        {operationNode(row.original)}
                                    </StyledTdBody>
                                )}
                                {row.cells.map((cell, cellIndex) => {
                                    return (
                                        <StyledTdBody
                                            {...cell.getCellProps()}
                                            key={cellIndex}
                                            style={{ width: cell.column.width, maxWidth: cell.column.maxWidth }}
                                        >
                                            {cell.render('Cell')}
                                        </StyledTdBody>
                                    )
                                })}
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

const StyledTable = styled.table`
    border-collapse: collapse;
    table-layout: fixed;
    width: 100%;
`
