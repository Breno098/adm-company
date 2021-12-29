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
          :active="table.loading"
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
      <v-col cols="12" v-if="table.loading">
        <v-row>
          <v-col cols="12" md="6" v-for="index in [0, 1, 2, 3]" :key="index">
            <v-skeleton-loader type="image"></v-skeleton-loader>
          </v-col>
        </v-row>
      </v-col>

      <v-col cols="12" md="6" v-for="order in table.items" :key="order.id" v-else>
        <v-card v-on:click="_edit(order.id)">
          <v-list-item three-line>
            <v-list-item-content v-if="order.client">
              <div class="text-overline mb-4 d-flex justify-space-between">
                {{ order.client.name }}
                <v-chip v-if="order.status" :color="order.status.color" class="py-2" label>{{ order.status.name }}</v-chip>
              </div>
              <v-list-item-subtitle v-if="order.address">
                {{ order.address.street }} {{ order.address.number ? `n° ${order.address.number}` : '' }}, {{ order.address.district }} - {{ order.address.city }}
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

          <v-card-actions>
            <v-spacer/>
            <v-btn text color="primary" v-on:click="_edit(order.id)">
              Ver informações
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>

      <v-col cols="12">
        <div class="text-center pt-2 mt-4">
          <v-pagination
            v-show="table.pageCount > 1"
            v-model="table.page"
            :length="table.pageCount"
            @input="_load"
            :total-visible="15"
            color="primary"
          ></v-pagination>
        </div>
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
    table: {
      filters: {
        status_id: null
      },
      orderBy: 'created_at:desc',
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
      items: [],
      loading: false,
    },
    statuses: []
  }),
  computed: {
    statusIdByRoute(){
      return this.$route.params.statusId ? this.$route.params.statusId : '';
    }
  },
  mounted() {
    this._start();
  },
  methods: {
    async _start(){
      this.table.filters.status_id = this.statusIdByRoute;

      this._load();
    },
    async _load(){
      let params = {
        page: this.table.page,
        itemsPerPage: this.table.itemsPerPage,
        orderBy: this.table.orderBy,
        relations: ['client','address'],
        ...this.table.filters
      }

      this.table.loading = true;
      await axios.get(`/api/order`, { params }).then(response => {
        console.log(response.data);
        if(response.data.data.data.length === 0 && this.table.page != 1){
          this.table.page = 1;
          this._load()
        }

        this.table.loading = false;

        if(response.data.success){
          this.table.items = response.data.data.data;
          this.table.pageCount = response.data.data.last_page;
        } else {
          this.$refs.fireDialog.error({ title: 'Erro ao carregar pedidos'});
        }
      });
    },
    _edit(id){
      this.$router.push({ name: 'order.show', params: { id } })
    },
     _add(){
      this.$router.push({ name: 'order.create' })
    }
  }
}
</script>
