import { Box } from 'rebass/styled-components'
import React from 'react'

export function PageWrapper({ children }: { children: React.ReactNode }) {
    return <Box p={4}>{children}</Box>
}
