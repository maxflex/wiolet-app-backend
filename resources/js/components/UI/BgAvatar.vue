<template>
  <v-avatar :size='size' class='bg-avatar' :style="{backgroundImage: `url(${ (photo && photo.has_cropped) ? (version ? photo.url_version : photo.url) : '/img/no-profile-img.jpg' })`}"></v-avatar>
</template>

<script>
export default {
  props: ['size', 'photo', 'version']
}
</script>
