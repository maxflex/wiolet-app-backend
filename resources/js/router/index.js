import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'
import { ROLES } from '@/config'
// import { API_URL as LOGS_API_URL } from '@/components/Log'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  },
  routes: [
    ...require('./events').default,
    ...require('./users').default,
  ]
})

// function urlLog(to) {
//   axios.post(apiUrl(LOGS_API_URL), {
//     data: {url: to.path},
//     type: 'url'
//   })
// }

router.beforeEach((to, from, next) => {
  // очищаем результаты поиска при переходе по другой ссылке
  if (store.state.search.results !== null) {
    store.commit('clearSearch')
  }
  Vue.nextTick(() => {
    if (store.state.user) {
      if (to.hasOwnProperty('meta') && to.meta.hasOwnProperty('roles')) {
        if (to.meta.roles.indexOf(store.state.user.class) !== -1) {
        //   urlLog(to)
          next()
        }
      } else {
        if (store.state.user.class === ROLES.ADMIN) {
        //   urlLog(to)
          next()
        }
      }
    } else {
      colorLog('no user', 'LightCoral')
      // Vue.nextTick(() => router.push({name: to.name}))
    }
  })
})

export default router
