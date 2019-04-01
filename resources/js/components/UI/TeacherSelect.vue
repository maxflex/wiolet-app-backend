<template>
  <v-select hide-details ref='select'
    :value='value'
    @input="value => $emit('input', value)"
    :items="$store.state.data.teachers"
    :label="label"
    item-value='id'
    item-text='default_name'
    :readonly="readonly"
  >
    <v-list-tile slot='prepend-item' @click='clear'>
      <v-list-tile-title class='grey--text'>
        не установлено
      </v-list-tile-title>
    </v-list-tile>
    <template slot='item' slot-scope='{ item }' @click='value = item.id'>
      <div :class="{'grey--text': item.in_egecentr !== 2}">{{ item.default_name }}</div>
    </template>
  </v-select>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      required: false,
      default: 'Учитель',
    },
    value: {
      required: true,
    },
    readonly: {
      type: Boolean,
      default: false,
      required: false,
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


