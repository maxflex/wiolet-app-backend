<template>
  <div class='add-btn' :class="{
    'add-btn_animated': animated === '',
    'add-btn_absolute': absolute === '',
  }">
    <div class='add-btn__label'>
      {{ label }}
    </div>
    <div class='add-btn__icon'>
      <v-icon color='white' v-html='icon'></v-icon>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: 'добавить',
      required: false,
    },

    animated: {
      type: String,
      required: false,
    },

    // костылёк. сделано, чтобы AddBtn был внутри List'a
    // может, потом переделаю
    absolute: {
      type: String,
      required: false,
    },

    icon: {
      type: String,
      required: false,
      default: 'add',
    }
  }
}
</script>

<style lang="scss" scoped>
$size: 34px;
$color: #f44336;
$animation-speed: .3s;

.add-btn {
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
    left: 0;
    position: absolute;
  }
  &_animated {
    & .add-btn__icon {
      transition: all ease-in-out $animation-speed;
      box-shadow: -1020px 0 0 1000px #f2f2f2;
      clip-path: inset(0px 0px -10px -1000px);
      left: calc(100% - #{$size});
    }
    &:hover {
      & .add-btn {
        &__icon {
          left: 0;
        }
      }
    }
  }
  &_absolute {
    position: absolute;
    right: 0;
    top: -62px;
  }
}
</style>
