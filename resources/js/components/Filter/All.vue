<template>
  <div>
    <v-menu v-for='(filter, index) in filters' :key='index'
      bottom
      origin="center center"
      transition="scale-transition"
      :close-on-content-click='false'
      v-model="used_filter_menu[filter.item.field]"
    >
      <v-chip slot='activator'>
        {{ filter.item.label }}: {{ getSelectedLabel(filter) }}
        <v-avatar class='ma-0 close-button'>
          <v-hover>
            <v-icon slot-scope="{ hover }" class='pointer'
              @click='close(index)'
              :class="hover ? 'black--text': 'grey--text text--darken-1'">cancel</v-icon>
          </v-hover>
        </v-avatar>
      </v-chip>

      <component 
        @selected='apply'
        :is="getTypeComponentName(filter.item)" 
        :item='filter.item' 
        :filter-value='filter.value' 
        v-if='used_filter_menu[filter.item.field]' 
      />
      <!-- <SelectFilterDialog 
        :item='filter.item' 
        :filter-value='filter.value' 
        @selected='apply' /> -->
    </v-menu>

    <v-menu
      v-show='notUsedFilters.length'
      bottom
      origin="center center"
      transition="scale-transition"
      :close-on-content-click='false'
      v-model="menu"
    >
      <v-chip slot='activator' class='pointer white--text' color='primary' @click='item = null'>
        <v-avatar class='pl-2'>
          <v-icon>equalizer</v-icon>
        </v-avatar>
        Добавить фильтр
      </v-chip>

      <v-list dense v-if='item === null'>
        <v-list-tile v-for='(item, index) in notUsedFilters' @click='select(item)' :key='index' :class="{'sort-tile': item.type === 'sort'}">
          <v-list-tile-title>
            {{ item.label }}
          </v-list-tile-title>
        </v-list-tile>
        <!-- <div class='menu-separator grey lighten-2' v-if='sort !== null'></div>
        <v-list-tile v-if='sort !== null' @click='select(sort)'>
          <v-list-tile-title>сортировать</v-list-tile-title>
        </v-list-tile> -->
      </v-list>
      <component :is="getTypeComponentName(item)" :item='item' @selected='apply' v-else />
    </v-menu>
  </div>
</template>

<script>
import { LOCAL_STORAGE_KEY } from './'

import { 
  TypeSelect,
  TypeAdmin,
  TypeMultiple,
  TypeInterval,
  TypeInput,
  TypeSort,
} from './Type'

const components = { 
  TypeSort,
  TypeInput,
  TypeSelect,
  TypeAdmin,
  TypeMultiple,
  TypeInterval,
}

export default {
  props: {
    // Опции выбора фильтров
    items: {
      type: Array,
      default: () => [],
      required: true,
    },

    // Предустановленные фильтры
    // Использование: this.pre_installed_filters.push({item: this.filters[3], value: [group.grade_id]})
    // TODO: сделать удобнеее {filter_index: value}
    preInstalled: {
      type: Object,
      default: null,
      required: false,
    },

    // sort: {
    //   type: Object,
    //   required: false,
    //   default: null,
    // }
  },

  components: components,

  created() {
    if (this.preInstalled === null) {
      if (LOCAL_STORAGE_KEY in localStorage) {
        const filters = JSON.parse(localStorage.getItem(LOCAL_STORAGE_KEY))
        if (filters.hasOwnProperty(this.$route.name) && Object.keys(filters[this.$route.name]).length > 0) {
          this.setPreInstalled(filters[this.$route.name])
        }
      }
    } else {
      this.setPreInstalled(this.preInstalled)
    }
    this.emit(true)
  },

  data() {
    return {
      filters: [],
      // выбранный фильтр
      item: null,
      value: null,
      menu: false,
      used_filter_menu: {},
    }
  },

  // watch: {
  //   menu(newVal, oldVal) {
  //     if (newVal === false && oldVal === true) {
  //       setTimeout(() => this.back(), 500)
  //     }
  //   }
  // },

  methods: {
    // 1 стадия выбора фильтра
    select(item) {
      this.item = clone(item)
    },

     // 2 стадия выбора фильтра – выбрать и применить
    apply(item) {
      // был ли фильтр выбран ранее?
      const index = this.filters.findIndex(e => e.item.field === item.item.field)
      if (index === -1) {
        this.filters.push(item)
      } else {
        this.filters.splice(index, 1, item)
      }
      this.emit()
      this.menu = false
      this.used_filter_menu = {}
    },

    close(index) {
      this.filters.splice(index, 1)
      this.emit()
    },

    emit(initial_set = false) {
      const filters = {}
      this.filters.forEach(e => {
        let value = e.value
        if (Array.isArray(value)) {
          value = value.join(',')
        }
        if (_.isObject(value)) {
          value = JSON.stringify(value)
        }
        filters[e.item.field] = value
      })
      console.log('emitting', filters, initial_set)
      this.$emit('updated', filters, initial_set)
    },

    sortDialog() {

    },

    getSelectedLabel(filter) {
      // TODO: определение типа внутри компонента
      // return components[this.getTypeComponentName(filter.item)].methods.getSelectedLabel(filter)
      switch(filter.item.type) {
        case 'sort': 
          return filter.item.options.find(e => e.field == filter.value).title
        case 'select':
          return _.get(
            filter.item.options.find(e => e[this.getItemValue(filter.item)] == filter.value), 
            this.getItemText(filter.item)
          )
        case 'multiple': {
          const label = []
          if (Array.isArray(filter.value)) {
            filter.value.forEach(v => {
              label.push(_.get(
                filter.item.options.find(e => e[this.getItemValue(filter.item)] === v), 
                this.getItemText(filter.item)
              ))
            })
          }
          return label.join(', ')
        }
        case 'date':
          return this.$options.filters.date(filter.value)
        case 'admin': {
          const label = []
          filter.value.forEach(id => {
            label.push(this.$store.state.data.admins.find(admin => admin[filter.item.valueField] === id).default_name)
          })
          return label.join(', ')
        }
        case 'interval': {
          const label = []
          if (filter.value.start !== null) {
            label.push('с ' + this.$options.filters.date(filter.value.start))
          }
          if (filter.value.end !== null) {
            label.push('по ' + this.$options.filters.date(filter.value.end))
          }
          return label.join(' ')
        }
        default:
          if (Array.isArray(filter.value)) {
            console.log('array', filter)
            return filter.value.map(v => {
              return _.get(
                filter.item.options.find(e => e.id === v), 
                this.getItemText(filter.item)
              )
            }).join(', ')
          }
          return filter.value
      }
    },

    getItemValue(item) {
      return item.valueField ? item.valueField : 'id'
    },

    getItemText(item) {
      return item.textField ? item.textField : 'title'
    },

    getTypeComponentName(item) {
      // если массив, значит сортировка
      // if (Array.isArray(item)) {
      //   return 'Sort'
      // } 
      const type = item.type
      return 'Type' + (type.charAt(0).toUpperCase() + type.slice(1))
    },

    setPreInstalled(filters) {
      const items = []
      Object.keys(filters).forEach(field => {
        items.push({
          item: this.items.find(e => e.field === field),
          value: filters[field]
        })
      })
      if (items.length > 0) {
        this.filters = items
      }
    },
  },

  computed: {
    notUsedFilters() {
      const not_used_filters = []
      this.items.forEach(item => {
        let filter_used = false
        this.filters.forEach(filter => {
          if (filter_used) {
            return
          }
          if (filter.item.field === item.field) {
            filter_used = true
          }
        })
        if (!filter_used) {
          not_used_filters.push(item)
        }
      })
      return not_used_filters
    },
  }
}
</script>

<style lang='scss'>
  .close-button {
    & i {
      font-size: 20px;
      height: 20px !important;
      width: 20px !important;
      left: 10px;
      position: relative;
    }
  }

  .sort-tile {
    &:before {
      content: '';
      width: 90%;
      border-bottom: 1px solid #e0e0e0;
      display: block;
      /* padding: 10px 0; */
      margin: 5px auto;
    }
    // height: 100px;
  }
</style>
