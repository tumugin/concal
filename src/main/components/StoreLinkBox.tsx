import { Flex } from 'rebass/styled-components'
import React from 'react'
import { UserStore } from 'main/api/types'

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
                height: '100%',
            }}
            padding={3}
        >
            {store.storeName}
        </Flex>
    )
}
