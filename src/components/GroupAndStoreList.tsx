import { UserStore, UserStoreGroup } from 'api/types'
import { Box, Heading } from 'rebass/styled-components'
import { StoreLinkBox } from 'components/StoreLinkBox'
import React from 'react'

interface GroupWithStores extends UserStoreGroup {
    stores: UserStore[]
}

export function GroupAndStoreList({ groups }: { groups: GroupWithStores[] }) {
    return (
        <Box
            sx={{
                display: 'grid',
                gridGap: 3,
            }}
        >
            {groups.map((group) => group.stores.length > 0 && <GroupView group={group} />)}
        </Box>
    )
}

function GroupView({ group }: { group: GroupWithStores }) {
    return (
        <Box key={group.id}>
            <Heading as="h3" fontSize={3} marginBottom={3}>
                {group.groupName}
            </Heading>
            <Box
                sx={{
                    display: 'grid',
                    gridGap: 3,
                    gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))',
                }}
            >
                {group.stores.map((store) => (
                    <StoreLinkBox key={store.id} store={store} />
                ))}
            </Box>
        </Box>
    )
}
