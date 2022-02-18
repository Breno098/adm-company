<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-card class="mb-4">
      <v-toolbar elevation="0">
        <v-toolbar-title> {{ report.month_name }} / {{ report.year }} </v-toolbar-title>
        <v-progress-linear
          indeterminate
          height="4"
          bottom
          absolute
          :active="loading"
        ></v-progress-linear>
      </v-toolbar>
    </v-card>

    <v-row>
      <v-col cols="12" md="6">
        <v-card>
          <v-card-title>
            Faturamento
          </v-card-title>

          <v-card-text>
            <v-virtual-scroll
              :items="report.installments_paid"
              :item-height="90"
              height="200"
            >
              <template v-slot:default="{ item: installment }">
                <router-link :to="{ name: 'order.show', params: { id: installment.order.id } }" style="text-decoration: none">
                  <v-list-item>
                    <v-list-item-content>
                      <v-list-item-title>{{ installment.pay_day | formatDate }} - {{ installment.order.client.name }}</v-list-item-title>
                      <v-list-item-subtitle class="mt-3 d-flex flex-row justify-space-between align-center">
                        <div>
                          {{ installment | stringInstallment }}
                        </div>
                        <h2 class="green--text">
                          {{ installment.value | formatMoney }}
                        </h2>
                      </v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </router-link>

                <v-divider color="grey" class="mx-5"></v-divider>
              </template>
            </v-virtual-scroll>
          </v-card-text>

          <v-divider color="black"></v-divider>

          <v-card-actions class="d-flex flex-row justify-space-between align-center px-5">
            <div>
              Total
            </div>
            <h2 class="green--text">
              {{ report.amount_paid | formatMoney }}
            </h2>
          </v-card-actions>
        </v-card>
      </v-col>

      <v-col cols="12" md="6">
        <v-card>
          <v-card-title>
            Custos
          </v-card-title>

          <v-card-text>
            <v-virtual-scroll
              :items="report.installments_expense_paid"
              :item-height="90"
              height="200"
            >
              <template v-slot:default="{ item: installment }">
                <router-link :to="{ name: 'expense.show', params: { id: installment.expense.id } }" style="text-decoration: none">
                  <v-list-item>
                    <v-list-item-content>
                      <v-list-item-title>{{ installment.pay_day | formatDate }} - {{ installment.expense.title }}</v-list-item-title>
                      <v-list-item-subtitle class="mt-3 d-flex flex-row justify-space-between align-center">
                        <div>
                        </div>
                        <h2 class="red--text">
                          {{ installment.value | formatMoney }}
                        </h2>
                      </v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </router-link>

                <v-divider color="grey" class="mx-5"></v-divider>
              </template>
            </v-virtual-scroll>
          </v-card-text>

          <v-divider color="black"></v-divider>

          <v-card-actions class="d-flex flex-row justify-space-between align-center px-5">
            <div>
              Total
            </div>
            <h2 class="red--text">
              {{ report.expense_paid | formatMoney }}
            </h2>
          </v-card-actions>
        </v-card>
      </v-col>

      <v-col cols="12">
        <v-card>
          <v-card-text class="d-flex flex-column justify-space-around align-center">
            <h2 class="mb-2">Lucro</h2>
            <h1 :class="report.profit >= 0 ? 'green--text' : 'red--text'">{{ report.profit | formatMoney }}</h1>
          </v-card-text>
        </v-card>
      </v-col>


      <v-col cols="12" md="6">
        <v-card>
          <v-card-title>
            A receber
          </v-card-title>

          <v-card-text>
            <v-virtual-scroll
              :items="report.installments_to_receive"
              :item-height="90"
              height="200"
            >
              <template v-slot:default="{ item: installment }">
                <router-link :to="{ name: 'order.show', params: { id: installment.order.id } }" style="text-decoration: none">
                  <v-list-item>
                    <v-list-item-content>
                      <v-list-item-title>{{ installment.due_date | formatDate }} - {{ installment.order.client.name }}</v-list-item-title>
                      <v-list-item-subtitle class="mt-2 d-flex flex-row justify-space-between align-center">
                        <div>
                          {{ installment | stringInstallment }}
                        </div>
                        <h2 class="blue--text">
                          {{ installment.value | formatMoney }}
                        </h2>
                      </v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </router-link>

                <v-divider color="grey" class="mx-5"></v-divider>
              </template>
            </v-virtual-scroll>
          </v-card-text>

          <v-card-actions class="d-flex flex-row justify-space-between align-center px-5">
            <div>
              Total
            </div>
            <h2 class="blue--text">
              {{ report.amount_to_receive | formatMoney }}
            </h2>
          </v-card-actions>
        </v-card>
      </v-col>

      <v-col cols="12" md="6">
        <v-card>
          <v-card-title>
            Não recebido
          </v-card-title>

          <v-card-text>
            <v-virtual-scroll
              :items="report.installments_unpaid"
              :item-height="90"
              height="200"
            >
              <template v-slot:default="{ item: installment }">
                <router-link :to="{ name: 'order.show', params: { id: installment.order.id } }" style="text-decoration: none">
                  <v-list-item>
                    <v-list-item-content>
                      <v-list-item-title>{{ installment.due_date | formatDate }} - {{ installment.order.client.name }}</v-list-item-title>
                      <v-list-item-subtitle class="mt-2 d-flex flex-row justify-space-between align-center">
                        <div>
                          {{ installment | stringInstallment }}
                        </div>
                        <h2 class="orange--text">
                          {{ installment.value | formatMoney }}
                        </h2>
                      </v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </router-link>

                <v-divider color="grey" class="mx-5"></v-divider>
              </template>
            </v-virtual-scroll>
          </v-card-text>

           <v-card-actions class="d-flex flex-row justify-space-between align-center px-5">
            <div>
              Total
            </div>
            <h2 class="orange--text">
              {{ report.amount_unpaid | formatMoney }}
            </h2>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';

moment.locale('pt-br');

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: this.titlePage }
  },
  data: () => ({
    titlePage: 'Relatório Financeiro',
    loading: false,
    report: {
      month: null,
      year: null,
      month_name: null,
      installments_paid: [],
      installments_unpaid: [],
      installments_to_receive: [],
      installments_expense_paid: [],
      amount_paid: 0,
      amount_unpaid: 0,
      amount_to_receive: 0,
      expense_paid: 0,
      profit: 0
    },
  }),
  computed: {
    year(){
      return this.$route.params.year;
    },
    month(){
      return this.$route.params.month;
    },
  },
  mounted() {
    this._loadReport();
  },
  filters: {
    formatDate(date) {
      return date ? moment(date).format('DD/MM/YYYY') : '';
    },
    formatMoney(money){
      return money ? 'R$ ' + money.toFixed(2).toString().replace('.', ',') : 'R$ 0,00';
    },
    stringInstallment(installment) {
      return installment.order.number_of_installments > 1 ? `parcela ${installment.number}/${installment.order.number_of_installments}` : 'á vista';
    }
  },
  methods: {
    async _loadReport(){
      let params = { year: this.year, month: this.month };

      this.loading = true;

      await axios.get('/api/report/finance/by-month-and-year', { params }).then(response => {
        this.report.month = response.data.data.month;
        this.report.year = response.data.data.year;
        this.report.month_name = response.data.data.month_name;
        this.report.installments_paid = response.data.data.installments_paid;
        this.report.installments_unpaid = response.data.data.installments_unpaid;
        this.report.installments_to_receive = response.data.data.installments_to_receive;
        this.report.amount_paid = response.data.data.amount_paid;
        this.report.amount_unpaid = response.data.data.amount_unpaid;
        this.report.amount_to_receive = response.data.data.amount_to_receive;
        this.report.installments_expense_paid = response.data.data.installments_expense_paid;
        this.report.expense_paid = response.data.data.expense_paid;
        this.report.profit = response.data.data.profit;
      }).catch(error => {
        this.$refs.fireDialog.error({ title: 'Erro ao carregar relatório' });
      })

      this.loading = false;
    },
  }
}
</script>
