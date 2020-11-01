import format from 'date-fns/format'
import ja from 'date-fns/locale/ja'
import { dateFnsLocalizer } from 'react-big-calendar'
import parse from 'date-fns/parse'
import startOfWeek from 'date-fns/startOfWeek'
import getDay from 'date-fns/getDay'

export function wrappedLocaleFunction(
    date: Date | number,
    ft: string,
    options: {
        weekStartsOn?: 0 | 1 | 2 | 3 | 4 | 5 | 6
        firstWeekContainsDate?: number
        useAdditionalWeekYearTokens?: boolean
        useAdditionalDayOfYearTokens?: boolean
    }
) {
    // どうしてこうなった.... オプションで指定させてくれ....
    return format(date, ft, { ...options, locale: ja })
}

export function getCalendarLocalizer() {
    const locales = {
        ja: ja,
    }
    return dateFnsLocalizer({
        format: wrappedLocaleFunction,
        parse,
        startOfWeek,
        getDay,
        locales,
    })
}
