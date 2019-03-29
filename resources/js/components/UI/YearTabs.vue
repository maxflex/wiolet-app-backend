<template>
  <div v-if='years.length > 0'>
    <div v-if='years.length < 3 || showAllYears'>
      <v-chip v-for="year in years" class='pointer ml-0 mr-3'
        :class="{'primary white--text': year.id == selectedYear}"
        @click='selectedYear = year.id'
        :key='year.id'>{{ year.title }}</v-chip>
    </div>
    <div v-else>
      <v-chip class='pointer' @click='showAllYears = true'>предыдущие годы</v-chip>
      <v-chip class='primary white--text'>
        {{ years.find(e => e.id === selectedYear).title }}
      </v-chip>
    </div>
  </div>
</template>


<script>
export default {
  props: {
    items: {
      type: Array,
      required: false,
      default: null,
    }
  },

  watch: {
    selectedYear(newVal) {
      this.$emit('update:selectedYear', newVal)
    },

    years(newVal) {
      if (newVal.length > 0) {
        this.selectedYear = _.last(newVal).id
      }
    }
  },

  created() {
    if (this.years.length > 0) {
      this.selectedYear = _.last(this.years).id
    }
  },

  data() {
    return {
      selectedYear: null,
      showAllYears: false,
    }
  },
  
  computed: {
    years() {
      return this.items || this.$store.state.data.years 
    }
  }
}
</script>
