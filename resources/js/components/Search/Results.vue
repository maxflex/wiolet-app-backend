<template>
  <v-container grid-list-xl class="pa-0 ma-0" fluid v-if='$store.state.search.results !== null'>
    <div v-if='typesWithData.length > 0'>
      <v-layout wrap row v-for='type in typesWithData' :key='type.field' class='mb-4'>
        <v-flex md12 class='headline'>
          {{ type.title }}
          <!-- <span class='grey--text'>({{ result[type.field].length }})</span> -->
        </v-flex>
        <v-flex md12 >
          <component :is='type.component' :items='$store.state.search.results[type.field]' />
        </v-flex>
      </v-layout>
    </div>
    <div v-else class='headline'>
      По вашему запросу ничего не найдено
    </div>
  </v-container>
</template>

<script>


export default {


  data() {
    return {
      types: [
        {
          title: 'Клиенты',
          component: 'ClientList',
          field: 'clients',
        },
        {
          title: 'Учителя',
          component: 'TeacherList',
          field: 'tutors',
        },
      ],
    }
  },

  computed: {
    typesWithData() {
      return this.types.filter(type => this.$store.state.search.results[type.field].length > 0)
    },
  }
}
</script>
