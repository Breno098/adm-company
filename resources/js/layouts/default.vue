<template>
  <v-app>
      <v-navigation-drawer v-model="drawer" fixed app class="elevation-0" :mini-variant="miniVariant" color="grey darken-3">
        <v-list dense>
          <v-list-item link class="py-4">
            <router-link :to="{ name: 'home' }" style="text-decoration: none">
              <v-list-item-content class="text-center white--text">
                  {{ !miniVariant ? appName : '' }}
                  <v-icon v-show="miniVariant" color="white">mdi-home</v-icon>
              </v-list-item-content>
            </router-link>
          </v-list-item>

          <v-list-item-group v-model="selectedItem" dark>
            <router-link :to="{ name: item.route }" style="text-decoration: none" v-for="(item, index) in menuItems" :key="index">
              <v-list-item :color="selectedItem == index ? item.color : ''">
                <v-list-item-icon>
                  <v-icon v-text="item.icon"></v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title >
                    <v-chip :color="selectedItem == index ? item.color : ''">
                      {{ item.title }}
                    </v-chip>
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </router-link>
          </v-list-item-group>
        </v-list>
      </v-navigation-drawer>

      <v-content class="grey lighten-4">
        <v-fab-transition>
          <v-btn :key="fabDrawer.icon" :color="fabDrawer.color" @click="drawer = !drawer" small fab class="ml-2 mt-2 float-lef">
            <v-icon>{{ fabDrawer.icon }}</v-icon>
          </v-btn>
        </v-fab-transition>
        <v-fab-transition>
          <v-btn :key="fabMiniVariant.icon" :color="fabMiniVariant.color" @click.stop="miniVariant = !miniVariant" small fab class="ml-2 mt-2 float-lef">
            <v-icon>{{ fabMiniVariant.icon }}</v-icon>
          </v-btn>
        </v-fab-transition>

        <v-fab-transition>
          <v-btn :key="fabUser.icon" :color="fabUser.color" @click="userMenu = !userMenu" small fab class="mr-2 mt-2 float-right">
            <v-icon>{{ fabUser.icon }}</v-icon>
          </v-btn>
        </v-fab-transition>

        <v-fab-transition>
          <v-btn color="orange" @click="_configs" small fab class="mr-2 mt-2 float-right"  v-show="userMenu">
            <v-icon>mdi-cog</v-icon>
          </v-btn>
        </v-fab-transition>

        <v-fab-transition>
          <v-btn color="red" @click.prevent="logout" small fab class="mr-2 mt-2 float-right" v-show="userMenu">
            <v-icon>mdi-logout</v-icon>
          </v-btn>
        </v-fab-transition>
         
        <child class="my-5 mx-5"/>
      </v-content>
  </v-app>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'MainLayout',
  data: () => ({
    appName: window.config.appName,
    drawer: true,
    userMenu: false,
    selectedItem: 1,
    miniVariant: false,
    menuItems: [
      { title: 'Cliente' , route: 'client.index', icon: 'mdi-account-convert', color: 'green' },
      { title: 'Produtos', route: 'item.index', icon: 'mdi-barcode', color: 'orange' },
      { title: 'Serviços', route: 'item.index', icon: 'mdi-wrench', color: 'indigo' },
      { title: 'Categoria', route: 'category.index', icon: 'mdi-format-list-bulleted-type', color: 'blue'},
      { title: 'Ordens' , route: 'order.index', icon: 'mdi-format-list-checks', color: 'purple' },
      { title: 'Agenda' , route: 'appointment.index', icon: 'mdi-calendar-today', color: 'green' },
    ]
  }),
  methods: {
    async logout () {
      await this.$store.dispatch('auth/logout')
      this.$router.push({ name: 'login' })
    },
    _configs(){
      this.selectedItem = null;
      this.userMenu = false;
      this.$router.push({ name: 'settings.profile' });
    }
  },
  computed: { 
    fabMiniVariant () {
      if (this.miniVariant) {
        return { color: 'blue', icon: 'mdi-chevron-right' }
      } else {
         return { color: 'blue', icon: 'mdi-page-layout-sidebar-left' }
      }
    },
    fabDrawer () {
      if (this.drawer) {
        return { color: 'grey', icon: 'mdi-chevron-left' }
      } else {
         return { color: 'grey', icon: 'mdi-menu' }
      }
    },
    fabUser () {
      if (this.userMenu) {
        return { color: 'blue', icon: 'mdi-close' }
      } else {
         return { color: 'green', icon: 'mdi-account' }
      }
    },
    ... mapGetters({
      user: 'auth/user'
    }),
  },
}
</script>

<style scoped>
  .v-application ::-webkit-scrollbar {
    height: 8px;
    width: 13px;
  }

  .v-application ::-webkit-scrollbar-corner {
    background: transparent;
  }

  .v-application ::-webkit-scrollbar-thumb {
    background: #2196F3;
    border-radius: 8px;
  }
</style>
