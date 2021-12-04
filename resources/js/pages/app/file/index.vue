<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-card class="mb-4">
      <v-toolbar elevation="0">
        <v-toolbar-title> Arquivos </v-toolbar-title>
      </v-toolbar>
    </v-card>

    <v-row>
      <v-col cols="12" md="6" v-for="file in filesTypes" :key="file.route">
        <router-link :to="{ name: file.route }" style="text-decoration: none">
          <v-hover v-slot="{ hover }">
              <v-card :class="{ 'on-hover': hover }" :elevation="hover ? 12 : 2">
                <v-card-text class="d-flex flex-row justify-space-between align-center py-10 px-8">
                  <h2> {{ file.title }} </h2>
                  <v-icon size="50">{{ file.icon }}</v-icon>
                </v-card-text>
              </v-card>
          </v-hover>
        </router-link>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: 'Ordens de Serviços' }
  },
  data: () => ({
    filesTypes: [{
      title: 'Recibo de funcionários',
      route: 'employee-receipt.index',
      icon: 'mdi-file-account'
    }]
  }),
  mounted() {
    this._start();
  },
  methods: {
    async _start(){
    },
  }
}
</script>
