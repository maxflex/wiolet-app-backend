<template>
  <div>
    <div class='add-btn-animated'>
      <div class='add-btn-animated__label'>
        {{ label }}
      </div>
      <div class='add-btn-animated__icon'>
        <v-icon color='white'>add</v-icon>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: null,
      required: false,
    }
  }
}
</script>

<style lang="scss" scoped>
$size: 34px;
$color: #f44336;
$animation-speed: .3s;

.add-btn-animated {
  margin: 4px;
  background: $color;
  // background: yellowgreen;
  color: white;
  border-radius: 40px;
  height: $size;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0 10px;
  position: relative;
  overflow: hidden;
  cursor: pointer;
  &__label {
    padding-left: #{$size - 10px};
  }
  &__icon {
    background: $color;
    color: white;
    height: $size;
    width: $size;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    left: calc(100% - #{$size});
    transition: all ease-in-out $animation-speed;
    box-shadow: -1020px 0 0 1000px #f2f2f2;
    clip-path: inset(0px 0px -10px -1000px);
  }
  &:hover {
    & .add-btn-animated {
      &__icon {
        left: 0;
      }
    }
  }
}
</style>
