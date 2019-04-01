<template>
  <div :style="{height: size + 'px'}">

  </div>
</template>

<script>
export default {
  props: {
    size: {
      type: Number,
      required: false,
      default: 32,
    }
  }
}
</script>
