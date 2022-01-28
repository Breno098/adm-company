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
                v-model="table.filters.status"
                :items="statuses"
                label="STATUS"
                outlined
                dense
                :loading="table.loading"
                multiple
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
          <v-col cols="12" v-for="index in [0, 1, 2, 3]" :key="index">
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
                    :color="order.status | statusColor"
                  >
                    {{ order.status }}
                    <v-icon color="black" class="ml-2">{{ order.status | statusIcon }}</v-icon>
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
        client_name: '',
        address: '',
        status: []
      },
      orderBy: 'created_at:desc',
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
      items: [],
      loading: false,
    },
    statuses: [
      'CANCELADO',
      'AGUARDANDO APROVAÇÃO',
      'EM ANDAMENTO',
      'CONCLUÍDO'
    ]
  }),
  filters: {
    statusColor(value){
      switch (value) {
        case 'CANCELADO': return 'orange accent-3';
        case 'AGUARDANDO APROVAÇÃO': return 'yellow accent-2';
        case 'EM ANDAMENTO': return 'indigo';
        case 'CONCLUÍDO': return 'green';
        default: return '';
      }
    },
    statusIcon(value){
      switch (value) {
        case 'CANCELADO': return 'mdi-close';
        case 'AGUARDANDO APROVAÇÃO': return 'mdi-thumb-up';
        case 'EM ANDAMENTO': return 'mdi-dots-horizontal';
        case 'CONCLUÍDO': return 'mdi-check';
        default: return '';
      }
    }
  },
  mounted() {
    this._start();
  },
  methods: {
    async _start(){
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
    }
  }
}
</script>
