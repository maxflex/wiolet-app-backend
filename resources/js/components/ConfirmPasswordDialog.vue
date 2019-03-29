<template>
  <v-layout row justify-center>
      <v-dialog v-model="dialog" persistent max-width="300px">
        <v-card>
          <v-card-text>
            <div class='headline'>
              <v-text-field 
                :error-messages="errors"
                v-model='password' 
                label='Введите пароль' 
                type='password'></v-text-field>
            </div>
          </v-card-text>
          <v-card-actions class='justify-center'>
            <v-btn :loading='loading' color='primary' flat @click='confirm'>OK</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
</template>



<script>
export default {
  data() {
    return {
      dialog: true,
      loading: false,
      errors: [],
      password: '',
    }
  },
  
  methods: {
    confirm() {
      this.loading = true
      this.errors = []
      axios.post(apiUrl('confirm-password'), {password: this.password}).then(r => {
        if (r.data === true) {
          this.dialog = false
          this.$emit('confirmed')
        } else {
          this.errors = ['в доступе отказано']
        }
        this.loading = false
      })
    }
  }
}
</script>
