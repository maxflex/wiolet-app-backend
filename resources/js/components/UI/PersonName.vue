<template>
  <span>{{ hasName ? getName() : 'пусто' }}</span>
</template> 

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true,
    },
    field: {
      type: String,
      default: 'default_name',
      required: false,
    }
  },

  methods: {
    getName() {
      return _.get(this.item, this.field)
    }
  },

  computed: {
    hasName() {
      return this.item.names.full.replace(/ /g, '').length > 0
    },
  }
}
</script>
