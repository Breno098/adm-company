<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-card class="mb-4">
      <v-toolbar elevation="0">
        <v-toolbar-title> {{ titlePage }} </v-toolbar-title>
        <v-progress-linear
          indeterminate
          height="4"
          bottom
          absolute
          :active="loading"
        ></v-progress-linear>
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
          </v-row>

          <!-- <v-card-actions class="pb-4">
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
          </v-card-actions> -->
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>

    <v-card>
      <v-card-title>
        Resumo
      </v-card-title>
      <v-card-text>
      </v-card-text>

      <v-card-actions>
      </v-card-actions>
    </v-card>
</div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: this.titlePage }
  },
  data: () => ({
    titlePage: 'Relatório Financeiro',
    loading: false,
    orders: []
  }),
  computed: {
  },
  mounted() {
    this._loadOrders();
  },
  filters: {
    formatDate(date){
      return date ? moment(date).format('DD/MM/YYYY') : '';
    },
  },
  methods: {
    async _loadOrders(){
      let params = {
        status_type: 'concluded'
      }

      this.loading = true;
      await axios.get('api/order', { params }).then(response => {
        if(response.data.success){
            this.orders = response.data.data.data;
        } else {
          this.$refs.fireDialog.error({ title: 'Erro ao carregar pedidos '});
        }
      }).catch(error => {
        this.$refs.fireDialog.error({ title: 'Erro ao carregar pedidos'});
      })

      this.loading = false;
    },
  }
}
</script>
