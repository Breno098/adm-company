<template>
  <v-app :style="{ background: '#FAFAFA' }">
      <v-navigation-drawer v-model="drawer" fixed app  :mini-variant="miniVariant" color="side-bar">
        <v-list dense two-line>
          <v-list-item link class="py-4" @click="_homeRoute">
              <v-list-item-content>
                <v-list-item-title class="white--text font-weight-bold text-subtitle-2"> {{ !miniVariant ? appName : variantAppName }} </v-list-item-title>
                <v-list-item-subtitle v-show="!miniVariant" class="white--text"> {{ hour }} </v-list-item-subtitle>
              </v-list-item-content>
          </v-list-item>
        </v-list>

         <v-list dense shaped>
          <v-list-item-group>
            <div v-for="(item, index) in menuItems" :key="index">
              <router-link
                :to="{ name: item.route, params: { ...item.params } }"
                style="text-decoration: none"
                v-if="!item.list"
              >
                <v-list-item color="white">
                  <v-list-item-icon>
                    <v-icon v-text="item.icon" class="white--text mr-4" small></v-icon>
                  </v-list-item-icon>

                  <v-list-item-title class="white--text font-weight-bold">
                      {{ item.title }}
                  </v-list-item-title>
                </v-list-item>
              </router-link>

              <v-list-group v-else>
                <template v-slot:prependIcon>
                  <v-icon v-text="item.icon" class="white--text mr-4" small></v-icon>
                </template>

                <template v-slot:activator>
                  <v-list-item-title class="white--text font-weight-bold">{{item.title}}</v-list-item-title>
                </template>

                 <template v-slot:appendIcon>
                  <v-icon class="white--text" small>mdi-chevron-down</v-icon>
                </template>

                <router-link
                  v-for="(listItem, i) in item.list"
                  :key="i"
                  :to="{ name: listItem.route, params: { ...listItem.params } }"
                  style="text-decoration: none"
                >
                  <v-list-item class="pl-6" color="white">
                    <v-list-item-icon>
                      <v-icon size="20" class="white--text">{{ listItem.icon }}</v-icon>
                    </v-list-item-icon>

                    <v-list-item-title class="caption white--text font-weight-bold">
                        {{ listItem.title }}
                    </v-list-item-title>
                  </v-list-item>
                </router-link>
              </v-list-group>
            </div>
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
          <v-btn color="primary" @click="_configs" small fab class="mr-2 mt-2 float-right"  v-show="userMenu">
            <v-icon>mdi-cog</v-icon>
          </v-btn>
        </v-fab-transition>

        <v-fab-transition>
          <v-btn color="primary" @click.prevent="logout" small fab class="mr-2 mt-2 float-right" v-show="userMenu">
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
    // if(this.user && this.user.first_access){
    //   this.$router.push({ name: 'settings-first-access' })
    // }
  },
  mounted() {
    this.setHour();
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
          can: true
        },
        {
          title: 'Agenda',
          route: 'appointment.index',
          icon: 'mdi-calendar-today',
          can: this.$can('appointment_index')
        },
        {
          title: 'Pedidos' ,
          route: 'order.index',
          icon: 'mdi-format-list-checks',
          can: this.$can('order_index')
        },
        {
          title: 'Cadastros',
          icon: 'mdi-pencil',
          can: true,
          list: [
            {
            title: 'Custos',
            route: 'expense.index',
            icon: 'mdi-database-minus',
            can: this.$can('expense_index')
            },
            {
              title: 'Clientes',
              route: 'client.index',
              icon: 'mdi-account',
              can: this.$can('client_index')
            },
            {
              title: 'Produtos',
              route: 'product.index',
              icon: 'mdi-barcode',
              can: this.$can('product_index')
            },
            {
              title: 'Serviços',
              route: 'service.index',
              icon: 'mdi-wrench',
              can: this.$can('service_index')
            },
            {
              title: 'Usuários',
              route: 'user.index',
              icon: 'mdi-account',
              can: this.$can('user_index'),
            },
            {
              title: 'Funcionáros',
              route: 'employee.index',
              icon: 'mdi-account-outline',
              can: this.$can('employee_index'),
            },
          ]
        },
        {
          title: 'Relatórios',
          route: 'report.index',
          icon: 'mdi-chart-bar',
          can: true,
          list: [
            {
              title: 'Relatório Financeiro',
              route: 'report.finance.index',
              icon: 'mdi-cash-usd',
              can: true
            }
          ]
        },
        {
          title: 'Arquivos',
          icon: 'mdi-file',
          can: true,
          list: [{
            title: 'Recibo de funcionários',
            route: 'file.employee-receipt.index',
            icon: 'mdi-file-account',
            can: true
          }]
        }
      ];

      let items = menu.filter(item => item.can);

      items.map(can => {
        if(can.list){
          can.list = can.list.filter(item => item.can);
        }
      });

      return items;
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
        return { color: 'primary', icon: 'mdi-chevron-right' }
      } else {
         return { color: 'primary', icon: 'mdi-page-layout-sidebar-left' }
      }
    },
    fabDrawer () {
      if (this.drawer) {
        return { color: 'primary', icon: 'mdi-chevron-left' }
      } else {
         return { color: 'primary', icon: 'mdi-menu' }
      }
    },
    fabUser () {
      if (this.userMenu) {
        return { color: 'primary', icon: 'mdi-close' }
      } else {
         return { color: 'primary', icon: 'mdi-account' }
      }
    },
    ... mapGetters({
      user: 'auth/user'
    }),
  },
}
</script>

