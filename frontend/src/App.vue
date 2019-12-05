<template>
  <v-app>
    <v-content>
      <v-container
        class="fill-height"
        fluid
      >
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            sm="8"
            md="4"
          >
            <h1 class="display-3 grey--text text-lighten-2 text-center">Lumen dan Vue.js</h1>
            <h2 class="display-1 grey--text text-lighten-2 text-center mb-5">Dalam Satu Repositori</h2>
            <p class="subtitle-1 grey--text text-center mb-8">Dalam mengerjakan suatu proyek yang membutuhkan Vue.js, seringkali saya menggabungkan
kedua basis kode proyek dalam satu repositori. Proyek ini saya harap bisa menjadi
bahan rujukan dan dapat memberikan gambaran tentang bagaimana saya memulai
proyeknya, termasuk langkah-langkah seperti apa yang saya lakukan.</p>
            <TasksList />
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
import TasksList from './components/TasksList';

export default {
  name: 'LumenVue',
  components: {
    TasksList,
  },
};
</script>
