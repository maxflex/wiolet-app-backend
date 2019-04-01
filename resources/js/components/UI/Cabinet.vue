<template>
  <span>
    <span :style="{color: cabinet.color}">
      {{ cabinet.title }}
    </span>
  </span>
</template>

<script>
export default {
  props: {
    id: {
      type: Number,
      required: true
    }
  },

  computed: {
    cabinet() {
      return this.getData('cabinets', this.id)
    }
  }
}
</script>
