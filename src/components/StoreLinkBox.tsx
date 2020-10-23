import { Flex } from 'rebass/styled-components'
import React from 'react'
import { UserStore } from 'api/types'

export function StoreLinkBox({ store }: { store: UserStore }) {
    return (
        <Flex
            key={store.id}
            sx={{
                backgroundColor: 'muted',
                alignItems: 'center',
                justifyContent: 'center',
                textAlign: 'center',
                borderRadius: '6px',
            }}
            padding={3}
        >
            {store.storeName}
        </Flex>
    )
}
