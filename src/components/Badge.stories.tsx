import { Badge } from 'components/Badge'
import { Meta, Story } from '@storybook/react/types-6-0'
import React from 'react'

export default {
    title: 'Components/Badge',
} as Meta

const Template: Story<Parameters<typeof Badge>[0]> = (args) => <Badge {...args} />

export const BadgeComponent = Template.bind({})
BadgeComponent.args = {
    children: 'アム・S・ミカエル',
    type: 'danger',
}
BadgeComponent.argTypes = {
    type: {
        control: {
            type: 'select',
            options: ['danger', 'success', 'alert'],
        },
    },
}
