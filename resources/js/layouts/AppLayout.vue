<template>
  <v-app id="inspire">
    <AdminLayout @logout='logout' />
    <v-content>
      <v-container fluid>
        <transition name="fade">
          <router-view v-show='$store.state.search.results === null'></router-view>
        </transition>
        <SearchResults v-show='$store.state.search.results !== null' />
      </v-container>
    </v-content>
    <!-- <ListenToLogout></ListenToLogout> -->
  </v-app>
</template>

<script>

  import AdminLayout from '@/layouts/Admin/Layout'
  import { SearchResults } from '@/components/Search'

  export default {
    data() {
      return {
        CLIENT_CLASS_NAME,
        TEACHER_CLASS_NAME,
        ADMIN_CLASS_NAME,
      }
    },

    components: { AdminLayout, SearchResults },

    methods: {
      logout() {
        axios.get(apiUrl('logout'))
        this.$store.commit('setUser', null)
      }
    }
  }
</script>
