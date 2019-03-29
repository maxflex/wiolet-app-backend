<template>
  <div class='loader-wrapper' :class="{
    'loader-wrapper_transparent': transparent === ''
  }">
    <v-progress-circular :size="50" color="primary" indeterminate></v-progress-circular>
  </div>
</template>

<script>
export default {
  props: ['transparent'],
}
</script>



<style lang="scss" scoped>
  @import '~sass/_variables';

  .loader-wrapper {
    position: absolute;
    top: 0;
    left: 0;
    height: 101%;
    width: 100%;
    display: flex;
    z-index: 1;
    justify-content: center;
    align-items: center;
    background: rgba(white, .8);
    border-radius: $border-radius;
    &_transparent {
      min-height: 300px;
      background: none;
    }
    &_halfscreen-dialog {
      height: calc(80vh - 64px);
      top: 10px;
    }
    &_fullscreen-dialog {
      height: calc(100vh - 64px);
      top: 10px;
    }
  }
</style>
