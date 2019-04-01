<template>
  <div>
    <Dialog ref='Dialog' />
    <v-data-table
      :class='config.elevationClass'
      hide-actions
      hide-headers
      :items='items'
    >
      <template slot='items' slot-scope="{ item }">
        <td width='400'>
          {{ item.name || 'пусто' }}
        </td>
        <td>
          <span v-if='item.city'>
            {{ item.city.name }}
          </span>
        </td>
        <td>
          <span v-if='item.gender'>
            {{ GENDER.find(e => e.id === item.gender).title }}
          </span>
          <span v-else>
            не указано
          </span>
        </td>
        <td class='grey--text'>
          {{ item.created_at | date-time }}
        </td>
        <td>
          <EmailShow :item='item.email' />
        </td>
        <td class='text-md-right'>
          <v-btn @click='$refs.Dialog.open(item.id)' slot='activator' flat icon color="black" class='ma-0'>
            <v-icon>more_horiz</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import Dialog from './Dialog'
import { GENDER } from './'
import EmailShow from '@/components/Email/Show'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  components: { Dialog, EmailShow },

  data() {
    return {
      GENDER
    }
  },

}
</script>
