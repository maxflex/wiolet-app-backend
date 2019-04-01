export default {
  props: {
    item: {
      type: Object,
      required: true,
    },
    filterValue: {
      required: false,
      default: null,
    },
    facet: {
      type: Object,
      required: false,
      default: null,
    }
  },

  data() {
    return {
      value: [],
    }
  },

  created() {
    if (this.filterValue !== null) {
      this.value = clone(this.filterValue)
    }
  },

  methods: {
    apply() {
      this.$emit('selected', _.pick(this, ['item', 'value']))
    },

    select(option) {
      this.value = option[this.idField]
      this.apply()
    },

    selectMultiple(option) {
      console.log('selecting', this.idField, option[this.idField])
      const value = option[this.idField]
      const value_index = this.value.indexOf(value)
      if (value_index === -1) {
        this.value.push(value)
      } else {
        this.value.splice(value_index, 1)
      }
    },
  },

  computed: {
    titleField() {
      return this.item.textField || 'title'
    },

    idField() {
      return this.item.valueField || 'id'
    },
  }
}