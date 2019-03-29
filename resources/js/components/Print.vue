<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay content-class='overflow-hidden'>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ title }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat @click.native="print">Печать</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='relative'>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout wrap>
              <v-flex md12>
                <TextEditor v-model='text' />
              </v-flex>
              <div id='print-block' v-html='text'></div>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
import { TextEditor } from '@/components/UI'
import printJS from 'print-js'

const API_URL = 'print'

export default {
  props: {
    params: {
      type: Object,
      required: false,
    },
    title: {
      type: String,
      default: 'Печать',
      required: false,
    },
  },

  components: { TextEditor },

  data() {
    return {
      dialog: false,
      loading: true,
      text: null
    }
  },

  methods: {
    open(params = {}) {
      this.loading = true
      this.dialog = true
      axios.get(apiUrl(API_URL) + queryString({...this.params, ...params})).then(r => {
        this.text = r.data
        this.loading = false
      })
    },

    print() {
      printJS('print-block', 'html')
      this.dialog = false
    },
  }
}
</script>
