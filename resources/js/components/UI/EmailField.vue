<template>
  <div>
    <v-text-field v-model="entity.email.email" label="Email" hide-details></v-text-field>
    <v-layout row justify-center>
      <v-dialog v-model="dialog" max-width="1000px" scrollable>
        <v-card>
          <v-card-title class='title justify-center'>
            {{ entity.email.email }}
          </v-card-title>
          <v-card-text class='messages'>
            <Loader v-if='messages === null' />
            <div v-else>
              <div v-for='message in messages' :key='message.id' class="mb-3 display-flex">
              <Avatar :photo='message.createdUser ? message.createdUser.photo : null' :size='50' class='mr-3' />
              <v-card class='messages__item grey lighten-4' :class='config.elevationClass'>
                <v-card-text class='py-2 px-3'>
                  <div class='display-flex align-center'>
                    <span class='font-weight-medium'>{{ message.createdUser ? message.createdUser.default_name : 'Неизвестный отправитель' }}</span>
                    <span class='ml-2 caption grey--text'>{{ message.created_at | date-time }}</span>
                  </div>
                  {{ message.message }}
                </v-card-text>
              </v-card>
            </div>
            </div>
          </v-card-text>
          <v-card-actions class='v-card-actions--normal-padding'>
            <div style='width: 100%'>
              <v-text-field label="Тема сообщения" v-model='subject'></v-text-field>
              <v-textarea v-model='message' label='Сообщение' :counter='true' ref='textarea' :loading='sending'
                @keydown.enter.prevent='send'
                @click:append='send'
                append-icon='send'>
              </v-textarea>
            </div>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
  </div>
</template>

<script>

const API_URL = 'email-messages'

export default {
  props: ['entity'],

  data() {
    return {
      dialog: false,
      sending: false,
      message: '',
      subject: '',
      messages: null,
    }
  },

  methods: {
    openDialog() {
      this.messages = null
      this.message = ''
      this.subject = ''
      this.dialog = true
      // Vue.nextTick(() => this.$refs.textarea.focus())
      axios.get(apiUrl(`${API_URL}?email=${this.entity.email.email}`)).then(r => {
        this.messages = r.data
      })
    },

    send() {
      this.sending = true
      axios.post(apiUrl(API_URL), {
        subject: this.subject,
        message: this.message,
        email: this.entity.email.email,
      }).then(r => {
        console.log(r.data)
        this.sending = false
        this.messages.unshift(r.data)
        this.message = ''
        this.subject = ''
      })
    },
  }
}
</script>

<style lang='scss' scoped>
  .v-input__append-inner {
    align-self: flex-end !important;
    & i {
      font-size: 24px;
      margin-bottom: 18px;
    }
  }
  .messages {
    height: 500px;
    position: relative;
    &__item {
      display: inline-block;
      min-width: 300px;
      max-width: 90%;
    }
    & .v-icon {
      cursor: default;
    }
  }
</style>
