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

    <v-card elevation="0">
      <v-card-title>
        Resumo
      </v-card-title>

      <v-card-text>
        <v-row>
          <v-col cols="12" md="3" v-if="report.monthly">
            <v-card height="100%">
              <v-card-text class="text-center">
                <v-btn text color="primary" block class="mt-4" @click="addYear">
                  <v-icon color="primary">mdi-arrow-up-drop-circle-outline</v-icon>
                </v-btn>

                <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                <div class="text-subtitle-1">Ano</div>
                <h1 class="blue--text">{{ filter.year }}</h1>

                <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                <v-btn text color="primary" block class="mt-5" @click="subYear">
                  <v-icon color="primary">mdi-arrow-down-drop-circle-outline</v-icon>
                </v-btn>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="9" v-if="report.monthly">
            <v-card>
              <v-card-text class="text-center">
                <div class="text-subtitle-1">Faturamento</div>
                <h1 class="green--text">{{ report.annually.amount_paid | formatMoney }}</h1>

                <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                <div class="text-subtitle-1">A receber</div>
                <h1 class="blue--text">{{ report.annually.amount_to_receive | formatMoney }}</h1>

                <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                <div class="text-subtitle-1">Não recebido</div>
                <h1 class="orange--text">{{ report.annually.amount_unpaid | formatMoney }}</h1>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="3" v-for="month in report.monthly" :key="month.month">
            <v-hover v-slot="{ hover }">
              <v-card
                :class="{ 'on-hover': hover }"
                :elevation="hover ? 12 : 2"
              >
                <v-app-bar color="primary" dense>
                  <v-toolbar-title>{{ month.month_name }}</v-toolbar-title>
                </v-app-bar>

                <v-card-text>
                  <div class="text-subtitle-1">Faturamento</div>
                  <h1 class="green--text">{{ month.amount_paid | formatMoney }}</h1>

                  <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                  <div class="text-subtitle-1">A receber</div>
                  <h1 class="blue--text">{{ month.amount_to_receive | formatMoney }}</h1>

                  <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                  <div class="text-subtitle-1">Não recebido</div>
                  <h1 class="orange--text">{{ month.amount_unpaid | formatMoney }}</h1>
                </v-card-text>

                <v-card-actions>
                  <v-spacer/>
                  <v-btn text color="primary" small>
                    Detalhes
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-hover>
          </v-col>


        </v-row>


         <!-- <v-simple-table dense>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">MÊS</th>
                <th class="text-left">FATURAMENTO</th>
                <th class="text-left">A RECEBER</th>
                <th class="text-left">NÃO RECEBIDO</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="month in report.monthly"
                :key="month.month"
              >
                <td>{{ month.month_name }}</td>
                <td>{{ month.amount_paid | formatMoney }}</td>
                <td>{{ month.amount_to_receive | formatMoney }}</td>
                <td>{{ month.amount_unpaid | formatMoney }}</td>
              </tr>
              <tr>
                <td>TOTAL</td>
                <td>{{ report.annually.amount_paid | formatMoney }}</td>
                <td>{{ report.annually.amount_to_receive | formatMoney }}</td>
                <td>{{ report.annually.amount_unpaid | formatMoney }}</td>
              </tr>
            </tbody>
          </template>
        </v-simple-table> -->
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
    report: {
      monthly: [],
      annually: {}
    },
    filter: {
      year: moment().year()
    },
  }),
  computed: {
  },
  mounted() {
    this._loadReport();
  },
  filters: {
    formatDate(date){
      return date ? moment(date).format('DD/MM/YYYY') : '';
    },
    formatMoney(money){
      return money ? 'R$ ' + money.replace('.', ',') : 'R$ 0,00';
    }
  },
  methods: {
    addYear() {
      this.filter.year = this.filter.year + 1;
      this._loadReport();
    },
    subYear() {
      this.filter.year = this.filter.year - 1;
      this._loadReport();
    },
    async _loadReport(){
      let params = { year: this.filter.year };

      this.loading = true;

      await axios.get('/api/report/finance/by-year', { params }).then(response => {
        this.report.monthly = response.data.data.monthly;
        this.report.annually = response.data.data.annually;
      }).catch(error => {
        this.$refs.fireDialog.error({ title: 'Erro ao carregar relatório' });
      })

      this.loading = false;
    },
  }
}
</script>
