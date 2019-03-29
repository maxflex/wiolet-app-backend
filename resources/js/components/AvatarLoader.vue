<template>
  <div>
    <div v-if='item.photo !== null'>
      <v-hover>
        <v-avatar slot-scope="{ hover }" :size='180' style='overflow: hidden'>
          <img :src='item.photo.url_version' />
          <div class='photo-needs-cropping' v-if='!item.photo.has_cropped'>
            <img src='/img/svg/scissors.svg'/>
          </div>
          <v-slide-y-reverse-transition>
            <div class='photo-actions' v-show='hover'>
              <div @click="dialog = true">
                <v-icon class='mr-1'>crop_free</v-icon>
                <span>редактировать</span>
              </div>
              <!-- <div @click="destroy">
                <v-icon>close</v-icon>
                <span>удалить</span>
              </div> -->
            </div>
          </v-slide-y-reverse-transition>
        </v-avatar>
      </v-hover>
    </div>

    <div v-else>
      <v-hover>
        <v-avatar slot-scope="{ hover }" :size='180' style='overflow: hidden'>
          <Loader v-if='uploading' />
          <img src='/img/no-profile-img.jpg' />
          <v-slide-y-reverse-transition>
            <div class='photo-actions' v-show='hover'>
              <div @click="selectFileToUpload">
                <v-icon>arrow_upward</v-icon>
                <span>загрузить</span>
              </div>
            </div>
          </v-slide-y-reverse-transition>
        </v-avatar>
      </v-hover>
    </div>

    <div class='text-md-center red--text' v-if='uploading_error'>ошибка загрузки</div>
    <!-- <div v-else class='image-upload' @click="selectFileToUpload">
      загрузить фото
    </div> -->

    <v-layout row justify-center>
      <v-dialog v-model="dialog" persistent max-width="1000px">
        <v-card>
          <v-card-text>
              <vue-cropper v-if='item.photo'
                ref="cropper" style='height: 600px'
                :src="item.photo.url_original"
                :zoomable='false'
                :view-mode='1'
                :min-crop-box-width='100'
                :min-crop-box-height='100'
                :min-container-height='600'
                :min-container-width='968'
                :aspect-ratio='1'
                :responsive='false'
              >
              </vue-cropper>
          </v-card-text>
          <v-card-actions>
            <span class='ml-3 red--text' v-show='uploading_error'>ошибка загрузки</span>
            <v-spacer></v-spacer>
            <v-btn color="grey darken-1" flat @click.native="dialog = false">Отмена</v-btn>
            <v-btn color="blue darken-1" flat @click.native="destroy">Удалить</v-btn>
            <v-btn color="blue darken-1" flat @click.native="selectFileToUpload" :loading='uploading'>Загрузить новое</v-btn>
            <v-btn color="blue darken-1" flat @click.native="cropImage" :loading='cropping'>Сохранить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
  </div>
</template>

<script>

import VueCropper from 'vue-cropperjs'

const API_URL = 'photo'

export default {
  props: {
    entityType: {
      type: String,
      required: true,
    },
    item: {
      type: Object,
      required: true,
    }
  },

  data() {
    return {
      dialog: false,
      cropping: false,
      uploading: false,
      uploading_error: false,
    }
  },

  created() {
    this.$upload.on('photo', {
       url: apiUrl(API_URL, 'upload'),
       maxSizePerFile: 1024 * 1024 * 20,
       body: this.item.id ? {
         entity_type: this.entityType,
         entity_id: this.item.id
       } : {},
       onSuccess(e, response) {
         this.item.photo = null
         Vue.nextTick(() => {
           this.item.photo = response.data
           this.dialog = true
         })
       },
        onError() {
          this.uploading = false
          this.uploading_error = true
        },
        onStart() {
          this.uploading = true
          this.uploading_error = false
        },
        onEnd() {
          this.uploading = false
        }
    })
  },

  methods: {
    selectFileToUpload() {
      this.$upload.select('photo')
    },

    cropImage() {
      this.cropping = true
      this.$refs.cropper.getCroppedCanvas().toBlob((blob) => {
        const formData = new FormData()
        formData.append('file', blob)
        formData.append('photo_id', this.item.photo.id)
        axios.post(apiUrl(API_URL, 'crop'), formData).then(r => {
          this.item.photo = r.data
          this.dialog = false
          setTimeout(() => this.cropping = false, 300)
        })
      })
    },

    destroy() {
      this.dialog = false
      axios.delete(apiUrl(API_URL, this.item.photo.id))
      this.waitForDialogClose(() => {
        this.item.photo = null
      })
    },
  }
}
</script>

<style lang="scss">
.photo-actions {
  position: absolute;
  background: rgba(29,32,34,.7);
  color: white;
  height: 36%;
  width: 100%;
  bottom: 0;
  padding-top: 5px;
  & .v-icon {
    color: white;
    height: 16px;
    font-size: 18px;
    margin-right: 4px;
  }
  & > div {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    opacity: .8;
    cursor: pointer;
    margin-bottom: 4px;
    &:hover {
      opacity: 1;
    }
  }
}

.image-upload {
  border-radius: 50%;
  border: 3px #c5c5c5 dashed;
  width: 180px;
  height: 180px;
  -webkit-box-flex: 0 !important;
  -ms-flex: none !important;
  flex: none !important;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #c5c5c5;
  cursor: pointer;
  transition: all .2s linear;
  &:hover {
    border-color: #0088ec;
    color: #0088ec;
    background: #e5f1fd;
  }
}

.photo-needs-cropping {
  position: absolute;
  background: rgba(black, .7);
  height: 100%;
  width: 100%;
  align-items: center;
  justify-content: center;
  display: flex;
  & img {
    height: 52px;
    width: 90px;
    border-radius: 0;
  }
}
</style>
