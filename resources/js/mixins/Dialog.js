export default {
  data() {
    return {
      dialog: false,
      saving: false,
      item: null,
      edit_mode: false,
      loading: true,
      edit_mode: true,
      destroying: false,
    }
  },

  methods: {
    open(item_id = null, defaults = {}) {
      this.item = null
      this.dialog = true
      if (item_id !== null) {
        this.edit_mode = true
        this.loadData(item_id)
      } else {
        this.edit_mode = false
        this.item = {...this.MODEL_DEFAULTS, ...defaults }
        this.loading = false
      }
    },

    loadData(item_id) {
      this.loading = true
      axios.get(apiUrl(this.API_URL, item_id)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },

    destroy() {
      this.destroying = true
      axios.delete(apiUrl(this.API_URL, this.item.id)).then(r => {
        this.$emit('updated')
        this.dialog = false
        this.waitForDialogClose(() => this.destroying = false)
      })
    },

    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(this.API_URL, this.item.id), this.item).then(r => this.item = r.data)
      } else {
        await axios.post(apiUrl(this.API_URL), this.item).then(r => this.item = r.data)
      }
      this.$emit('updated', this.item)
      colorLog("Emitting updated", 'Turquoise')
      this.dialog = false
      this.waitForDialogClose(() => this.saving = false)
    }
  }
}