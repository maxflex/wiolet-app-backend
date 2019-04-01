export default {
  props: {
    displayOptions: {
      type: Object,
      required: false,
      default: () => {},
    },
  },

  computed: {
    show() {
      return {
        ...this.defaultDisplayOptions,
        ...this.displayOptions,
      }
    },
  }
}