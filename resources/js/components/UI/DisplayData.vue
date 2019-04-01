<template>
  <div>
    <Loader v-if='loading' />

    <div class='flex-items align-center'>
      <YearTabs v-if='tabs' :items='tabsWithData' :selected-year.sync='selectedTab' />
      <div v-else-if='customTabs !== null'>
        <v-chip v-for="(label, id) in tabsWithData" class='pointer ml-0 mr-3'
          :class="{'primary white--text': id == selectedTab}"
          @click='selectedTab = id'
          :key='id'
        >
          {{ label }}
        </v-chip>
      </div>
      <AllFilter v-if='filters !== null' :items='filters' :pre-installed='preInstalledFilters' :sort='sort' @updated='filtersUpdated' />
      <v-spacer></v-spacer>
      <slot name='buttons' v-if='items.length > 0'></slot>
    </div>

    <!-- <NoData v-if='items.length === 0' :class="{'invisible': loading}" /> -->

    <v-container grid-list-md fluid :class="`px-0 ${containerClass} ${loading ? 'invisible' : ''}`">
      <v-layout row wrap class='relative'>
        <v-flex xs12>
          <!-- <div v-if='sort !== undefined' class='grey--text darken-3 mb-3 text-md-right caption flex-items justify-end'>
            cортировка:
            <v-menu class='mx-1'>
              <span slot='activator' class='sort-label'>{{ selectedSort.label }}</span>
              <v-list dense>
                <v-list-tile v-for='(s, index) in sort' :key='index' @click='setSort(index)'>
                  <v-list-tile-title>{{ s.label }}</v-list-tile-title>
                </v-list-tile>
              </v-list>
            </v-menu>
            <v-icon class='pr-3' small @click='toggleSortType'>
              {{ sort.find(e => e.selected).type === 'asc' ? 'arrow_upward' : 'arrow_downward' }}
            </v-icon>
          </div> -->

          <slot name='items' :items='items'></slot>

          <infinite-loading v-if='paginate && infinite_loading'
            @infinite='loadData' ref='InfiniteLoading' :distance='2000' spinner='spiral' class='mt-3'>
            <div slot='no-more'></div>
            <div slot='no-results'></div>
          </infinite-loading>
        </v-flex>
      </v-layout>
    </v-container>

    <slot name='buttons-bottom'></slot>
  </div>
</template>

<script>

import AllFilter from '@/components/Filter/All'
import { tabsWithData } from '@/other/functions'
import InfiniteLoading from 'vue-infinite-loading'

export default {
  props: {
    apiUrl: {
      type: String,
      required: true,
    },
    filters: {
      type: Array,
      required: false,
      default: null,
    },
    paginate: {
      type: Number,
      required: false,
      default: null,
    },
    invisibleFilters: {
      type: Object,
      required: false,
    },
    preInstalledFilters: {
      type: Array,
      required: false,
    },
    sort: {
      type: Array,
      required: false,
    },
    tabs: {
      type: Boolean,
      default: false,
    },
    customTabs: {
      type: Object,
      default: null,
      required: false,
    },
    containerClass: {
      type: String,
      default: '',
    }
  },

  components: { AllFilter, InfiniteLoading },

  data() {
    return {
      page: 1,
      loading: true,
      // для пересоздания компонента
      infinite_loading: true,
      data: [],
      selectedTab: null,
    }
  },

  created() {
    if (this.paginate === null) {
      this.loadData()
    }
  },

  methods: {
    loadData(state) {
      axios.get(apiUrl(this.apiUrl) + queryString({
        page: this.page,
        paginate: this.paginate || '',
        ...this.current_filters,
        ...this.invisibleFilters,
        // ...this.getSort(),
      })).then(response => {
        this.loading = false
        if (this.page === 1 || this.paginate === null) {
          this.data = response.data.data
          if (this.tabs && this.tabsWithData.length > 0) {
            this.selectedTab = this.tabsWithData.slice(-1)[0].id
          }
          if (this.customTabs !== null && Object.keys(this.tabsWithData).length > 0) {
            this.selectedTab = Object.keys(this.tabsWithData)[0]
          }
        } else {
          this.data.push(...response.data.data)
        }
        if (this.paginate !== null) {
          if (response.data.meta.current_page === response.data.meta.last_page) {
            colorLog('COMPLETE', 'Turquoise')
            state.complete()
          } else {
            colorLog('LOADED', 'PaleVioletRed')
            state.loaded()
          }
          this.page++
        }
        // } else {
        //   colorLog('COMPLETE2', 'Turquoise')
        //   state.complete()
        // }
        // if (this.paginate !== null) {
        //   if (response.data.meta.current_page >= response.data.meta.last_page) {
        //     colorLog('COMPLETE', 'Turquoise')
        //     this.$refs.InfiniteLoading.stateChanger.complete()
        //   } else {
        //     colorLog('LOADED', 'PaleVioletRed')
        //     this.$refs.InfiniteLoading.stateChanger.loaded()
        //   }
        //   this.page++
        // }
        this.loading = false
      })
    },

    reloadData() {
      colorLog('Reloading data', 'Turquoise')
      this.loading = true
      this.page = 1
      this.data = []
      this.infinite_loading = false
      Vue.nextTick(() => this.infinite_loading = true)
      // this.loadData()
      // this.$refs.InfiniteLoading.$emit('infinite', this.$refs.InfiniteLoading.stateChanger)
      // Vue.nextTick(() => {
      //   this.$refs.InfiniteLoading.attemptLoad()
      // })
    },

    getSort() {
      if (this.sort !== undefined) {
        return {
          sort_by: this.selectedSort.field,
          sort_type: this.selectedSort.type,
        }
      }
      return {}
    },

    toggleSortType() {
      this.loading = true
      this.selectedSort.type = this.selectedSort.type === 'asc' ? 'desc' : 'asc'
      this.reloadData()
    },

    setSort(index) {
      this.loading = true
      this.selectedSort.selected = false
      this.sort[index].selected = true
      this.reloadData()
    },

    filtersUpdated(filters, initial_set) {
      this.current_filters = filters
      if (!initial_set) {
        this.reloadData()
      }
    },
  },

  computed: {
    selectedSort() {
      if (this.sort) {
        return this.sort.find(e => e.selected)
      }
    },

    items() {
      if (this.tabs) {
        return this.data.filter(e => e.year === this.selectedTab)
      } else if (this.customTabs !== null) {
        return this.data.filter(e => e[this.customTabs.field] === this.selectedTab)
      }
      return this.data
    },

    tabsWithData() {
      if (this.tabs) {
        console.log(this.tabs, this.data)
        return tabsWithData(this.data)
      } else if (this.customTabs !== null) {
        console.log(this.customTabs, this.data)
        return _.omitBy(this.customTabs.data, (label, key) => {
          return this.data.findIndex(e => e[this.customTabs.field] === key) === -1
        })
      }
    },
  }
}
</script>

<style lang="scss" scoped>
  .sort-label {
    border-bottom: 1px dotted grey;
    cursor: pointer;
  }
</style>
