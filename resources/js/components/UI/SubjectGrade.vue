<template>
  <span>
    <span v-if='item.subject_id'>{{ getData('subjects', item.subject_id).three_letters }}</span><span v-if='item.subject_id && item[gradeField]'>–</span><span v-if='item[gradeField]'>{{ getData('grades', item[gradeField]).short }}</span>
  </span>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true,
    },
    gradeField: {
      type: String,
      required: false,
      default: 'grade_id',
    }
  }
}
</script>
