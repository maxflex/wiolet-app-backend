<template>
  <div>
    <center autocomplete="off" class="login-form animated fadeIn">
        <!-- <div class="login-logo group">
            <img src="/img/svg/logo.svg" />
        </div> -->
        <div class="input-groups">
            <div class="group">
                <input :disabled="sms_verification" type="text" placeholder="логин" autofocus
                  ref="login" v-model="credentials.login" autocomplete="off" @keyup.enter="imitateSubmit">
            </div>
            <div class="group">
                <input :disabled="sms_verification" type="password" placeholder="пароль" ref="password"
                  v-model="credentials.password" autocomplete="new-password" @keyup.enter="imitateSubmit">
            </div>
            <div class="group" v-show="sms_verification">
                <input type="text" id="sms-code" placeholder="sms code" @keyup.enter="imitateSubmit"
                  v-model="credentials.code" autocomplete="off">
            </div>
            <div class="group">
              <div class="btn btn-submit" :class="{'btn--disabled': loading}" ref='submit'>
                <button @click="callback">войти</button>
              </div>
            </div>
        </div>
        <div v-show="error" class="login-errors">
          {{ error }}
        </div>
    </center>
  </div>
</template>

<script>
  import Cookies from 'js-cookie';

  const API_URL = 'auth/login'

  export default {
    data() {
      return {
        credentials: {},
        loading: false,
        sms_verification: false,
        error: null
      }
    },

    created() {
      // TODO: какого хрена при уходе с элемента его стили сохраняются в HEAD?
      $('body').css({
          height: '100vh',
          background: 'linear-gradient(to bottom, #5b3c7f, #e94c8f)'
        })
    },

    destroyed() {
      $('body').css({background: '', height: 'auto'})
    },

    methods: {
      validate() {
        if (! this.credentials.login) {
          this.$refs.login.focus()
          return false
        }
        if (! this.credentials.password) {
          this.$refs.password.focus()
          return false
        }
        return true
      },

      callback(token) {
        this.loading = true
        this.error = false
        axios.post(apiUrlBackend(API_URL), {
            email: this.credentials.login,
            password: this.credentials.password,
        }).then(response => {
            // Сохраняем токен
            Cookies.set('access-token', response.data.access_token)
            window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.access_token
            axios.get(apiUrlBackend('profile')).then(r => {
                this.$store.commit('setUser', r.data)
            })
        }).catch(error => {
          this.error = 'в доступе отказано'
        }).then(() => {
            this.loading = false
        })
      },

      imitateSubmit() {
        this.$refs.submit.querySelector('button').click()
      }
    }
  }
</script>

<style lang="scss" scoped>
  @import "~sass/login";
</style>
