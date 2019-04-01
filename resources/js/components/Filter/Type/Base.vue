<template>
  <v-card class='relative'>
    <!-- padding-bottom: 60px !important; -->
    <v-card-text class='pa-0' 
      :class="{'with-apply-button': applyButton || $parent.filterValue !== null}"
      style='max-height: 700px; overflow-y: scroll'>
      <slot></slot>
    </v-card-text>
    <v-card-actions v-if='applyButton || $parent.filterValue !== null' class='justify-center' >
      <v-btn color="blue darken-1" small flat 
        v-if='$parent.filterValue !== null'
        @click='togglePin'>{{ isPinned() ? 'открепить' : 'закрепить' }}</v-btn>
      <v-btn color="blue darken-1" small flat 
        v-if='applyButton'
        :disabled='!applyEnabled' 
        @click='$parent.apply'>Применить</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { LOCAL_STORAGE_KEY } from '@/components/Filter'

export default {
  props: {
    applyButton: {
      type: Boolean,
      default: true,
    },
    applyEnabled: {
      type: Boolean,
      default: false,
    }
  },

  data() {
    return {
      LOCAL_STORAGE_KEY,
    }
  },

  methods: {
    togglePin() {
      if (this.isPinned()) {
        let filters = this.getFiltersFromLocalStorage()
        delete filters[this.$route.name][this.$parent.item.field]
        this.saveFiltersToLocalStorage(filters)
      } else {
        let filters = {}
        if (LOCAL_STORAGE_KEY in localStorage) {
          filters = this.getFiltersFromLocalStorage()
        }
        if (! filters.hasOwnProperty(this.$route.name)) {
          filters[this.$route.name] = {}
        }
        filters[this.$route.name][this.$parent.item.field] = this.$parent.value
        this.saveFiltersToLocalStorage(filters)
      }
      Vue.nextTick(() => this.$forceUpdate())
    },

    getFiltersFromLocalStorage() {
      return JSON.parse(localStorage.getItem(LOCAL_STORAGE_KEY))
    },

    saveFiltersToLocalStorage(filters) {
      localStorage.setItem(LOCAL_STORAGE_KEY, JSON.stringify(filters))
    },

    isPinned() {
      if (LOCAL_STORAGE_KEY in localStorage) {
        const filters = this.getFiltersFromLocalStorage()
        return filters.hasOwnProperty(this.$route.name) && filters[this.$route.name].hasOwnProperty(this.$parent.item.field)
      }
      return false
    },
  },
}
</script>


<style lang="scss" scoped>
  .v-card__actions {
    position: fixed;
    bottom: 0px;
    width: 100%;
    background: white;
    box-shadow: 0 0 12px 12px white;
  }
  .with-apply-button {
    padding-bottom: 60px !important;
  }
</style>
>

</style>
