<template>
  <span class='grey--text'>
    {{ item[userField] ? item[userField].default_name : 'неизвестно' }}
    {{ item.created_at | date-time }}
  </span>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true,
    },

    userField: {
      type: String,
      default: 'createdUser'
    },
  },
}
</script>
