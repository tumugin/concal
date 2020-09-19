import { Column, useTable } from 'react-table'
import React from 'react'

export function AdminBasicTable<D extends Record<string, unknown>>({
    columns,
    data,
}: {
    columns: Column<D>[]
    data: D[]
}) {
    const { getTableProps, getTableBodyProps, headerGroups, rows, prepareRow } = useTable({ columns, data })

    return (
        <table {...getTableProps()}>
            <thead>
                {headerGroups.map((headerGroup, index) => (
                    <tr {...headerGroup.getHeaderGroupProps()} key={index}>
                        {headerGroup.headers.map((column, columnIndex) => (
                            <th {...column.getHeaderProps()} key={columnIndex}>
                                {column.render('Header')}
                            </th>
                        ))}
                    </tr>
                ))}
            </thead>
            <tbody {...getTableBodyProps()}>
                {rows.map((row, rowIndex) => {
                    prepareRow(row)
                    return (
                        <tr {...row.getRowProps()} key={rowIndex}>
                            {row.cells.map((cell, cellIndex) => {
                                return (
                                    <td {...cell.getCellProps()} key={cellIndex}>
                                        {cell.render('Cell')}
                                    </td>
                                )
                            })}
                        </tr>
                    )
                })}
            </tbody>
        </table>
    )
}
