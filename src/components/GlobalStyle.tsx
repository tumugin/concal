import { createGlobalStyle } from 'styled-components'

// FIXME: 型がぶっ壊れるのでひん曲げる
type FIXMEType = { colors: { background: string } }

export const GlobalStyle = createGlobalStyle`
body {
  font-family: sans-serif, system-ui;
  background-color: ${({ theme }) => (theme as FIXMEType).colors.background};
}
`
