<template>
  <v-list dense>
    <SearchBar />

    <MenuItem v-for='m in menu' :key='m.route' :item='m' />

    <div class='menu-separator'></div>

    <MenuItem v-for='m in admin_menu' :key='m.route' :item='m' />

    <v-list-tile @click="$store.commit('toggleDrawer', false)">
      <v-list-tile-action>
        <v-icon>arrow_back_ios</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>Скрыть</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>

    <v-list-tile @click="$store.dispatch('logout')">
      <v-list-tile-action>
        <v-icon>exit_to_app</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>Выход</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile>
  </v-list>
</template>



<script>
import SearchBar from '@/components/Search/Bar'
import MenuItem from '@/components/UI/MenuItem'

export default {
  components: { SearchBar, MenuItem },

  data: () => ({
    drawer: true,
    menu: [
      {
        icon: 'person',
        route: 'UserIndex',
        label: 'Пользователи',
        counter: 'users',
      },
      {
        icon: 'compare_arrows',
        route: 'EventIndex',
        label: 'События',
      },
    //   {
    //     icon: 'person',
    //     route: 'ClientIndex',
    //     label: 'Клиенты',
    //     counter: 'clients',
    //   },
    //   {
    //     icon: 'calendar_today',
    //     route: 'GroupIndex',
    //     label: 'Группы',
    //     counter: 'groups',
    //   },
    //   {
    //     icon: 'calendar_today',
    //     route: 'AbstractGroupIndex',
    //     label: 'Болота',
    //   },
    //   {
    //     icon: 'attach_money',
    //     route: 'PaymentIndex',
    //     label: 'Платежи'
    //   },
    //   {
    //     icon: 'people',
    //     route: 'TeacherIndex',
    //     label: 'Преподаватели'
    //   },
    //   {
    //     icon: 'edit',
    //     route: 'TestIndex',
    //     label: 'Тесты'
    //   },
    //   {
    //     icon: 'import_contacts',
    //     route: 'ContractIndex',
    //     label: 'Договоры'
    //   },
    //   {
    //     icon: 'how_to_reg',
    //     route: 'VisitIndex',
    //     label: 'Посещаемость'
    //   },
    //   {
    //     icon: 'chat',
    //     route: 'ReviewIndex',
    //     label: 'Отзывы'
    //   },
    //   {
    //     icon: 'rate_review',
    //     route: 'ReportIndex',
    //     label: 'Отчёты'
    //   },
    //   {
    //     icon: 'textsms',
    //     route: 'SmsMessageIndex',
    //     label: 'SMS',
    //   }
    ],
    admin_menu: [
    //   {
    //     icon: 'event',
    //     route: 'DatesAndPrices',
    //     label: 'Экзамены и цены'
    //   },
    //   {
    //     icon: 'textsms',
    //     route: 'SmsTemplateIndex',
    //     label: 'Шаблоны'
    //   },
    //   {
    //     icon: 'history',
    //     route: 'LogIndex',
    //     label: 'Логи'
    //   },
    //   {
    //     icon: 'print',
    //     route: 'MacroIndex',
    //     label: 'Макросы'
    //   },
    //   {
    //     icon: 'people',
    //     route: 'AdminIndex',
    //     label: 'Пользователи'
    //   },
    //   {
    //     icon: 'assignment_turned_in',
    //     route: 'TaskIndex',
    //     label: 'Задачи'
    //   },
    ],
  }),
}
</script>
