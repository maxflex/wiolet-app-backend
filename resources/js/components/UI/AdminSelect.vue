<template>
  <v-select hide-details ref='select'
    :value="value"
    @input="admin_id => $emit('input', admin_id)"
    :items="$store.state.data.admins"
    :label="label"
    item-value='id'
    item-text='default_name'
  >
    <v-list-tile slot='prepend-item' @click='clear'>
      <v-list-tile-title class='grey--text'>
        не установлено
      </v-list-tile-title>
    </v-list-tile>
    <template slot='item' slot-scope='{ item }' @click='value = item.id'>
      <div :class="{'grey--text': item.is_banned}">{{ item.default_name }}</div>
    </template>
  </v-select>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: 'Пользователь',
      required: false,
    },
    value: {
      required: true
    },
  },

  methods: {
    clear() {
      this.$refs.select.isMenuActive = false
      this.$emit('input', null)
      this.$refs.select.blur()
    },
  }
}
</script>
