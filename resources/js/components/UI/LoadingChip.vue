<template>
  <div class='loading-chip' v-if='show'>
    <span class='loading-chip__percent' v-show='loading'>{{ file.percentComplete }}%</span>
    <div class='loading-chip__progress' v-if='loading'
      :style="{width: file.percentComplete + '%'}">
      <v-chip close  @input='close'>
        <span class='invisible'>{{ file.name | truncate(25) }}</span>
      </v-chip>
    </div>
    <v-chip close class='loading-chip__real' :class="{'loading-chip__real_loading': loading}" @input="close">
      <span>{{ file.name | truncate(25) }}</span>
    </v-chip>
  </div>
</template>

<script>
export default {
  props: {
    file: {
      type: Object,
      required: true,
    },
    dev: {
      type: Object,
    }
  },

  data() {
    return {
        show: true,
    }
  },

  methods: {
    close() {
      if (this.loading) {
        this.file.source.cancel()
      } else {
        this.$emit('remove')
      }
      this.file.clear()
      this.show = false
    },
  },

  computed: {
    loading() {
      // return this.dev.loading
      // return this.file.sending
      return ['progress', 'queue'].indexOf(this.file.state) !== -1
    }
  },
}
</script>


<style scoped lang='scss'>
  .loading-chip {
    display: inline-block;
    position: relative;
    &__progress {
      position: absolute;
      overflow: hidden;
      transition: all .3s linear;
      z-index: 1;
    }
    &__real {
      transition: none;
      & span {
        transition: all .3s linear;
      }
      &_loading {
        opacity: .25;
        & span {
          opacity: 0;
        }
      }
    }
    &__percent {
      position: absolute;
      width: calc(100% - 32px);
      z-index: 2;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100%;
      padding-left: 32px;
    }
  }
</style>

