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

        <v-spacer></v-spacer>

          <v-btn
          v-if="(!idByRoute && $role.expense.add()) || (idByRoute && $role.expense.update()) "
          color="btn-primary"
          @click="_store"
          :loading="loading"
          rounded
          small
        >
          Salvar <v-icon dark class="ml-2">mdi-content-save</v-icon>
        </v-btn>
      </v-toolbar>
    </v-card>

    <v-card>
      <v-card-text>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field
              label="TITULO"
              outlined
              dense
              v-model="expense.title"
              :loading="loading"
              :rules="[rules.required]"
              :error="errors.title && !expense.title"
              @input="expense.title = expense.title.toUpperCase()"
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="6">
            <v-select
              v-model="expense.categories"
              vali
              :items="categories"
              item-text="name"
              item-value="id"
              label="CATEGORIAS"
              outlined
              dense
              :loading="loading"
              multiple
            ></v-select>
          </v-col>

          <v-col cols="12">
              <v-textarea
                label="DESCRIÇÃO"
                outlined
                dense
                v-model="expense.description"
                :loading="loading"
                hint="DETALHES DO CUSTO/DESPESA"
                @input="expense.description = expense.description.toUpperCase()"
              ></v-textarea>
            </v-col>

            <v-col cols="12" v-if="expense.installments.length > 1">
              <v-text-field
                type="number"
                prefix="R$"
                label="VALOR TOTAL"
                outlined
                dense
                v-model="expense.value"
                :loading="loading"
                readonly
                color="black"
              ></v-text-field>
          </v-col>
        </v-row>

        <v-row
          v-for="(installment, index) in expense.installments"
          :key="index"
        >
          <v-col cols="12" :md="expense.installments.length > 1 ? 2 : 4">
            <v-text-field
              outlined
              prefix="R$"
              type="number"
              dense
              label="VALOR"
              v-model="installment.value"
            ></v-text-field>
          </v-col>

          <v-col cols="12" :md="expense.installments.length > 1 ? 3 : 4">
              <v-select
              :items="payments"
              label="FORMA DE PAGAMENTO"
              outlined
              dense
              v-model="installment.payment_method"
              clearable
            ></v-select>
          </v-col>

          <v-col cols="12" :md="expense.installments.length > 1 ? 2 : 4">
            <v-dialog
              v-model="menu_date_installments_pay_day[index]"
              :close-on-content-click="false"
              max-width="290"
              transition="scale-transition"
              offset-y
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  :value="installment.pay_day | formatDate"
                  label="DATA PAGAMENTO"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  outlined
                  dense
                  clearable
                  @click:clear="installment.pay_day = null"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="installment.pay_day"
                @change="menu_date_installments_pay_day[index] = false"
                scrollable
                no-title
                crollable
                locale="pt-Br"
              ></v-date-picker>
            </v-dialog>
          </v-col>

          <v-col cols="12" md="2" v-if="expense.installments.length > 1">
            <v-dialog
              v-model="menu_date_installments_due_date[index]"
              :close-on-content-click="false"
              max-width="290"
              transition="scale-transition"
              offset-y
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  :value="installment.due_date | formatDate"
                  label="DATA VENCIMENTO"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  outlined
                  dense
                  clearable
                  @click:clear="installment.due_date = null"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="installment.due_date"
                @change="menu_date_installments_due_date[index] = false"
                scrollable
                no-title
                crollable
                locale="pt-Br"
              ></v-date-picker>
            </v-dialog>
          </v-col>

          <v-col cols="12" md="3" v-if="expense.installments.length > 1">
              <v-select
              :items="['PAGO', 'EM ABERTO', 'CANCELADO', 'INADIMPLENTE']"
              label="STATUS"
              outlined
              dense
              v-model="installment.status"
            ></v-select>
          </v-col>

          <v-divider color="grey" class="mx-5" v-if="(index + 1) < expense.installments.length"/>
        </v-row>

        <v-row>
            <v-col cols="12" class="d-flex flex-row justify-end">
            <v-btn
              color="btn-delete"
              @click="deleteLastInstallment"
              :loading="loading"
              small
              rounded
              v-if="expense.installments.length > 1"
            >
              Apagar ultima parcela <v-icon color="red darken-4">mdi-delete</v-icon>
            </v-btn>

            <v-btn
              color="btn-primary"
              class="ml-3"
              :loading="loading" small rounded
              @click="expense.installments.push({
                number: expense.installments.length + 1,
                payment_method: null,
                status: 'EM ABERTO',
                due_date: dateInstallment(),
                pay_day: null,
                value: null
              })"
            >
              {{ expense.installments.length > 1 ? 'Adicionar parcela' : 'Pagamento parcelado' }}
              <v-icon>{{ expense.installments.length > 1 ? 'mdi-plus' : '' }}</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          v-if="(!idByRoute && $role.expense.add()) || (idByRoute && $role.expense.update()) "
          color="btn-primary"
          @click="_store"
          :loading="loading"
          rounded
        >
          Salvar <v-icon class="ml-2">mdi-content-save</v-icon>
        </v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import { format, parseISO } from 'date-fns'

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: this.titlePage }
  },
  watch: {
    'expense.installments': {
      deep: true,
      handler() {
        if(this.expense.installments.length < 2){
          this.expense.installments[0].status = 'PAGO';
          this.expense.installments[0].due_date = moment().format('YYYY-MM-DD');
        }

        this.expense.value = 0;
        this.expense.installments.forEach(installment => this.expense.value += installment.value ? parseFloat(installment.value) : 0);
      }
    }
  },
  data: () => ({
    tab: null,
    menu_date: false,
    menu_time: false,
    menu_date_installments_pay_day: [],
    menu_date_installments_due_date: [],
    loading: false,
    errors: {
      title: false,
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    expense: {
      title: null,
      description: null,
      date: format( parseISO(new Date().toISOString()), 'yyyy-MM-dd'),
      time: null,
      quantity: null,
      value: null,
      categories: [],
      installments: [{
          number: 1,
          payment_method: null,
          status: 'PAGO',
          due_date: moment().format('YYYY-MM-DD'),
          pay_day: moment().format('YYYY-MM-DD'),
          value: null
      }],
      number_of_installments: null
    },
    categories: [],
    payments: [
      'PIX',
      'DINHEIRO',
      'CARTÃO DÉBITO',
      'CARTÃO CRÉDITO',
      'CHEQUE',
      'BOLETO',
      'CONTRATO',
      'TRANSFERÊNCIA'
    ],
  }),
  filters: {
    formatDate(date){
      return date ? moment(date).format('DD/MM/YYYY') : '';
    },
  },
  computed: {
    titlePage(){
      return this.$route.params.id ? 'Custo/Despesa' : 'Adicionar Custo/Despesa';
    },
    idByRoute(){
      return this.$route.params.id;
    },
  },
  mounted(){
    this._start();
  },
  methods: {
    dateInstallment() {
      return moment().add(this.expense.installments.length, 'M').format('YYYY-MM-DD');
    },
    async _start(){
      if(this.idByRoute){
        await this._load();
      }

      await this._loadCategories();
    },
    async _loadCategories(){
      this.loading = true;
      await axios.get(`/api/category?type=expense`).then(response => {
        if(response.data.success){
          return this.categories = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar categorias' })
      });
      this.loading = false;
    },
    async _load(){
      this.loading = true;
      await axios.get(`/api/expense/${this.idByRoute}`).then(response => {
        if(response.data.success){
          this.expense = response.data.data;

          return this.expense.categories = response.data.data.categories_id;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar dados do custo/despesa' });
        this.$refs.fireDialog.hide(1500);
      });
      this.loading = false;
    },
    async _store(){
      for (const field in this.errors) {
        if(!this.expense[field])
          return this.errors[field] = true;
      }

      this.loading = true;
      this.$refs.fireDialog.loading({ title: this.idByRoute ? 'Atualizando...' : 'Salvando...' })

      this.loading = true;

      const response = !this.idByRoute ? await axios.post('/api/expense', this.expense) : await axios.put(`/api/expense/${this.idByRoute}`, this.expense);

      this.loading = false;

      if(response.data.success){
        this.$refs.fireDialog.success({ title: 'Custo/Despesa salvo com sucesso' })
        return setTimeout(() => this.$router.push({ name: 'expense.index' }), 1500);
      }

      this.$refs.fireDialog.error({ title: 'Error aos salvar custo/despesa.' })
    },
    async deleteLastInstallment() {
      const ok = await this.$refs.fireDialog.confirm({
          title: `Deletar parcela ${this.expense.installments.length}`,
          textConfirmButton: 'Deletar',
          colorConfirButton: 'btn-delete',
          colorCancelButton: 'btn-primary'
      })

      if (ok) {
        this.expense.installments.pop();
      }
    },
  }

}
</script>
