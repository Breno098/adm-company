<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <p class="font-weight-bold mb-5 text-h5">
      <v-icon color="primary">mdi-format-list-checks</v-icon>
      Pedidos
    </p>

    <v-row class="mb-2">
      <v-col cols="6" md="10">
        <v-menu
          :close-on-content-click="false"
          :nudge-width="200"
          offset-y
          v-model="menuFilter"
        >
          <template v-slot:activator="{ on, attrs }">
              <v-btn text small v-bind="attrs" v-on="on">
                  Filtros
                  <v-icon color="primary">mdi-chevron-down</v-icon>
              </v-btn>
          </template>

          <v-card width="800">
              <v-card-text>
                <v-row>
                  <v-col cols="12">
                    <v-select
                      v-model="table.filters.status"
                      :items="statuses"
                      label="STATUS"
                      filled
                      dense
                      :loading="table.loading"
                      multiple
                    ></v-select>
                  </v-col>

                  <v-col cols="12" md="6">
                    <v-text-field
                      label="CLIENTE"
                      filled
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
                      filled
                      dense
                      v-model="table.filters.address"
                      :loading="table.loading"
                      @input="table.filters.address = table.filters.address.toUpperCase()"
                      v-on:keyup.enter="_load"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn
                  color="deep-purple lighten-5"
                  small
                  class="rounded-lg mr-2"
                  elevation="0"
                  @click="_eraser"
                >
                    Limpar <v-icon class="ml-2" small>mdi-eraser</v-icon>
                </v-btn>

                <v-btn
                  color="primary"
                  small
                  class="rounded-lg"
                  elevation="0"
                  @click="_load"
                >
                    Buscar <v-icon class="ml-2" small>mdi-magnify</v-icon>
                </v-btn>
              </v-card-actions>
          </v-card>
        </v-menu>
      </v-col>

      <v-col cols="6" md="2">
        <v-btn
            color="btn-primary"
            class="rounded-lg"
            block
            small
            dark
            @click="_add"
            v-if="$can('order_add')"
        >
          Adicionar <v-icon small class="ml-2">mdi-plus</v-icon>
        </v-btn>
      </v-col>
    </v-row>


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
            <v-card-text>
                <v-row>
                  <v-col cols="12" md="9">
                    <div class="font-weight-bold text-h6">
                      {{ order.id }}
                    </div>
                  </v-col>
                  <v-col cols="12" md="3" class="d-flex justify-center">
                    <v-chip
                      v-if="order.status"
                      class="d-flex justify-center"
                      label
                      :color="order.status | statusColor"
                      style="width: 100%;"
                      small
                    >
                      {{ order.status }}
                      <v-icon class="ml-2">{{ order.status | statusIcon }}</v-icon>
                    </v-chip>
                  </v-col>

                  <v-col cols="12" md="9">
                    <div>
                      {{ order.client.name }}
                    </div>
                    <div v-if="order.address">
                      {{ order.address.street }} {{ order.address.number ? `n° ${order.address.number}` : '' }}, {{ order.address.district }} - {{ order.address.city }}
                    </div>
                  </v-col>

                  <v-col cols="12" md="3" class="d-flex align-end">
                    <v-btn text color="btn-primary" v-on:click="_edit(order.id)" small block>
                      Ver informações
                    </v-btn>
                  </v-col>
                </v-row>
            </v-card-text>

            <v-expand-transition>
              <v-card
                v-show="hover && (order.technical_visit_date || order.complaint || order.work_done)"
              >
                <v-card-text>
                  <div v-if="order.technical_visit_date">
                    <v-icon small>mdi-calendar</v-icon>
                    <b>DATA VISITA TÉCNICA:</b> {{ order.technical_visit_date | formatDMY }} {{ order.technical_visit_time }}
                    <v-divider color="primary" class="mx-2 my-1"/>
                  </div>

                  <div v-if="order.complaint">
                    <v-icon small>mdi-comment-alert-outline</v-icon>
                    <b>PROBLEMA RECLAMADO:</b> {{ order.complaint }}
                    <v-divider color="primary" class="mx-2 my-1"/>
                  </div>

                  <div v-if="order.work_done">
                    <v-icon small>mdi-comment-check</v-icon>
                    <b>SERVIÇO REALIZADO:</b> {{ order.work_done }}
                  </div>
                </v-card-text>
              </v-card>
            </v-expand-transition>

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
    menuFilter: false,
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
        case 'EM ANDAMENTO': return 'primary';
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
      this.menuFilter = false;

      let params = {
        page: this.table.page,
        itemsPerPage: this.table.itemsPerPage,
        orderBy: this.table.orderBy,
        relations: [ 'client', 'address' ],
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
      this.$can('order_show')
        ? this.$router.push({
            name: 'order.show',
            params: { id }
        }) : null
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
