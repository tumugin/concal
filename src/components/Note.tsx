import { Box } from 'rebass/styled-components'
import React from 'react'

export function Note({ children, tight }: { children: React.ReactNode; tight?: boolean }) {
    return (
        <Box
            sx={{
                fontSize: 1,
                fontStyle: 'italic',
                px: 3,
                py: 2,
                my: 3,
                bg: 'muted',
                borderRadius: 4,
                borderLeft: (t) => `8px solid ${t.colors.primary}`,
            }}
            css={{ width: tight ? 'fit-content' : undefined }}
        >
            {children}
        </Box>
    )
}
