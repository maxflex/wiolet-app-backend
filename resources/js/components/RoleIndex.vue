<template>
  <div>
    <component :is='items[componentName]' />
  </div>
</template>

<script>

export default {
  props: {
    items: {
      type: Object,
      required: true,
    }
  },

  computed: {
    componentName() {
      return `${this.$store.state.user.class_basename}Index`
    }
  }
}
</script>
