<template>
  <div>
    <DisplayData :api-url='API_URL' :filters='FILTERS'>
      <template slot='items' slot-scope='{ items }'>
        <EventList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import DisplayData from '@/components/UI/DisplayData'
import { API_URL, FILTERS,  } from '@/components/Event'
import EventList from '@/components/Event/List'

export default {
  components: { DisplayData, EventList },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
