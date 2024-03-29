<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <p class="font-weight-bold mb-5 text-h5">
      <v-icon color="primary">mdi-cash-usd</v-icon>
      {{ titlePage }}
    </p>

    <v-row>
      <v-col
        cols="2"
        offset="1"
        offset-md="2"
        class="d-flex flex-column justify-space-around align-center"
      >
        <v-btn color="btn-primary" dark @click="subYear" fab>
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
      </v-col>
      <v-col
        cols="6"
        md="4"
        class="d-flex flex-column justify-center align-center"
      >
        <v-progress-circular
          :size="130"
          :width="10"
          color="primary"
          :indeterminate="loading"
        >
          {{ filter.year }}
        </v-progress-circular>
      </v-col>
      <v-col
        cols="2"
        class="d-flex flex-column justify-space-around align-center"
      >
        <v-btn color="btn-primary" dark @click="addYear" fab>
          <v-icon>mdi-chevron-right</v-icon>
        </v-btn>
      </v-col>
    </v-row>

    <v-row>
      <v-col
        cols="12"
        md="4"
        v-for="month in report.monthly"
        :key="month.month"
      >
        <v-expansion-panels >
          <v-expansion-panel>
            <v-expansion-panel-header class="white--text" color="primary">
              {{ month.month_name }}

              <template v-slot:actions class="d-flex justify-center">
                  <v-icon color="white" small>
                    mdi-chevron-down
                  </v-icon>
              </template>
            </v-expansion-panel-header>

            <v-expansion-panel-content>
              <router-link
                :to="{ name: 'report.finance.details', params: { year: filter.year, month: month.month } }"
                style="text-decoration: none"
              >
                <v-card elevation="0">
                  <v-card-text>
                    <div class="text-subtitle-1">Faturamento</div>
                    <h1 class="green--text">{{ month.amount_paid | formatMoney }}</h1>

                    <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                    <div class="text-subtitle-1">Custos</div>
                    <h1 class="red--text">{{ month.expense_paid | formatMoney }}</h1>

                    <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                    <div class="text-subtitle-1">Lucro</div>
                    <h1 :class="month.profit >= 0 ? 'green--text' : 'red--text'">{{ month.profit | formatMoney }}</h1>

                    <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>
                    <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                    <div class="text-subtitle-1">A receber</div>
                    <h1 class="blue--text">{{ month.amount_to_receive | formatMoney }}</h1>

                    <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                    <div class="text-subtitle-1">Não recebido</div>
                    <h1 class="orange--text">{{ month.amount_unpaid | formatMoney }}</h1>
                  </v-card-text>

                  <v-card-actions>
                    <v-spacer/>
                    <v-btn color="primary" small>
                      Resumo
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </router-link>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-col>

      <v-col cols="12" v-if="report.monthly">
        <v-expansion-panels >
          <v-expansion-panel>
            <v-expansion-panel-header class="white--text" color="primary">
              {{ report.annually.year }}

              <template v-slot:actions class="d-flex justify-center">
                  <v-icon color="white" small>
                    mdi-chevron-down
                  </v-icon>
              </template>
            </v-expansion-panel-header>

             <v-expansion-panel-content>
               <v-card elevation="0">
                  <v-card-text>
                    <div class="text-subtitle-1">Faturamento</div>
                    <h1 class="green--text">{{ report.annually.amount_paid | formatMoney }}</h1>

                    <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                    <div class="text-subtitle-1">Custos</div>
                    <h1 class="red--text">{{ report.annually.expense_paid | formatMoney }}</h1>

                    <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                    <div class="text-subtitle-1">Lucro</div>
                    <h1 :class="report.annually.profit >= 0 ? 'green--text' : 'red--text'">{{ report.annually.profit | formatMoney }}</h1>

                    <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>
                    <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                    <div class="text-subtitle-1">A receber</div>
                    <h1 class="blue--text">{{ report.annually.amount_to_receive | formatMoney }}</h1>

                    <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                    <div class="text-subtitle-1">Não recebido</div>
                    <h1 class="orange--text">{{ report.annually.amount_unpaid | formatMoney }}</h1>
                  </v-card-text>
                </v-card>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-col>
    </v-row>
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
