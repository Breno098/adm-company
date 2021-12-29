<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-card class="mb-4">
      <v-toolbar elevation="0">
        <v-toolbar-title> Pedidos </v-toolbar-title>
        <v-progress-linear
          color="primary"
          indeterminate
          height="4"
          bottom
          absolute
          :active="loading"
        ></v-progress-linear>

        <v-spacer></v-spacer>

        <v-btn
          dark
          color="primary"
          @click="_add"
          small
        >
          Adicionar <v-icon dark>mdi-plus</v-icon>
        </v-btn>
      </v-toolbar>
    </v-card>

    <v-row>
      <v-col cols="12" md="6" v-for="status in statuses" :key="status.name">
        <router-link :to="{ name: 'order.list', params: { statusId: status.id } }" style="text-decoration: none">
          <v-hover v-slot="{ hover }">
              <v-card :class="{ 'on-hover': hover }" :elevation="hover ? 12 : 2">
                <v-card-text class="d-flex flex-row justify-space-between align-center py-10 px-8">
                  <h2> {{ status.name }} </h2>
                  <v-badge
                    :content="status.orders ? status.orders.length : 0"
                    :value="status.orders ? status.orders.length : 0"
                    color="primary"
                    overlap
                  >
                    <v-icon size="50" :color="status.color">{{ status.icon }}</v-icon>
                  </v-badge>
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
    loading: false,
    statuses: []
  }),
  mounted() {
    this._start();
  },
  methods: {
    async _start(){
      await this._loadStatuses();
    },
    async _loadStatuses(){
      this.loading = true;

      let params = {
        type: 'order',
        orderBy: 'order',
        relations: [ 'orders' ]
      }

      await axios.get(`/api/status`, { params }).then(response => {
        if(response.data.success){
          this.statuses = response.data.data;

          let orders_all = [];

          this.statuses.forEach(status => status.orders.length > 0 ? orders_all.push(status.orders) : null );

          this.statuses.push({
            name: 'TODOS',
            icon: 'mdi-format-list-bulleted',
            orders: orders_all
          });

          return;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar pedidos' })
      });

      this.loading = false;
    },
     _add(){
      this.$router.push({ name: 'order.create' })
    }
  }
}
</script>
