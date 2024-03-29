<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <p class="font-weight-bold mb-5 text-h5">
      <v-icon color="primary">mdi-format-list-checks</v-icon>
      Pedidos
    </p>

    <v-row class="mb-2">
      <v-col cols="6" md="10" class="d-flex">
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

                  <v-col cols="12" md="6">
                    <v-menu
                      v-model="menuTechnicalVisitDateFrom"
                      :close-on-content-click="false"
                      max-width="290"
                      transition="scale-transition"
                      offset-y
                    >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        append-icon="mdi-calendar"
                        :value="table.filters.technical_visit_date_from | formatDMY"
                        clearable
                        label="VISITA TÉCNICA DE"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        @click:clear="table.filters.technical_visit_date_from = null"
                        filled
                        dense
                        :loading="table.loading"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="table.filters.technical_visit_date_from"
                        @change="menuTechnicalVisitDateFrom = false"
                        no-title
                        crollable
                        locale="pt-Br"
                        color="primary"
                    ></v-date-picker>
                    </v-menu>
                  </v-col>

                  <v-col cols="12" md="6">
                    <v-menu
                      v-model="menuTechnicalVisitDateTo"
                      :close-on-content-click="false"
                      max-width="290"
                      transition="scale-transition"
                      offset-y
                    >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        append-icon="mdi-calendar"
                        :value="table.filters.technical_visit_date_to | formatDMY"
                        clearable
                        label="VISITA TÉCNICA ATÉ"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        @click:clear="table.filters.technical_visit_date_to = null"
                        filled
                        dense
                        :loading="table.loading"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="table.filters.technical_visit_date_to"
                        @change="menuTechnicalVisitDateTo = false"
                        no-title
                        crollable
                        locale="pt-Br"
                        color="primary"
                    ></v-date-picker>
                    </v-menu>
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

        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn text small @click="_eraser" v-bind="attrs" v-on="on">
               <v-icon small>mdi-eraser</v-icon>
            </v-btn>
          </template>
          <span>Limpar filtros</span>
        </v-tooltip>
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
        <v-hover v-slot="{ hover }" style="cursor: pointer;">
          <v-card
            :class="{ 'on-hover': hover }"
            :elevation="hover ? 12 : 2"
          >
            <v-card-text v-on:click="_edit(order.id)">
                <v-row>
                  <v-col cols="12" md="6">
                    <div class="font-weight-bold text-h6">
                      {{ order.id }}
                    </div>
                  </v-col>
                  <v-col cols="12" md="3">
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
                  <v-col cols="12" md="3">
                    <v-chip
                      v-if="order.payment_status"
                      class="d-flex justify-center"
                      label
                      :color="order.payment_status | paymentStatusColor"
                      style="width: 100%;"
                      small
                    >
                      {{ order.payment_status }}
                      <v-icon class="ml-2">{{ order.payment_status | paymentStatusIcon }}</v-icon>
                    </v-chip>
                  </v-col>

                  <v-col cols="12" md="9">
                    <div>
                      {{ order.client && order.client.name ? order.client.name : '' }}
                    </div>
                    <div>
                      {{ order.address && order.address.street ? order.address.street : '' }}
                      {{ order.address && order.address.number ? `n° ${order.address.number}` : '' }},
                      {{ order.address && order.address.district }}
                      {{ order.address && order.address.city ? `- ${order.address.city}` : '' }}
                    </div>
                  </v-col>
                </v-row>
            </v-card-text>

            <v-expansion-panels flat>
              <v-expansion-panel>
                <v-expansion-panel-header class="primary--text">
                  <template v-slot:actions>
                    <v-btn text color="btn-primary" small block>
                      Informações rapidas
                      <v-icon color="primary" small>
                        mdi-chevron-down
                      </v-icon>
                    </v-btn>
                  </template>
                </v-expansion-panel-header>
                <v-expansion-panel-content class="caption">
                  <v-icon small color="primary">mdi-calendar</v-icon>
                  <b>DATA VISITA TÉCNICA:</b> {{ order.technical_visit_date | formatDMY }} {{ order.technical_visit_time }}

                  <v-divider class="mx-2 my-1"/>

                  <v-icon small color="btn-delete">mdi-comment-alert-outline</v-icon>
                  <b>PROBLEMA RECLAMADO:</b> {{ order.complaint }}

                  <v-divider class="mx-2 my-1"/>

                  <v-icon small color="green">mdi-comment-check-outline</v-icon>
                  <b>SERVIÇO REALIZADO:</b> {{ order.work_done }}

                  <v-divider class="mx-2 my-1"/>

                  <v-icon small color="primary">mdi-account-outline</v-icon>
                  <b>RESPONSÁVEL TÉCNICO:</b> {{ order.technician_id ? order.technician.name : '' }}
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
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
    menuTechnicalVisitDateFrom: false,
    menuTechnicalVisitDateTo: false,
    table: {
      filters: {
        client_name: '',
        address: '',
        status: [],
        technical_visit_date_from: null,
        technical_visit_date_to: null
      },
      orderBy: 'created_at:desc',
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
      items: [],
      loading: false,
      relations: [ 'client', 'address', 'technician' ],
    },
    statuses: [
      'CANCELADO',
      'AGUARDANDO APROVAÇÃO',
      'EM ANDAMENTO',
      'CONCLUÍDO'
    ]
  }),
  mounted() {
    this._start();
  },
  methods: {
    async _start(){
      this._loadWithStorageFilters();
    },
    async _loadWithStorageFilters() {
      let filter = localStorage.getItem('filter-order');

      if(filter !== 'null' && filter) {
        this.table.filters = JSON.parse(filter);
      }

      await this._load();
    },
    async _load(){
      this.menuFilter = false;

      let params = {
        page: this.table.page,
        itemsPerPage: this.table.itemsPerPage,
        orderBy: this.table.orderBy,
        relations: this.table.relations,
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

          localStorage.setItem('filter-order', JSON.stringify(this.table.filters))
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
