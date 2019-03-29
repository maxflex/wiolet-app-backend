require('./bootstrap')
window.Vue = require('vue')
window.Vue.http = axios

// иначе загрузка STORE происходит позже
// import Index from '@/components/Index'
import store from './store'

// иначе загрузка STORE происходит позже
// import router from './router'
import filters from '@/other/filters'
import {GlobalPlugin} from '@/other/plugins'
import VueTheMask from 'vue-the-mask'
import Vuetify from 'vuetify'
import ru from 'vuetify/es5/locale/ru'
import vueUpload from '@/vendor/vue-upload.min'
// import * as VueGoogleMaps from 'vue2-google-maps'

[GlobalPlugin, VueTheMask].forEach(use => Vue.use(use))

// Vue.use(VueGoogleMaps, {
//   load: {
//     key: 'AIzaSyAXXZZwXMG5yNxFHN7yR4GYJgSe9cKKl7o',
//     libraries: 'places',
//     language: 'ru'
//   },
// })

Vue.use(Vuetify, {
  lang: {
    locales: { ru },
    current: 'ru'
  }
})

Vue.use(vueUpload, {
  http: function(data) {
    console.log('data', data)
    return axios.post(data.url, data.body, {
      onUploadProgress: data.progress,
      cancelToken: data.cancelToken,
    })
    .then(data.success)
    // .catch(data.error)
  }
})

Object.entries(filters).forEach(entry => Vue.filter(entry[0], entry[1]))

axios.get(apiUrl('initial-data')).then(r => {
  store.commit('setData', r.data.data)
  store.commit('setUser', r.data.user)
  new Vue({
    el: '#app',
    components: {
      App: require('./components/App').default
    },
    store,
    router: require('./router').default
  })
})
