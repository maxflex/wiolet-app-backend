<template>
  <FilterTypeBase :apply-button='false'>
    <v-list dense style='min-width: 220px'>
      <v-list-tile @click='select(option)' v-for='option in item.options' :key='option.field'>
        <v-list-tile-title>{{ option.title }}</v-list-tile-title>
      </v-list-tile>
    </v-list>
  </FilterTypeBase>
</template>

<script>
import FilterTypeBase from './Base'
import { FilterTypeMixin } from '@/mixins'

export default {
  components: { FilterTypeBase },

  mixins: [ FilterTypeMixin ],


  data() {
    return {
      value: {
        field: null,
        type: 'asc',
      },
    }
  },

  methods: {
    select(option) {
      this.value = option[this.idField]
      this.apply()
    }
  }
}
</script>
