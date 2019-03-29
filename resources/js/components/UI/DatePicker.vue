<template>
  <v-menu
    ref="datepicker"
    :close-on-content-click="false"
    :return-value.sync="date"
    lazy
    transition="scale-transition"
    offset-y
    full-width
    min-width="290px"
  >
    <v-text-field hide-details
      slot="activator"
      v-model='dateFormatted'
      :readonly="readonly"
      :label="label"
    ></v-text-field>
    <v-date-picker no-title
      locale='ru'
      :readonly="readonly"
      v-model="date"
      :first-day-of-week='1'
      @input="$refs.datepicker.save(date)">
    </v-date-picker>
  </v-menu>
</template>

<script>
export default {
  props: {
    value: {},
    label: {},
    readonly: {
      type: Boolean,
      default: false,
      required: false,
    },
  },

  data() {
    return {
      date: null,
      dateFormatted: null,
    }
  },

  created() {
    this.date = this.value
    this.dateFormatted = this.formatDate()
  },
  
  methods: {
    formatDate() {
      if (!this.date) {
        return null
      }
      return moment(this.date).format('DD.MM.YYYY')
    },

    parseDate() {
      if (!this.date) {
        return null
      }
      const [month, day, year] = this.date.split('.')
      // colorLog(`${date} => ` + moment([year, month, day].join('-')).format('YYYY-MM-DD'), 'DeepPink')
      return moment([year, month, day].join('-')).format('YYYY-MM-DD')
    }
  },

  watch: {
    date(val) {
      this.dateFormatted = this.formatDate()
      if (val !== undefined) {
        this.$emit('input', this.date)
      }
    }
  }
}
</script>
