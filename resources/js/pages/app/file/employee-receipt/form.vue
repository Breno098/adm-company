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
            v-if="!idByRoute && $role.employee_receipt.add() && !employee_receipt.id"
            color="btn-primary"
            @click="_store"
            :loading="loading"
            rounded
            dark
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
                label="VALOR TOTAL"
                type="number"
                prefix="R$"
                outlined
                dense
                v-model="employee_receipt.amount"
                :loading="loading"
                :rules="[rules.required]"
                :error="errors.amount && !employee_receipt.amount"
                :readonly="idByRoute || employee_receipt.id"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-autocomplete
                v-model="employee_receipt.employee_id"
                :items="employees"
                item-text="name"
                item-value="id"
                label="FUNCIONÁRIO"
                outlined
                dense
                :loading="loading"
                no-data-text="Nenhum funcionário encontrado"
                :rules="[rules.required]"
                :error="errors.employee_id && !employee_receipt.employee_id"
                :readonly="idByRoute || employee_receipt.id"
              ></v-autocomplete>
            </v-col>

            <v-col cols="12" md="6">
              <v-menu
                v-model="menuDateStart"
                :close-on-content-click="false"
                max-width="290"
                transition="scale-transition"
                offset-y
                :disabled="idByRoute || employee_receipt.id"
              >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  append-icon="mdi-calendar"
                  :value="employee_receipt.date_start | formatDate"
                  clearable
                  label="DATA INICIO"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  @click:clear="employee_receipt.date_start = null"
                  outlined
                  dense
                  :loading="loading"
                  :rules="[rules.required]"
                  :error="errors.date_start && !employee_receipt.date_start"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="employee_receipt.date_start"
                @change="menuDateStart = false"
                no-title
                crollable
                locale="pt-Br"
              ></v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="6">
              <v-menu
                v-model="menuDateEnd"
                :close-on-content-click="false"
                max-width="290"
                transition="scale-transition"
                offset-y
                :disabled="idByRoute || employee_receipt.id"
              >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  append-icon="mdi-calendar"
                  :value="employee_receipt.date_end | formatDate"
                  clearable
                  label="DATA INICIO"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  @click:clear="employee_receipt.date_end = null"
                  outlined
                  dense
                  :loading="loading"
                  :rules="[rules.required]"
                  :error="errors.date_end && !employee_receipt.date_end"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="employee_receipt.date_end"
                @change="menuDateEnd = false"
                no-title
                crollable
                locale="pt-Br"
              ></v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12">
              <v-textarea
                :readonly="idByRoute || employee_receipt.id"
                label="COMENTÁRIOS"
                outlined
                dense
                v-model="employee_receipt.comments"
                :loading="loading"
                @input="employee_receipt.comments = employee_receipt.comments.toUpperCase()"
              ></v-textarea>
            </v-col>

          </v-row>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            v-if="!idByRoute && $role.employee_receipt.add && !employee_receipt.id"
            color="btn-primary"
            @click="_store"
            :loading="loading"
            rounded
            dark
          >
            Salvar <v-icon dark class="ml-2">mdi-content-save</v-icon>
          </v-btn>

          <v-btn
            v-if="$role.employee_receipt.download()"
            color="btnSecondary"
            @click="download"
            :loading="loading"
            rounded
            dark
          >
            Baixar o arquivo <v-icon dark class="ml-2">mdi-download</v-icon>
          </v-btn>

          <v-spacer></v-spacer>
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
    loading: false,
    menuDateStart: false,
    menuDateEnd: false,
    errors: {
      amount: false,
      date_start: false,
      date_end: false,
      employee_id: false
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    employee_receipt: {
      date_start: null,
      date_end: null,
      amount: null,
      discount_amount: null,
      comments: null,
      path: null,
      employee_id: null
    },
    employees: [],
  }),
  computed: {
    titlePage(){
      return this.$route.params.id ? 'Recibo' : 'Adicionar Recibo';
    },
    idByRoute(){
      return this.$route.params.id;
    }
  },
  filters: {
    formatDate(date){
      return date ? moment(date).format('DD/MM/YYYY') : '';
    },
  },
  mounted(){
    this._start();
  },
  methods: {
    async _start(){
      if(this.idByRoute){
        await this._load();
      }

      await this._loadEmployees();
    },
    async _load(){
      this.loading = true;
      await axios.get(`/api/employee-receipt/${this.idByRoute}`).then(response => {
        if(response.data.success){
          return this.employee_receipt = response.data.data;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar dados recibo' })
        this.$refs.fireDialog.hide(1500);
      });
      this.loading = false;
    },
    async _loadEmployees(){
      this.loading = true;
      await axios.get(`/api/employee`).then(response => {
        if(response.data.success){
          return this.employees = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar categorias' })
      });
      this.loading = false;
    },
    async _store(pushRoute = true){
      for (const field in this.errors) {
        if(!this.employee_receipt[field])
          return this.errors[field] = true;
      }

      const ok = await this.$refs.fireDialog.confirm({
          title: 'Finalizar recibo',
          message: 'Ao confirmar, o sistema gerará um recibo que não poderá ser editado.',
      })

      if (!ok) {
        return;
      }

      this.loading = true;
      this.$refs.fireDialog.loading({ title: this.idByRoute ? 'Atualizando...' : 'Salvando...' })

      const response = !this.idByRoute
        ? await axios.post('/api/employee-receipt', this.employee_receipt)
        : await axios.put(`/api/employee-receipt/${this.idByRoute}`, this.employee_receipt);

      this.loading = false;

      if(!response.data.success){
        return this.$refs.fireDialog.error({ title: 'Error aos salvar recibo' })
      }

      this.employee_receipt = response.data.data;

      if(pushRoute){
        this.$refs.fireDialog.success({ title: 'Recibo salvo com sucesso' })
        return setTimeout(() => this.$router.push({ name: 'file.employee-receipt.index' }), 1500);
      } else {
         this.$refs.fireDialog.hide();
      }
    },
    async download(){
      if(!this.idByRoute && !this.employee_receipt.id){
        await this._store(false);
      }

      window.open(`/api/employee-receipt/${this.employee_receipt.id}/download`);
    }
  }

}
</script>
