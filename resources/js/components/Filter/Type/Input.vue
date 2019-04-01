<template>
  <FilterTypeBase
    :apply-button='true'
    :apply-enabled="value.length > 0"
  >
    <div style='padding: 5px 16px 0'>
      <v-text-field hide-details v-model='value' :label='item.label' />
    </div>
  </FilterTypeBase>
</template>

<script>
import FilterTypeBase from './Base'
import { FilterTypeMixin } from '@/mixins'

export default {
  components: { FilterTypeBase },

  mixins: [ FilterTypeMixin ],

  methods: {
    select(option) {
      this.value = option[this.idField]
      this.apply()
    }
  }
}
</script>
