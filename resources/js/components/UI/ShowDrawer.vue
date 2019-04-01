<template>
  <div class='show-drawer' 
    @mouseover="hover = true"
    @mouseleave="hover = false"
    @click="$store.commit('toggleDrawer', true)">
    <transition
      enter-active-class="animated slideInLeft"
      leave-active-class="animated slideOutLeft">
        <div class='show-drawer__btn' v-if='hover'>
          <v-icon color='white' class='animated fadeInLeft'>arrow_forward_ios</v-icon>
        </div>
    </transition>
  </div>
</template>



<script>
export default {
  data() {
    return {
      hover: false,
    }
  },
}
</script>



<style lang='scss'>
  .show-drawer {
    position: fixed !important;
    height: 100%;
    z-index: 99;
    width: 20px;
    &__btn {
      animation-duration: .3s;
      background: #213846;
      display: flex;
      align-items: center;
      height: 100%;
      cursor: pointer;
      & .v-icon {
        animation-duration: .5s;
        position: relative;
        left: -2px;
      }
    }
  }
</style>