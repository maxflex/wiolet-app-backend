<template>
  <FilterTypeBase 
    :apply-button='true'
    :apply-enabled='value.start !== null || value.end !== null'
    style='width: 300px'
  >
    <v-container class='pt-2'>
      <v-layout wrap align-center style='justify-content: space-between'>
        <v-flex style='width: 70px'>
          <DatePicker v-model='value.start' label='начало'></DatePicker>
        </v-flex>
        <v-spacer></v-spacer>
        <v-flex style='width: 70px'>
          <DatePicker v-model='value.end' label='конец'></DatePicker>
        </v-flex>
      </v-layout>
    </v-container>
  </FilterTypeBase>
</template>

<script>
import FilterTypeBase from './Base'
import { FilterTypeMixin } from '@/mixins'
import { DatePicker } from '@/components/UI'

export default {
  components: { FilterTypeBase, DatePicker },

  mixins: [ FilterTypeMixin ],

  data() {
    return {
      value: {
        start: null,
        end: null,
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
