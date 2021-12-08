<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-row>
      <v-col cols="12">
        <v-card elevation="0">
          <v-toolbar elevation="0" class="mb-2">
            <v-toolbar-title> {{ titlePage }} </v-toolbar-title>
            <v-progress-linear
              color="primary"
              indeterminate
              height="4"
              bottom
              absolute
              :active="loading"
            ></v-progress-linear>

            <v-spacer></v-spacer>

             <v-btn
              v-if="(!idByRoute && $role.expense.add()) || (idByRoute && $role.expense.update()) "
              color="btnPrimary"
              @click="_store"
              :loading="loading"
              rounded
              dark
              small
            >
              Salvar <v-icon dark class="ml-2">mdi-content-save</v-icon>
            </v-btn>
          </v-toolbar>

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

            <v-col cols="12" md="6">
              <v-menu
                  v-model="menu_date"
                  :close-on-content-click="false"
                  max-width="290"
                  transition="scale-transition"
                  offset-y
              >
              <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                      append-icon="mdi-calendar"
                      :value="DateFormat"
                      clearable
                      label="DATA"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      @click:clear="expense.date = null"
                      outlined
                      dense
                      :loading="loading"
                      :rules="[rules.required]"
                      :error="errors.date && !expense.date"
                  ></v-text-field>
              </template>
              <v-date-picker
                  v-model="expense.date"
                  @change="menu_date = false"
                  no-title
                  crollable
                  locale="pt-Br"
              ></v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="6">
                <v-menu
                    ref="menu_time"
                    v-model="menu_time"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="expense.time"
                    transition="scale-transition"
                    offset-y
                    max-width="290px"
                    min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="expense.time"
                        label="HORA"
                        prepend-icon="mdi-clock-time-four-outline"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        dense
                        outlined
                        :loading="loading"
                    ></v-text-field>
                  </template>
                  <v-time-picker
                      v-if="menu_time"
                      v-model="expense.time"
                      @click:minute="$refs.menu_time.save(expense.time)"
                      format="24hr"
                  ></v-time-picker>
                </v-menu>
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

              <v-col cols="12" md="6">
                <v-text-field
                  type="number"
                  prefix="R$"
                  label="VALOR"
                  outlined
                  dense
                  v-model="expense.value"
                  :loading="loading"
                  :rules="[rules.required]"
                  :error="errors.value && !expense.value"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  label="QUANTIDADES"
                  type="number"
                  outlined
                  dense
                  v-model="expense.quantity"
                  :loading="loading"
                ></v-text-field>
              </v-col>

              <v-col cols="12">
                  <v-btn
                    v-if="(!idByRoute && $role.client.add()) || (idByRoute && $role.client.update()) "
                    color="btnPrimary"
                    @click="_store"
                    :loading="loading"
                    rounded
                    dark
                  >
                    Salvar <v-icon dark class="ml-2">mdi-content-save</v-icon>
                  </v-btn>
              </v-col>
            </v-row>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="btnPrimary" @click="_store" :loading="loading" rounded dark>
              Salvar <v-icon dark class="ml-2">mdi-content-save</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
         </v-card>
      </v-col>
    </v-row>
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
  data: () => ({
    menu_date: false,
    menu_time: false,
    loading: false,
    errors: {
      title: false,
      date: false,
      value: false
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
    },
    categories: []
  }),
  computed: {
    DateFormat () {
      return this.expense.date ? moment(this.expense.date).format('DD/MM/YYYY') : moment().format('DD/MM/YYYY')
    },
    titlePage(){
      return this.$route.params.id ? 'Custo/Despesa' : 'Adicionar Custo/Despesa';
    },
    idByRoute(){
      return this.$route.params.id;
    }
  },
  mounted(){
    this._start();
  },
  methods: {
    async _start(){
      if(this.idByRoute){
        await this._load();
      }

      await this._loadCategories();
    },
    async _loadCategories(){
      this.loading = true;
      await axios.get(`api/category?type=expense`).then(response => {
        if(response.data.success){
          return this.categories = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar categorias' })
      });
      this.loading = false;
    },
    async _load(){
      this.loading = true;
      await axios.get(`api/expense/${this.idByRoute}`).then(response => {
        if(response.data.success){
          return this.expense = response.data.data;
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

      const response = !this.idByRoute ? await axios.post('api/expense', this.expense) : await axios.put(`api/expense/${this.idByRoute}`, this.expense);

      this.loading = false;

      if(response.data.success){
        this.$refs.fireDialog.success({ title: 'Custo/Despesa salvo com sucesso' })
        return setTimeout(() => this.$router.push({ name: 'expense.index' }), 1500);
      }

      this.$refs.fireDialog.error({ title: 'Error aos salvar custo/despesa.' })
    },
  }

}
</script>
