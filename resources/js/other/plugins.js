import Loader from '@/components/UI/Loader'
import Avatar from '@/components/UI/Avatar'
import AddBtn from '@/components/UI/AddBtn'
import AddBtnAnimated from '@/components/UI/AddBtnAnimated'
import NoData from '@/components/UI/NoData'
import DataTable from '@/components/UI/DataTable'
import PersonName from '@/components/UI/PersonName'
import ClearableSelect from '@/components/UI/ClearableSelect'
import SubjectGrade from '@/components/UI/SubjectGrade'
import Credentials from '@/components/UI/Credentials'
import Placeholder from '@/components/UI/Placeholder'
import YearTabs from '@/components/UI/YearTabs'

export const GlobalPlugin = {
  install(Vue, options) {
    Vue.prototype.getData = function(field, id) {
      const result = this.$store.state.data[field].find(e => e.id == id)
      return result
      // return result || {}
    }

    Vue.prototype.config = {
      elevationClass: 'elevation-3',
    }

    Vue.prototype.waitForDialogClose = (f) => {
      Vue.nextTick(() => f(), 200)
    }

    // Vue.prototype.toggleEnum = function(obj, field, statuses) {
    //   let status = statuses.indexOf(obj[field])
    //   console.log('from', statuses[status], status)
    //   status++
    //   if (status >= statuses.length) {
    //     status = 0
    //   }
    //   console.log('to', statuses[status], status)
    //   Vue.set(obj, field, statuses[status])
    // }

    Vue.component('Loader', Loader)
    Vue.component('Avatar', Avatar)
    Vue.component('AddBtn', AddBtn)
    Vue.component('ClearableSelect', ClearableSelect)
    Vue.component('NoData', NoData)
    Vue.component('PersonName', PersonName)
    Vue.component('AddBtnAnimated', AddBtnAnimated)
    Vue.component('DataTable', DataTable)
    Vue.component('Credentials', Credentials)
    Vue.component('SubjectGrade', SubjectGrade)
    Vue.component('Placeholder', Placeholder)
    Vue.component('YearTabs', YearTabs)
  }
}
