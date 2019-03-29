<template>
  <v-list-tile @click="$router.push({name: item.route})" :class="{'active': isActive()}">
      <v-list-tile-action>
          <v-icon>{{ item.icon }}</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
          <v-list-tile-title>
            {{ item.label }}
            <span class='menu-counter' v-if="item.hasOwnProperty('counter')">{{ $store.state.counters[item.counter] || '' }}</span>
          </v-list-tile-title>
      </v-list-tile-content>
  </v-list-tile>
</template>



<script>
export default {
  props: {
    item: {
      type: Object,
      required: true,
    }
  },

  methods: {
    isActive() {
      // SmsMessageIndex => SmsMessage
      if (this.$route.name !== null) {
        const currentRouteComponent = this.$route.name.match(/[A-Z][a-z]+/g)
        currentRouteComponent.splice(-1, 1)

        const currentMenuComponent = this.item.route.match(/[A-Z][a-z]+/g)
        currentMenuComponent.splice(-1, 1)

        return currentRouteComponent.join('') === currentMenuComponent.join('')
      }
    }
  }
}
</script>
