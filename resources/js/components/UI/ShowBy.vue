<template>
  <div class='flex-items align-center'>
    <div>
      <span>отображать по: </span>
    </div>
    <v-select style='width: 75px; margin: 0 0 0 20px; padding: 0' hide-details
      v-model="selected"
      :items="options"
    ></v-select>
  </div>
</template>

<script>
export default {
  props: ['value'],

  data() {
    return {
      selected: null,
      options: [
        10,
        30,
        50,
        'все',
      ]
    }
  },

  created() {
    this.selected = this.value
  },

  watch: {
    selected(newVal, oldVal) {
      if (newVal != oldVal) {
        this.$emit('changed', newVal)
      }
    }
  }

  // methods: {
  //   select(option) {
  //     this.selected = option
      
  //   }
  // }
}
</script>
