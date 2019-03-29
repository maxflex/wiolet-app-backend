<template>
  <span>
    <span v-for='(branch_id, index) in items' :key='branch_id'>
      <span :style="{color: getData('branches', branch_id).color}">{{ getData('branches', branch_id).short }}{{ index === items.length - 1 ? '' : ', ' }}</span>
    </span>
  </span>
</template>

<script>
export default {
  props: ['items']
}
</script>
