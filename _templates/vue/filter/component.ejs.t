---
to: resources/js/components/Filter/Type/<%= Name %>.vue
---
<template>
  <FilterTypeBase>
   
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
