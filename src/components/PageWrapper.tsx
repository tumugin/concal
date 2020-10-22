import { Box } from 'rebass/styled-components'
import React from 'react'

export function PageWrapper({ children }: { children: React.ReactNode }) {
    return (
        <Box p={4} sx={{ marginLeft: 'auto', marginRight: 'auto', maxWidth: '1600px' }}>
            {children}
        </Box>
    )
}
