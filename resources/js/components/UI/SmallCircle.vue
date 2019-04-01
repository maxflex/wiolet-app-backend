<template>
  <div :style="{height: size + 'px', width: size + 'px'}" :class="'circle ' + className"></div>
</template>

<script>
export default {
  props: {
    size: {
      type: Number,
      required: false,
      default: 8,
    },
    className: {
      type: String,
      required: false,
      default: 'red',
    }
  },
}
</script>

<style lang="scss">
  .circle {
    border-radius: 50%;
    display: inline-block;
  }
</style>