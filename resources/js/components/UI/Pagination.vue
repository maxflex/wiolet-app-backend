<template>
  <div>
    <h1>test</h1>
  </div>
</template>

<script>

import { ShowBy } from '@/components/UI'

export default {
  props: {
    apiUrl: {
      type: String,
      required: true,
    },
  },

  components: { ShowBy },
  
  data() {
    return {
      page: 1,
      loading: false,      
    }
  },

  created() {
    this.loadData()
  },

  watch: {
    page() {
      this.loadData()
    }
  },

  methods: {
    loadData(filters = '') {
      this.loading = true
      axios.get(apiUrl(this.apiUrl)).then(response => {
        this.collection = response.data
        this.loading = false
      })
    },
  },
}
</script>
