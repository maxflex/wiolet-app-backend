<template>
  <div>
    <EmailHistory ref='EmailHistory' />
    <EmailDialog ref='EmailDialog' />
    <v-menu>
      <a slot='activator'>
        {{ item }}
      </a>
      <v-list dense>
        <v-list-tile @click='$refs.EmailDialog.open([item])'>
          <v-list-tile-action>
            <v-icon>email</v-icon>
          </v-list-tile-action>
          <v-list-tile-title>написать</v-list-tile-title>
        </v-list-tile>
        <v-list-tile @click='$refs.EmailHistory.open(item)'>
          <v-list-tile-action>
            <v-icon>history</v-icon>
          </v-list-tile-action>
          <v-list-tile-title>история</v-list-tile-title>
        </v-list-tile>
      </v-list>
    </v-menu>
  </div>
</template>

<script>

import EmailHistory from './History'
import EmailDialog from './Dialog'

export default {
  props: ['item'],

  components: { EmailHistory, EmailDialog },
}
</script>
