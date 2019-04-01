<template>
  <v-select hide-details ref='select'
    :value='value'
    @input="value => $emit('input', value)"
    :items="items"
    :label="label"
    item-value='id'
    :item-text='itemText'
    :readonly="readonly"
  >
    <v-list-tile slot='prepend-item' @click='clear'>
      <v-list-tile-title class='grey--text'>
        не установлено
      </v-list-tile-title>
    </v-list-tile>
  </v-select>
</template>

<script>
export default {
  props: {
    items: {
      type: Array,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    value: {
      required: true,
    },
    itemText: {
      required: false,
      default: 'title',
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


