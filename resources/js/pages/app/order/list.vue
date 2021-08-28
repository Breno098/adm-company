<template>
  <div>
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

    <v-row>
      <v-col cols="12">
        <v-btn color="blue" @click="_add" rounded>
            Adicionar <v-icon dark>mdi-plus</v-icon>
        </v-btn>
      </v-col>

      <v-col cols="12" v-if="loading">
        <v-row>
          <v-col cols="12" md="6" v-for="index in [0, 1, 2, 3]" :key="index">
            <v-skeleton-loader type="image"></v-skeleton-loader>
          </v-col>
        </v-row>
      </v-col>
      
      <v-col cols="12" md="6" v-for="order in items" :key="order.id" v-else>
        <v-card v-on:click="_edit(order.id)">
          <v-list-item three-line>
            <v-list-item-content>
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
            <v-btn text color="blue" v-on:click="_edit(order.id)">
              Ver informações
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>

      <v-col cols="12">
        <div class="text-center pt-2 mt-4">
          <v-pagination
            v-model="page"
            :length="pageCount"
            @input="_load"
            :total-visible="15"
            circle
          ></v-pagination>
        </div>
      </v-col>
  </v-row>

  <v-dialog v-model="dialog" max-width="350">
    <v-card>
        <v-card-title>
            Confirmar exclusão do cliente?
        </v-card-title>
        <v-card-text>
            {{ deleted.name }}
        </v-card-text>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green" text @click="dialog = false">
                Fechar
            </v-btn>
            <v-btn color="red" text @click="_delete">
                Excluir
            </v-btn>
        </v-card-actions>
    </v-card>
  </v-dialog>
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
    dialog: false,
    filters: {},
    deleted: {},
    search: '',
    page: 1,
    pageCount: 0,
    items: [],
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
      await axios.get(`api/status?type=order`).then(response => {
        if(response.data.success){
          return this.statuses = response.data.data;
        }
      });
      this.loading = false;
    },
    _loadFilterByStatus(status_id){
      this.filters.status_id = status_id;
      this._load();
    },
    _loadAll(){
      this.filters = {};
      this._load();
    },
    async _load(){
      let params = { 
        page: this.page, 
        itemsPerPage: 20,
        ...this.filters 
      }

      this.loading = true;
      await axios.get(`api/order`, { params }).then(response => {
        if(response.data.data.data.length === 0 && this.page != 1){
          this.page = 1;
          this._load()
        }

        if(response.data.success){
            this.items = response.data.data.data;
            this.pageCount = response.data.data.last_page;
            this.loading = false;
        }
      });
    },
    async _delete(){
      this.loading = true;
      await axios.delete(`api/order/${this.deleted.id}`).then(response => {
        if(response.data.success){
          this.dialog = false;
          this._load();
        }
      });
    },
    filterOnlyCapsText (value, search, item) {
        return value != null && search != null && typeof value === 'string' && value.toString().indexOf(search) !== -1
    },
    confirmDelete(deleted){
        this.deleted = deleted;
        this.dialog = true;
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
