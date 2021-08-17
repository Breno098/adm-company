<template>
  <v-row>
    <v-col md="3">
      <v-card>
        <v-toolbar class="d-flex flex-row justify-center" color="blue">
          <v-toolbar-title>Configurações</v-toolbar-title>
        </v-toolbar>

        <v-card-text>
          <v-list flat>
            <v-list-item-group
              v-model="selectedItem"
              color="primary"
            >
              <router-link :to="{ name: tab.route }" v-for="tab in tabs" :key="tab.route" style="text-decoration: none">
                <v-list-item>
                  <v-list-item-icon>
                    <v-icon v-text="tab.icon"></v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title v-text="tab.name"></v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </router-link>
            </v-list-item-group>
          </v-list>
        </v-card-text>
      </v-card>
    </v-col>

    <v-col md="9">
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </v-col>
  </v-row>
</template>

<script>
export default {
  middleware: 'auth',
  data: () => ({
    selectedItem: 0
  }),
  computed: {
    tabs () {
      return [
        {
          icon: 'mdi-account',
          name: 'Perfil',
          route: 'settings.profile'
        },
        {
          icon: 'mdi-lock',
          name: 'Senha',
          route: 'settings.password'
        }
      ]
    }
  }
}
</script>

<style>
.settings-card .card-body {
  padding: 0;
}
</style>
