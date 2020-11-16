import styled from 'styled-components'
import React from 'react'
import { Box, Button, Flex } from 'rebass/styled-components'

interface StoreItem {
    storeName: string
    storeId: number
}

export function AdminStoresEditorListView({
    stores,
    onDelete,
}: {
    stores: StoreItem[]
    onDelete: (storeId: number) => void
}) {
    return (
        <Wrapper>
            {stores.map((store) => (
                <Store store={store} onDelete={() => onDelete(store.storeId)} key={store.storeId} />
            ))}
        </Wrapper>
    )
}

function Store({ store, onDelete }: { store: StoreItem; onDelete: () => void }) {
    return (
        <Flex sx={{ alignItems: 'center' }}>
            <Button variant="outline" onClick={onDelete}>
                削除
            </Button>
            <Box marginLeft={2}>{store.storeName}</Box>
        </Flex>
    )
}

const Wrapper = styled.div`
    display: grid;
    grid-auto-flow: row;
    grid-row-gap: 8px;
`
