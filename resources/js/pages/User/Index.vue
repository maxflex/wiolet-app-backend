<template>
  <div>
    <DisplayData />
  </div>
</template>

<script>

import DisplayData from '@/components/UI/DisplayData'
import { API_URL, FILTERS } from '@/components/User'
// import UserList from '@/components/User/List'

export default {
  components: { DisplayData },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
