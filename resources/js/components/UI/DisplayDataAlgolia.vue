<template>
  <div>
    <Loader v-if='loading' />

    <div class='flex-items align-center'>
      <YearTabs v-if='tabs' :items='tabsWithData' :selected-year.sync='selectedTab' />
      <AllFilterAlgolia v-if='filters !== null' :items='filters' :pre-installed='preInstalledFilters' :sort='sort' :facets='facets' @updated='filtersUpdated' />
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

import AllFilterAlgolia from '@/components/FilterAlgolia/All'
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
    containerClass: {
      type: String,
      default: '',
    }
  },

  components: { AllFilterAlgolia, InfiniteLoading },

  data() {
    return {
      page: 1,
      loading: true,
      // для пересоздания компонента
      infinite_loading: true,
      data: [],
      selectedTab: null,
      facets: null,
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
          this.data = response.data.data.hits
          // на протяжении всех страниц facets один и тот же
          if (response.data.data.hasOwnProperty('facets') ) {
            this.facets = response.data.data.facets
          }
        } else {
          this.data.push(...response.data.data.hits)
        }
        if (this.tabs && this.tabsWithData.length) {
          this.selectedTab = this.tabsWithData.slice(-1)[0].id
        }
        if (this.paginate !== null) {
          if (response.data.current_page >= response.data.last_page) {
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
        //   if (response.data.data.meta.current_page >= response.data.data.meta.last_page) {
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
      return this.tabs ?
        this.data.filter(e => e.year === this.selectedTab) :
        this.data
    },

    tabsWithData() {
      return tabsWithData(this.data)
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
