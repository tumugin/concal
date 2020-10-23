import { UserStore, UserStoreGroup } from 'api/types'
import { Box, Heading } from 'rebass/styled-components'
import { StoreLinkBox } from 'components/StoreLinkBox'
import React from 'react'
import styled from 'styled-components'
import { Link } from 'react-router-dom'

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
            {groups.map((group) => group.stores.length > 0 && <GroupView key={group.id} group={group} />)}
        </Box>
    )
}

function GroupView({ group }: { group: GroupWithStores }) {
    return (
        <Box>
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
                    <NoStyleLink key={store.id} to={`/stores/${store.id}`}>
                        <StoreLinkBox key={store.id} store={store} />
                    </NoStyleLink>
                ))}
            </Box>
        </Box>
    )
}

const NoStyleLink = styled(Link)`
    color: white;
    text-decoration: none;
`
