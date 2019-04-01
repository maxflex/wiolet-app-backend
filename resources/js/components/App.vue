<template>
  <div>
    <AppLayout v-if="$store.state.user"></AppLayout>
    <LoginLayout v-else></LoginLayout>
  </div>
</template>

<script>
  import AppLayout from '@/layouts/AppLayout'
  import LoginLayout from '@/layouts/LoginLayout'

  export default {
    components: { AppLayout, LoginLayout },

    created() {
      // типа Drawer сохраняется в LocalStorage
      if (localStorage.hasOwnProperty('drawer')) {
        this.$store.commit('toggleDrawer', localStorage.getItem('drawer') === 'true')
      }
    }
  }
</script>
