<template>
  <div>
    <v-dialog v-model="dialog" width="500">
      <v-card>
        <v-card-title>
          <span class="headline">Сессия завершится через {{ seconds_until_logout }}...</span>
        </v-card-title>
        <v-card-text>
        <v-layout wrap align-center>
        </v-layout>
        <v-card-actions>
         <v-spacer></v-spacer>
         <v-btn color="primary" @click.native="continueSession">Продолжить сессию</v-btn>
       </v-card-actions>
      </v-card-text>
    </v-card>
   </v-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dialog: false,
      seconds_until_logout: null,
      interval: null
    }
  },

  created() {
    this.listenToLogout()
  },

  methods: {
    listenToLogout() {
      const pusher = new Pusher(process.env.MIX_SSO_PUSHER_APP_KEY, {
        cluster: 'eu'
      })
      const channel = pusher.subscribe('session.' + this.$store.state.user.id)
      channel.bind("App\\Events\\LogoutSignal", (data) => {
        switch (data.action) {
          case 'notify':
            return this.logoutCountdown()
          case 'destroy':
            return window.location.href = '/logout'
        }
      })
    },

    logoutCountdown() {
      this.dialog = true
      this.seconds_until_logout = 59
      this.interval = setInterval(() => {
        this.seconds_until_logout--
        if (this.seconds_until_logout <= 0) {
          location.reload()
        }
      })
    },

    continueSession() {
      axios.get("/auth/continue-session")
      this.interval = null
      this.dialog = false
    }
  }
}
</script>
