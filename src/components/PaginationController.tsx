import { Box } from 'rebass/styled-components'
import styled from 'styled-components'
import React, { useCallback } from 'react'

export function PaginationController({
    currentPage,
    totalPages,
    onPageChange,
}: {
    currentPage: number
    totalPages: number
    onPageChange: (pageNumber: number) => void
}) {
    const canGoNext = currentPage + 1 <= totalPages
    const canGoBack = currentPage - 1 > 0
    const onNextPage = useCallback(() => {
        if (canGoNext) {
            onPageChange(currentPage + 1)
        }
    }, [canGoNext, currentPage, onPageChange])
    const onPrevPage = useCallback(() => {
        if (canGoBack) {
            onPageChange(currentPage - 1)
        }
    }, [canGoBack, currentPage, onPageChange])

    return (
        <CenteringContainer>
            <PaginationContainer>
                <RoundContainer onClick={onPrevPage} cursorPointer={canGoBack} bg={canGoBack ? 'gray' : 'muted'}>
                    {'<'}
                </RoundContainer>
                <CurrentNumber px={2}>
                    {currentPage} / {totalPages}
                </CurrentNumber>
                <RoundContainer onClick={onNextPage} cursorPointer={canGoNext} bg={canGoBack ? 'gray' : 'muted'}>
                    {'>'}
                </RoundContainer>
            </PaginationContainer>
        </CenteringContainer>
    )
}

const CurrentNumber = styled(Box)`
    display: flex;
    align-items: center;
`

const CenteringContainer = styled(Box)`
    display: flex;
    justify-content: center;
`

const PaginationContainer = styled(Box)`
    display: grid;
    grid-auto-flow: column;
    grid-gap: 4px;
    width: fit-content;
`

const RoundContainer = styled(Box)<{ cursorPointer: boolean }>`
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    min-height: 40px;
    border-radius: 100%;
    cursor: ${({ cursorPointer }) => (cursorPointer ? 'pointer' : 'default')};
`
