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
        <td>
          {{ TYPE.find(e => e.id === item.type).title }}
        </td>
        <td>
          {{ item.userFrom.name }}
        </td>
        <td>
          {{ item.userTo.name }}
        </td>
        <td>
          {{ item.comment }}
        </td>
        <td class='text-md-right grey--text'>
          {{ item.created_at | date-time }}
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import Dialog from './Dialog'
import { TYPE } from './'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  components: { Dialog },

  data() {
    return {
      TYPE
    }
  },
  
}
</script>
