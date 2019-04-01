<template>
  <!-- КРУЖОК С ОЦЕНКОЙ -->
  <div class='score-circle caption font-weight-medium' :class="{
    'grey lighten-1': !score,
    'red': score < 4 && score > 0,
    'orange': score === 4,
    'green': score === 5,
  }">
    {{ score || '–' }}
  </div>
</template>



<script>
export default {
  props: {
    score: {
      required: true,
    }
  }
}
</script>



<style lang="scss">
.score-circle {
  $size: 20px;
  height: $size;
  width: $size;
  border-radius: 50%;
  color: white;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: default;
}
</style>
