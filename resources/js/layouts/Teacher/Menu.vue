<template>
  <v-list dense>
    <MenuItem v-for='m in menu' :key='m.route' :item='m' />

    <div class='menu-separator'></div>
    
    <v-list-tile @click="logout">
      <v-list-tile-action>
        <v-icon>exit_to_app</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>Выход</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile> 

    <v-list-tile v-if='PreviewMode.isActive()' class='preview-mode-info'>
      Преподаватель №{{ $store.state.user.id }}
      <br>
      {{ $store.state.user.last_name }}
      {{ $store.state.user.first_name }}
      {{ $store.state.user.middle_name }}
    </v-list-tile>
  </v-list>
</template>



<script>
import MenuItem from '@/components/UI/MenuItem'
import PreviewMode from '@/other/PreviewMode'

export default {
  components: { MenuItem },

  data: () => ({
    PreviewMode,
    drawer: true,
    menu: [
      {
        icon: 'calendar_today',
        route: 'GroupIndex',
        label: 'Группы'
      },
      {
        icon: 'attach_money',
        route: 'BalanceIndex',
        label: 'Баланс'
      },
      {
        icon: 'rate_review',
        route: 'ReportIndex',
        label: 'Отчёты'
      },
    ],
  }),
  methods: {
    logout() {
      if (PreviewMode.isActive()) {
        PreviewMode.exit()
      } else {
        this.$store.dispatch('logout')
      }
    },
  }
}
</script>
