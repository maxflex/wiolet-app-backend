<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>История переписки с {{ email }}</v-toolbar-title>
        </v-toolbar>
        <v-card-text class='email-messages'>
            <Loader transparent v-if='items === null' />
            <div v-else>
              <div v-if='items.length > 0'>
                <div v-for='item in items' :key='item.id' class="mb-3 display-flex">
                  <Avatar :photo='item.createdUser ? item.createdUser.photo : null' :size='50' class='mr-3' />
                  <v-card class='email-messages__item grey lighten-4' :class='config.elevationClass'>
                    <v-card-text class='py-2 px-3'>
                      <div class='display-flex align-center'>
                        <span class='font-weight-medium'>{{ item.createdUser ? item.createdUser.default_name : 'Неизвестный отправитель' }}</span>
                        <span class='ml-2 caption grey--text'>{{ item.created_at | date-time }}</span>
                      </div>
                      <div v-if='item.subject' class='font-weight-medium'>
                        {{ item.subject }}
                      </div>
                      <div>
                        {{ item.message }}
                      </div>
                      <div v-if='item.files.length' class='mt-2 grey--text small caption flex-items' style='flex-wrap: wrap'>
                        <a v-for='file in item.files' :key='file.name' class='mr-2 flex-items align-center' :href="`/download/${file.id}`">
                          <v-chip class='pointer'>
                            <span>{{ file.original_name | truncate(25) }}</span>
                          </v-chip>
                        </a>
                      </div>
                    </v-card-text>
                  </v-card>
                </div>
              </div>
              <div v-else class='full-height-vh flex-items align-center justify-center grey--text' style='flex-direction: column'>
                <div style='opacity: .2'>
                  <v-icon size='100'>mail_outline</v-icon>
                </div>
                <div class='subheading'>
                  писем нет
                </div>
              </div>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
import { API_URL } from '@/components/Email'

export default {

  data() {
    return {
      dialog: false,
      items: null,
      email: null,
    }
  },

  methods: {
    open(email) {
      this.items = null
      this.dialog = true
      this.email = email
      // Vue.nextTick(() => this.$refs.textarea.focus())
      axios.get(apiUrl(`${API_URL}?email=${email}`)).then(r => {
        this.items = r.data
      })
    },
  }
}
</script>

<style lang='scss'>
  .email-messages {
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