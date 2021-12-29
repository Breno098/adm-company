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
          rounded
          small
        >
          Adicionar <v-icon dark>mdi-plus</v-icon>
        </v-btn>
      </v-toolbar>

      <v-card-text>
        <v-row>
          <v-col cols="12" md="4" v-for="(status) in statuses" :key="status.id" >
            <v-btn :color="status.color" block @click="_loadFilterByStatus(status.id)">
                {{ status.name }}
            </v-btn>
          </v-col>
          <v-col cols="12" md="4" >
            <v-btn block @click="_loadAll" color="grey">
              Todas
            </v-btn>
          </v-col>
        </v-row>
      </v-card-text>
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
            circle
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
      filters: {},
      orderBy: 'created_at',
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
      items: [],
      loading: false,
    },
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
      this.table.loading = true;
      await axios.get(`/api/status?type=order`).then(response => {
        if(response.data.success){
          return this.statuses = response.data.data;
        }
      });
      this.table.loading = false;
    },
    _loadFilterByStatus(status_id){
      this.table.filters.status_id = status_id;
      this._load();
    },
    _loadAll(){
      this.table.filters = {};
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
    async _delete(){
      this.loading = true;
      await axios.delete(`/api/order/${this.deleted.id}`).then(response => {
        if(response.data.success){
          this.dialog = false;
          this._load();
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
