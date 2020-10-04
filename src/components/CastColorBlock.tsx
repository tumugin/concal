import { Box } from 'rebass'
import styled from 'styled-components'
import React from 'react'

export function CastColorBlock({ color }: { color: string }) {
    return <ColorBox sx={{ backgroundColor: color }} />
}

const ColorBox = styled(Box)`
    border-radius: 4px;
    border: ${({ theme }) => theme.colors.text} 1px solid;
    width: 20px;
    height: 20px;
`
