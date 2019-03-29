import store from '@/store'

// Year tabs with data
export const tabsWithData = function(items) {
  return store.state.data.years.filter(d => {
    return items.findIndex(e => e.year === d.id) !== -1
  })
}