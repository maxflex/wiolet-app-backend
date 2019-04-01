export default {
  'date-time': value => moment(value).format('DD.MM.YY в HH:mm'),
  'day-month': value => moment(value).format('DD.MM'),
  date: value => moment(value).format('DD.MM.YY'),
  year: (value, year) => value.filter(e => e.year == year),
  truncate(text, stop, clamp) {
    return text.slice(0, stop) + (stop < text.length ? clamp || '...' : '')
  },
  dateFormat(value, format) {
    return moment(value).format(format)
  },
}
