<template>
  <div>
    <v-navigation-drawer
      clipped
      fixed
      :value="$store.state.drawer"
      :width="250"
      app
      dark
    >
      <div class='menu-logo'>
        <img src='/img/svg/logo-full.svg' />
      </div>
      <Menu />
    </v-navigation-drawer>

    <ShowDrawer v-if='!$store.state.drawer' />
      
  </div>
</template>

<script>

import Menu from './Menu'
import SearchBar from '@/components/Search/Bar'
import ShowDrawer from '@/components/UI/ShowDrawer'

export default {
  components: { Menu, SearchBar, ShowDrawer },

  created() {
    axios.get(apiUrl('counters')).then(r => {
      this.$store.commit('setCounters', r.data)
    })
  },
}
</script>
