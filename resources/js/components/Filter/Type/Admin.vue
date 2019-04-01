<template>
  <FilterTypeBase 
    :apply-enabled='value.length > 0'
  >
    <v-list dense style='min-width: 300px'>
      <v-list-tile @click='selectMultiple(option)' v-for='(option, index) in $store.state.data.admins' :key='index'>
        <v-list-tile-title :class="{'grey--text': option.is_banned}">{{ option.default_name }}</v-list-tile-title>
        <v-icon style='float: right' v-if='value.indexOf(option[idField]) !== -1'>check</v-icon>
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
}
</script>
