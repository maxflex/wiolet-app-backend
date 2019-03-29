<template>
  <div>
    <!-- <v-slide-y-transition :group='true'> -->
      <div class='flex-items align-flex-start mb-3' v-for='(comment, index) in comments' :key='comment.id'>
        <Avatar :photo='comment.createdUser.photo' :size='50' class='mr-3' />
        <div>
          <div>
            <b>{{ comment.createdUser.default_name }}</b>
            <span class='d-inline-block ml-1 grey--text'>
              {{ comment.created_at | date-time }}
              <span class='cursor-default' v-if='comment.created_at != comment.updated_at' :title="'Отредактировано ' + $options.filters['date-time'](comment.updated_at)">(edited)</span>
            </span>
            <v-menu left>
              <v-btn slot='activator' flat icon small color="black" class='ma-0' v-if='comment.createdUser.id === $store.state.user.id'>
                <v-icon>more_horiz</v-icon>
              </v-btn>
              <v-list dense>
                <v-list-tile @click='edit(index)'>
                    <v-list-tile-action>
                      <v-icon>edit</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>Редактировать</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click='destroy(index)'>
                    <v-list-tile-action>
                      <v-icon>close</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>Удалить</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
              </v-list>
            </v-menu>
          </div>
          <div v-if='editing_comment_index === index'>
            <v-text-field hide-details class='pa-0 ma-0' ref='comments'
              v-model='editing_comment_text'
              @blur='editing_comment_index = null'
              @keydown.esc='editing_comment_index = null'
              @keydown.enter='saveEdited'
              :loading='editing_saving'
            ></v-text-field>
          </div>
          <div v-else>
            {{ comment.text }}
          </div>
        </div>
      </div>
    <!-- </v-slide-y-transition> -->
    <a v-show='!commenting' class='grey--text' @click='startCommenting'>комментировать</a>
    <div class='flex-items align-center' v-show='commenting'>
      <div>
        <Avatar :photo='$store.state.user.photo' :size='50' class='mr-3' />
      </div>
      <div style='flex: 1'>
        <b style='position: absolute'>{{ $store.state.user.first_name }} {{ $store.state.user.last_name }}</b>
        <v-text-field ref='comment' v-model="text" hide-details placeholder='введите комментарий...'
          @blur='endCommenting'
          @keydown.esc='endCommenting'
          @keydown.enter='saveComment'
          :loading='adding'
        ></v-text-field>
      </div>
    </div>
  </div>
</template>

<script>

  import Avatar from '@/components/UI/Avatar'

  const API_URL = 'comments';

  export default {
    props: {
      className: {
        type: String,
        required: true,
      },
      entityId: {
        type: Number,
        required: true,
      },
      items: {
        type: Array,
        required: false,
        default: null,
      },
    },

    data() {
      return {
        adding: false,
        commenting: false,
        text: '',
        editing_saving: false,
        editing_comment_text: '',
        editing_comment_index: null,
        comments: []
      }
    },
    created() {
      if (this.items === null) {
        this.loadData()
      } else {
        this.comments = this.items
      }
    },
    components: { Avatar },
    methods: {
      startCommenting() {
        this.commenting = true
        Vue.nextTick(() => {
          this.$refs.comment.focus()
        })
      },

      endCommenting() {
        this.commenting = false
        this.text = ''
      },

      saveComment() {
        this.adding = true
        axios.post(apiUrl(API_URL), {
          text: this.text,
          class: this.className,
          entity_id: this.entityId
        }).then(r => {
          this.comments.push(r.data)
          this.endCommenting()
          this.adding = false
        })
      },

      edit(index) {
        this.editing_comment_index = index
        this.editing_comment_text = this.comments[index].text
        Vue.nextTick(() => {
          this.$refs.comments[index].focus()
        })
      },

      destroy(index) {
        axios.delete(apiUrl(API_URL, this.comments[index].id))
        this.comments.splice(index, 1)
      },

      saveEdited() {
        this.editing_saving = true
        axios.put(apiUrl(API_URL, this.comments[this.editing_comment_index].id), {text: this.editing_comment_text}).then(r => {
          this.comments[this.editing_comment_index] = r.data
          this.editing_comment_index = null
          this.editing_saving = false
        })
      },

      loadData() {
        axios.get(apiUrl(API_URL), {
          params: {
            class: this.className,
            entity_id: this.entityId
          }
        }).then(r => {
          this.comments = r.data
        })
      }
    }
  }
</script>
