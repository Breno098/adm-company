<template>
  <v-app-bar app color="blue" height="75" >
      <v-app-bar-nav-icon
        v-bind:drawer="drawer"
        v-on:click="$emit('click', $event.target.drawer)"

      ></v-app-bar-nav-icon>
      <v-spacer></v-spacer>
      <v-menu transition="slide-y-transition">
          <template v-slot:activator="{ on, attrs }">
              <v-btn text v-bind="attrs" v-on="on">
                  {{ user.name }}<v-icon class="ml-3">mdi-dots-vertical</v-icon>
              </v-btn>
          </template>

          <v-list nav dense>
              <v-list-item>
                  <v-list-item-icon>
                      <v-icon outlined color="green">mdi-cog</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                      <v-list-item-title> Configurações </v-list-item-title>
                  </v-list-item-content>
              </v-list-item>
              <v-list-item @click.prevent="logout">
                  <v-list-item-icon>
                      <v-icon outlined color="red">mdi-logout</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                      <v-list-item-title> Sair </v-list-item-title>
                  </v-list-item-content>
              </v-list-item>
          </v-list>
      </v-menu>
  </v-app-bar>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  props: {
    drawer: true,
  },
  data: () => ({
    appName: window.config.appName,
  }),
  computed: mapGetters({
    user: 'auth/user'
  }),
}
</script>
