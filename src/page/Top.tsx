import React, { useEffect } from 'react'
import { Heading } from 'rebass/styled-components'
import { PageWrapper } from 'components/PageWrapper'
import { useLoadTopContents, useTop } from 'store/top'

export function Top() {
    const top = useTop()
    const loadTopContents = useLoadTopContents()
    useEffect(() => {
        if (!top.loaded) {
            void loadTopContents()
        }
    }, [loadTopContents, top.loaded])

    return (
        <PageWrapper>
            <Heading>コンカフェカレンダーへようこそ</Heading>
        </PageWrapper>
    )
}
