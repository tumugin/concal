import { PageWrapper } from 'components/PageWrapper'
import { Box, Flex, Heading, Link as RebassLink } from 'rebass/styled-components'
import React, { useEffect } from 'react'
import { Link, useParams } from 'react-router-dom'
import { useCast, useLoadCast } from 'main/store/cast'
import { Grid250 } from 'components/Grid250'
import styled from 'styled-components'
import { StoreLinkBox } from 'main/components/StoreLinkBox'
import { InfoGrid } from 'components/InfoGrid'
import { CastColorBlock } from 'components/CastColorBlock'
import { AttendInfoBox } from 'main/components/AttendInfoBox'
import { Note } from 'components/Note'
import { BootstrapLikeColors } from 'utils/bootstrapLike'

export default function Cast() {
    const { id } = useParams<{ id: string }>()
    const parsedId = parseInt(id)
    const cast = useCast(parsedId)
    const loadCast = useLoadCast(parsedId)

    useEffect(() => {
        if (!cast) {
            void loadCast()
        }
    }, [cast, loadCast])

    if (!cast) {
        return null
    }

    return (
        <PageWrapper>
            <Heading>{cast.castName}</Heading>
            <Heading as="h3" marginTop={3} marginBottom={3} fontSize={3}>
                キャストプロフィール
            </Heading>
            {cast.castDisabled && <Note color={BootstrapLikeColors.alert}>このキャストは既に卒業しています</Note>}
            <InfoGrid
                data={[
                    {
                        name: '在籍店舗グループ',
                        value: cast.stores.map((store) => store.storeGroup.groupName)[0],
                    },
                    {
                        name: 'キャスト省略名称',
                        value: cast.castShortName ?? '未登録',
                    },
                    {
                        name: 'Twitter',
                        value: cast.castTwitterId ? (
                            <RebassLink href={`https://twitter.com/${cast.castTwitterId}`} target="_blank">
                                @{cast.castTwitterId}
                            </RebassLink>
                        ) : (
                            '未登録'
                        ),
                    },
                    {
                        name: 'キャストイメージカラー',
                        value: cast.castColor ? (
                            <Flex sx={{ alignItems: 'center' }}>
                                <CastColorBlock color={cast.castColor} />
                                <Box marginLeft={2}>{cast.castColor}</Box>
                            </Flex>
                        ) : (
                            '未登録'
                        ),
                    },
                ]}
            />
            <Heading as="h3" marginTop={3} fontSize={3}>
                在籍店舗一覧
            </Heading>
            <Grid250 marginTop={3}>
                {cast.stores.map((store) => (
                    <NoStyleLink key={store.id} to={`/stores/${store.id}`}>
                        <StoreLinkBox key={store.id} store={store} />
                    </NoStyleLink>
                ))}
            </Grid250>
            <Heading as="h3" marginTop={3} fontSize={3}>
                直近の出勤一覧
            </Heading>
            <Grid250 marginTop={3}>
                {cast.recentAttends.map((attend) => (
                    <AttendInfoBox attend={attend} color={cast.castColor} key={attend.id} />
                ))}
            </Grid250>
        </PageWrapper>
    )
}

const NoStyleLink = styled(Link)`
    color: white;
    text-decoration: none;
`
