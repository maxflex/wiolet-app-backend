<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} пользователя</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat v-if='edit_mode' @click.native="destroy" :loading='destroying'>Удалить</v-btn>
            <v-btn dark flat @click.native="storeOrUpdate" :loading='saving'>{{ edit_mode ? 'Сохранить' : 'Добавить' }}</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout>
              <v-flex md6>
                <div class='headline mb-4'>Основные данные</div>
                <div class='vertical-inputs'>
                  <div class='vertical-inputs__input'>
                    <v-text-field label='Имя' v-model='item.name' hide-details />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field label='Email' v-model='item.email' hide-details />
                  </div>
                  <div class='vertical-inputs__input'>
                    <DatePicker v-model='item.birthdate' label="Дата рождения" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <ClearableSelect :items='GENDER' label='Пол' v-model='item.gender' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model='item.height' label="Рост" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model='item.weight' label="Вес" />
                  </div>
                  <div class='headline mb-4 mt-5'>Предпочтения</div>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model='item.preferences.age_from' label="Возраст, от" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model='item.preferences.age_to' label="Возраст, до" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <ClearableSelect :items='GENDER' label='Пол' v-model='item.preferences.gender' />
                  </div>
                </div>
              </v-flex>
              <v-flex md6>
                <div class='vertical-inputs'>
                  <!-- <div class='vertical-inputs__input'> -->
                    <v-textarea hide-details v-model='item.about' label='О себе' auto-grow>

                    </v-textarea>
                  <!-- </div> -->
                </div>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL, MODEL_DEFAULTS, GENDER } from './'
import { DialogMixin } from '@/mixins'
import DatePicker from '@/components/UI/DatePicker'

export default {
  mixins: [ DialogMixin ],

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      GENDER,
    }
  },

  components: { DatePicker },
  

  methods: {

  }
}
</script>
