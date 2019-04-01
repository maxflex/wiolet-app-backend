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
          {{ item.id }}
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
    }
  },
  
}
</script>
