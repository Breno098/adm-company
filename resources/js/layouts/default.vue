<template>
  <v-app>
      <v-navigation-drawer v-model="drawer" fixed app class="elevation-0" :mini-variant="miniVariant" color="grey lighten-1">
        <v-list dense two-line>
          <v-list-item link class="py-4" @click="_homeRoute">
              <v-list-item-content>
                <v-list-item-title class="black--text font-weight-bold text-subtitle-2"> {{ !miniVariant ? appName : variantAppName }} </v-list-item-title>
                <v-list-item-subtitle> {{ hour }} </v-list-item-subtitle>
              </v-list-item-content>
          </v-list-item>
        </v-list>

        <v-list dense>
          <v-list-item-group v-model="selectedItem" >
            <router-link :to="{ name: item.route }" style="text-decoration: none" v-for="(item, index) in menuItems" :key="index">
              <v-list-item :color="selectedItem == index ? item.color : ''">
                <v-list-item-icon v-if="miniVariant">
                  <v-icon v-text="item.icon"></v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title> 
                    <v-chip class="py-2 px-5" label :color="selectedItem == index ? item.color : 'grey lighten-1'">
                      <v-icon 
                        v-text="item.icon" 
                        v-show="!miniVariant" 
                        :class="{ 
                          'white--text' : selectedItem == index, 
                          'mr-4': true 
                        }">
                      </v-icon> 
                      <span 
                        :class="{ 
                          'white--text' : selectedItem == index , 
                          'font-weight-bold': selectedItem == index,
                          'font-weight-medium': !(selectedItem == index),
                        }"> 
                        {{ item.title }}
                      </span> 
                    </v-chip>
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </router-link>
          </v-list-item-group>
        </v-list>
      </v-navigation-drawer>

      <v-main>
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
         
        <child class="my-8 mx-5"/>
      </v-main>
  </v-app>
</template>

<script>
import { mapGetters } from 'vuex';
import moment from 'moment';

export default {
  name: 'MainLayout',
  data: () => ({
    appName: window.config.appName,
    hour: moment().format('DD/MM/YYYY HH:mm:ss'),
    drawer: true,
    userMenu: false,
    selectedItem: 0,
    miniVariant: false,
  }),
  created () {
    if(this.user && this.user.first_access){
      this.$router.push({ name: 'settings-first-access' })
    }
  },
  mounted() {
    this.setHour();

    if(this.user && !this.user.first_access){
      // this.$router.push({ name: 'settings-first-access' })
    }
  },
  methods: {
    setHour(){
      setInterval(() => this.hour = moment().add(1, 'seconds').format('DD/MM/YYYY HH:mm:ss'), 1000)
    },
    async logout () {
      await this.$store.dispatch('auth/logout')
      this.$router.push({ name: 'login' })
    },
    _homeRoute(){
      this.selectedItem = 0;
      this.$router.push({ name: 'home' });
    },
    _configs(){
      this.selectedItem = null;
      this.userMenu = false;
      this.$router.push({ name: 'settings-user-profile' });
    }
  },
  computed: { 
    menuItems(){
      let menu = [
        { 
          title: 'Inicio', 
          route: 'home', 
          icon: 'mdi-home', 
          color: 'blue',
          role: true
        },
        { 
          title: 'Pedidos' , 
          route: 'order.index', 
          icon: 'mdi-format-list-checks', 
          color: 'purple',
          role: this.$role.order.index()
        },
        { 
          title: 'Agenda', 
          route: 'appointment.index', 
          icon: 'mdi-calendar-today', 
          color: 'teal accent-4',
          role: this.$role.appointment.index()
        },
        { 
          title: 'Custos/Despesas', 
          route: 'expense.index', 
          icon: 'mdi-database-minus', 
          color: 'red',
          role: this.$role.expense.index()
        },
        { 
          title: 'Clientes', 
          route: 'client.index', 
          icon: 'mdi-account', 
          color: 'green',
          role: this.$role.client.index()
        },
        { 
          title: 'Produtos', 
          route: 'product.index', 
          icon: 'mdi-barcode', 
          color: 'orange',
          role: this.$role.product.index()
        },
        { 
          title: 'Serviços', 
          route: 'service.index', 
          icon: 'mdi-wrench', 
          color: 'indigo',
          role: this.$role.service.index()
        },
        { 
          title: 'Categorias', 
          route: 'category.index', 
          icon: 'mdi-format-list-bulleted-type', 
          color: 'cyan accent-4',
          role: false, //this.$role.category.index()
        },
        { 
          title: 'Usuários', 
          route: 'user.index', 
          icon: 'mdi-account', 
          color: 'orange',
          role: this.$role.user.index(),
        }
      ];

      return menu.filter(item => item.role)
    },
    variantAppName(){
      let splitName = this.appName.split(' ');
      if(splitName.length > 1){
        return splitName[0].substr(0, 1) + splitName[1].substr(0, 1);
      }
      return splitName[0].substr(0, 2);
    },
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
