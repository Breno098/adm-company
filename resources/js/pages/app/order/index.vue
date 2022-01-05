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

    <v-expansion-panels class="mb-4">
      <v-expansion-panel>
        <v-expansion-panel-header>
          <span>
            <v-icon :size="15">mdi-magnify</v-icon>
            Filtros
          </span>
        </v-expansion-panel-header>
        <v-expansion-panel-content>
          <v-row>
             <v-col cols="12">
              <v-select
                v-model="table.filters.statuses_ids"
                vali
                :items="statuses"
                item-text="name"
                item-value="id"
                label="STATUS"
                outlined
                dense
                :loading="table.loading"
                multiple
                no-data-text="Nenhum status encontrado"
              ></v-select>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="CLIENTE"
                outlined
                dense
                v-model="table.filters.client_name"
                :loading="table.loading"
                @input="table.filters.client_name = table.filters.client_name.toUpperCase()"
                v-on:keyup.enter="_load"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="ENDEREÇO"
                outlined
                dense
                v-model="table.filters.address"
                :loading="table.loading"
                @input="table.filters.address = table.filters.address.toUpperCase()"
                v-on:keyup.enter="_load"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-card-actions class="pb-4">
            <v-spacer></v-spacer>
            <v-btn
              color="btnPrimary"
              @click="_load"
              class="px-5"
              rounded
            >
              Buscar <v-icon dark class="ml-2">mdi-magnify</v-icon>
            </v-btn>
            <v-btn
              color="btnCleanFilter"
              @click="_eraser"
              class="px-5"
              rounded
            >
              Limpar <v-icon dark class="ml-2">mdi-eraser</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>

    <v-row>
      <v-col cols="12" v-if="table.loading">
        <v-row>
          <v-col cols="12" md="6" v-for="index in [0, 1, 2, 3]" :key="index">
            <v-skeleton-loader type="image"></v-skeleton-loader>
          </v-col>
        </v-row>
      </v-col>

      <v-col cols="12" v-for="order in table.items" :key="order.id" v-else>
        <v-hover v-slot="{ hover }">
          <v-card
            v-on:click="_edit(order.id)"
            :class="{ 'on-hover': hover }"
            :elevation="hover ? 12 : 2"
          >
            <v-list-item three-line>
              <v-list-item-content v-if="order.client">
                <div class="text-overline mb-4 d-flex justify-space-between">
                  {{ order.client.name }}
                  <v-chip
                    v-if="order.status"
                    class="py-2"
                    label
                    :color="order.status.color"
                    outlined
                  >
                    {{ order.status.name }}
                    <v-icon :color="order.status.color" class="ml-2">{{ order.status.icon }}</v-icon>
                  </v-chip>
                </div>
                <v-list-item-subtitle v-if="order.address">
                  {{ order.address.street }} {{ order.address.number ? `n° ${order.address.number}` : '' }}, {{ order.address.district }} - {{ order.address.city }}
                </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>

            <v-card-actions>
              <v-spacer/>
              <v-btn text color="primary" v-on:click="_edit(order.id)" small>
                Ver informações
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-hover>
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
        status_id: null,
        client_name: '',
        address: '',
        statuses_ids: []
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
      this._load();

      this._loadStatuses();
    },
    async _load(){
      let params = {
        page: this.table.page,
        itemsPerPage: this.table.itemsPerPage,
        orderBy: this.table.orderBy,
        relations: ['client','address', 'status'],
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
    },
    _eraser(){
      for (const field in this.table.filters) {
        this.table.filters[field] = null;
      }

      this._load();
    },
    async _loadStatuses(){
      this.table.loading = true;

      let params = {
        type: 'order',
        orderBy: 'order',
      }

      await axios.get(`/api/status`, { params }).then(response => {
        if(response.data.success){
          return this.statuses = response.data.data;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar pedidos' })
      });

      this.table.loading = false;
    },
  }
}
</script>
