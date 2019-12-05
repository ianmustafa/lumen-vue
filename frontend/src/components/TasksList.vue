<template>
  <v-card
    max-width="475"
    class="mx-auto"
    elevation="4"
    :loading="loadingSave"
  >
    <v-toolbar
      color="teal"
      dark
    >
      <v-toolbar-title>Daftar Tugas</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn text v-if="formPayload.name.length" @click="saveTask">
        Simpan tugas
      </v-btn>
    </v-toolbar>

    <v-list two-line subheader>
      <v-subheader>Tambahkan tugas baru</v-subheader>

      <v-text-field
        v-model.trim="formPayload.name"
        label="Nama tugas"
        single-line
        solo
        flat
        full-width
        hide-details
        class="px-1"
      ></v-text-field>

      <v-divider />

      <v-text-field
        v-model.trim="formPayload.description"
        label="Deskripsi"
        single-line
        solo
        flat
        full-width
        hide-details
        class="px-1"
      ></v-text-field>

      <v-divider />
    </v-list>

    <v-list
      subheader
      flat
    >
      <v-subheader>Semua tugas</v-subheader>

      <template v-if="loading">
        <v-list-item v-for="i in 3" :key="`loading-${i}`">
          <v-list-item-action>
            <span />
          </v-list-item-action>

          <v-list-item-content>
            <v-list-item-title>
              <v-skeleton-loader type="text" width="25%" />
            </v-list-item-title>
            <v-list-item-subtitle>
              <v-skeleton-loader type="text" width="60%" />
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </template>

      <v-list-item v-else-if="fetchError">
        <v-list-item-content>
          <v-list-item-subtitle class="text-center">
            Terjadi kesalahan. ⚠️
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <template v-else>
        <v-list-item v-if="tasks.length === 0">
          <v-list-item-content>
            <v-list-item-subtitle class="text-center">
              Yay, belum ada tugas! 🎉
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>

        <template v-else>
          <v-list-item
            v-for="task in tasks"
            :key="`task-${task.id}`"
            :three-line="task.description.length"
            class="pr-2"
          >
            <v-list-item-action>
              <span v-if="loadingSingle === task.id" />
              <template v-else>
                <v-checkbox
                  v-model="task.is_done"
                  color="grey lighten-2"
                  @click="toggleTask(task)"
                />
              </template>
            </v-list-item-action>

            <v-list-item-content>
              <v-list-item-title :class="{
                'grey--text': task.is_done,
                'text-lighten-2': task.is_done,
                'text-strikethrough': task.is_done,
              }">
                <v-skeleton-loader
                  v-if="loadingSingle === task.id"
                  type="text"
                  width="25%"
                />
                <template v-else>{{ task.name }}</template>
              </v-list-item-title>
              <v-list-item-subtitle :class="{
                'grey--text': task.is_done,
                'text-lighten-3': task.is_done,
                'text-strikethrough': task.is_done,
              }">
                <v-skeleton-loader
                  v-if="loadingSingle === task.id && task.description.length"
                  type="text"
                  width="60%"
                />
                <template v-else>{{ task.description }}</template>
              </v-list-item-subtitle>
            </v-list-item-content>

            <v-list-item-action v-if="loadingSingle !== task.id" class="mt-2">
              <v-btn icon
                :color="`red ${task.is_done ? 'lighten-4' : ''}`"
                @click="deleteTask(task)"
              >
                <v-icon>mdi-delete</v-icon>
              </v-btn>
            </v-list-item-action>
          </v-list-item>
        </template>
      </template>
    </v-list>
  </v-card>
</template>

<script>
export default {
  name: 'TasksList',

  data: () => ({
    loading: true,
    loadingSave: false,
    loadingSingle: null,
    fetchError: false,
    tasks: [],
    formPayload: {
      name: '',
      description: '',
    },
  }),

  methods: {
    async fetchTasks() {
      this.loading = true;

      try {
        const { data: result } = await this.axios.get('api/tasks');
        this.tasks = result.data;
      } catch (error) {
        this.tasks = [];
        this.fetchError = true;
      }

      this.loading = false;
    },
    async saveTask() {
      if (this.loadingSave) {
        return false;
      }

      this.loadingSave = true;

      try {
        const { data: result } = await this.axios.post(`api/tasks`, this.formPayload);
        this.tasks.push(result);
        this.formPayload = {
          name: '',
          description: '',
        };
      } catch (error) {
        //
      }

      this.loadingSave = false;
    },
    async toggleTask({ id, is_done }) {
      if (this.loadingSingle === id) {
        return false;
      }

      this.loadingSingle = id;
      const taskIndex = this.tasks.findIndex(o => o.id === id);

      try {
        await this.axios.patch(`api/tasks/${id}`, { is_done: !is_done });
        this.tasks[taskIndex].is_done = !is_done;
      } catch (error) {
        //
      }

      this.loadingSingle = null;
    },
    async deleteTask({ id }) {
      if (this.loadingSingle === id) {
        return false;
      }

      const sureDelete = confirm('Yakin ingin menghapus tugas ini?');
      if (!sureDelete) {
        return false;
      }

      this.loadingSingle = id;
      const taskIndex = this.tasks.findIndex(o => o.id === id);

      try {
        await this.axios.delete(`api/tasks/${id}`);
        this.tasks.splice(taskIndex, 1);
      } catch (error) {
        //
      }

      this.loadingSingle = null;
    },
  },
  created() {
    this.fetchTasks();
  },
};
</script>

<style lang="scss" scoped>
.text-strikethrough {
  text-decoration: line-through;
}
</style>
