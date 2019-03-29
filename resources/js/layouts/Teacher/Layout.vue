<template>
  <div>
    <v-navigation-drawer
      clipped
      fixed
      :value="true"
      :width="250"
      app
      dark
    >
      <div class='menu-logo'>
        <img src='/img/svg/logo-full.svg' />
      </div>
      <Menu />
    </v-navigation-drawer>
  </div>
</template>

<script>

import Menu from './Menu'

export default {
  components: { Menu },
}
</script>

