<template>
  <div>
    <VueEditor
      :editorOptions="options"
      :editorToolbar="toolbar"
      v-model='text'
    />
    <!-- <input id="file" type="file" class="form-control"/> -->
  </div>
</template>

<script>
import { VueEditor, Quill } from 'vue2-editor'
import ImageDrop from '@/vendor/ImageDrop'

Quill.register('modules/imageDrop', ImageDrop)
// const icons = Quill.import('ui/icons')
// icons['file'] = '<i class="v-icon material-icons">attach_file</i>'

export default {
  props: ['value'],

  data() {
    const vm = this
    return {
      text: '',
      options: {
        modules: {
          imageDrop: true,
        }
      },
      toolbar: [
        [{ 'size': [false, 'large', 'huge'] }],  // custom dropdown
        ['bold', 'italic', 'underline'],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['link', 'image'],
        // ['image', 'code-block']
      ],
    }
  },

  created() {
    this.text = this.value
  },

  watch: {
    text(newVal) {
      this.$emit('input', newVal)
    }
  },

  methods: {
    // editorReady(quill) {
    //   const toolbar = quill.getModule('toolbar')
    //   toolbar.addHandler('file', () => {
    //     console.log('workds')
    //   })
    // }
  }
}
</script>
