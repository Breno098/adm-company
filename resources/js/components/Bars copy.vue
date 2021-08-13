<template>
  <div>
    <v-app-bar app color="blue" height="50" flat class="elevation-0">
      <v-app-bar-nav-icon @click="drawer = !drawer" color="white"/>
      <v-spacer/>
      <v-menu transition="slide-y-transition">
          <template v-slot:activator="{ on, attrs }">
              <v-btn text v-bind="attrs" v-on="on">
                  {{ user.name }}<v-icon class="ml-3">mdi-dots-vertical</v-icon>
              </v-btn>
          </template>

          <v-list nav dense>
            <router-link :to="{ name: 'settings.profile' }" style="text-decoration: none">
              <v-list-item>
                  <v-list-item-icon>
                      <v-icon outlined color="green">mdi-cog</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                      <v-list-item-title> Configurações </v-list-item-title>
                  </v-list-item-content>
              </v-list-item>
            </router-link>
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

    <v-navigation-drawer v-model="drawer" fixed app width="200" color="blue" class="elevation-0">

      <v-list dense>
        <v-list-item link class="py-4">
          <router-link :to="{ name: 'home' }" style="text-decoration: none">
            <v-list-item-content class="text-h5 text-center white--text">
                {{ appName }}
            </v-list-item-content>
          </router-link>
        </v-list-item>

        <v-list-group
            v-for="item in items"
            :key="item.title"
            :prepend-icon="item.icon"
            no-action
            color="white"
        >
            <template v-slot:activator>
            <v-list-item-content>
                <v-list-item-title v-text="item.title"></v-list-item-title>
            </v-list-item-content>
            </template>

            <v-list-item v-for="child in item.items" :key="child.title">
                <router-link :to="{ name: child.route, params: child.params }" style="text-decoration: none">
                    <v-list-item-content class="white--text">
                        <v-list-item-title v-text="child.title"></v-list-item-title>
                    </v-list-item-content>
                </router-link>
            </v-list-item>
        </v-list-group>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data: () => ({
    appName: window.config.appName,
    drawer: true,
    items: [
      {
        title: 'Cadastros',
        icon: 'mdi-pencil',
        items: [
          { title: 'Cliente' , route: 'client.index' },
          { title: 'Produtos/Serviços', route: 'item.index' },
          { title: 'Categoria', route: 'category.index' },
        ],
      },
      {
        title: 'Ordens',
        icon: 'mdi-format-list-checks',
        items: [
          { title: 'Ordens' , route: 'order.index' },
        ],
      },
      {
        title: 'Compromissos',
        icon: 'mdi-calendar-today',
        items: [
          { title: 'Agenda' , route: 'appointment.index' },
        ],
      },
    ],
  }),
  computed: mapGetters({
    user: 'auth/user'
  }),
  methods: {
    async logout () {
      await this.$store.dispatch('auth/logout')
      this.$router.push({ name: 'login' })
    }
  }
}
</script>
