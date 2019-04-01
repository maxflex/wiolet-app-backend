<template>
  <div>
    <DisplayData :api-url='API_URL' :paginate='50' :filters='FILTERS'>
        <template slot='items' slot-scope='{ items }'>
            <UserList :items='items' />
        </template>
    </DisplayData>
  </div>
</template>

<script>

import DisplayData from '@/components/UI/DisplayData'
import { API_URL, FILTERS } from '@/components/User'
import UserList from '@/components/User/List'

export default {
  components: { DisplayData, UserList },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
