<template>
  <div>
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
          <v-btn color="green darken-1" @click="_store" :loading="loading" rounded>
              Salvar <v-icon dark class="ml-2">mdi-content-save</v-icon>
          </v-btn>
      </v-col>
    </v-row>

   <v-dialog v-model="dialog.show" max-width="350">
      <v-card>
          <v-card-title>
              <div class="mx-auto"> {{ dialog.message }} </div>
          </v-card-title>

          <v-card-text class="text-center py-5" >
            <v-progress-circular v-if="loading" :width="7" color="blue" :size="70" indeterminate></v-progress-circular>
            <v-icon v-else :size="70" :color="dialog.status === 'success' ? 'green' : dialog.status === 'error' ? 'red' : '' ">
              {{ dialog.status === 'success' ? 'mdi-check' : dialog.status === 'error' ? 'mdi-alert' : '' }}
            </v-icon>
          </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import { format, parseISO } from 'date-fns'

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: 'Custos/Despesas' }
  },
  data: () => ({
    menu_date: false,
    menu_time: false,
    loading: false,
    dialog: {
      show: false,
      message: '',
      status: null
    },
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
  },
  mounted(){
    this._start();
  },
  methods: {
    async _start(){
      if(this.$route.params.id){
        await this._load();
      }

      await this._loadCategories();
    },
    _showModal(message, status = ''){
      this.dialog.message = message;
      this.dialog.status = status;
      this.dialog.show = true;
    },
    _hideModal(){
      this.dialog.show = false;
    },
    async _loadCategories(){
      this.loading = true;
      await axios.get(`api/category?type=expense`).then(response => {
        if(response.data.success){
          return this.categories = response.data.data;
        }
        this._showModal('Error ao carregar categorias', 'error');
      });
      this.loading = false;
    },
    async _load(){
      let id = this.$route.params.id;

      this.loading = true;

      await axios.get(`api/expense/${id}`).then(response => {
        if(response.data.success){
          return this.expense = response.data.data;
        }

        this._showModal('Error ao carregar dados do custo/despesa', 'error');
        setTimeout(() => this.$router.push({ name: 'expense.index' }), 1500);
      });

      this.loading = false;
    },
    async _store(){
      for (const field in this.errors) {
        if(!this.expense[field])
          return this.errors[field] = true;
      }

      let id = this.$route.params.id;

      this.loading = true;
      this._showModal(id ? 'Atualizando...' : 'Salvando...');

      const response = !id ? await axios.post('api/expense', this.expense) : await axios.put(`api/expense/${id}`, this.expense);

      this.loading = false;

      if(response.data.success){
        this._showModal('Custo/Despesa salvo com sucesso', 'success');
        return setTimeout(() => this.$router.push({ name: 'expense.index' }), 1500);
      }

      this._showModal('Error ao salvar custo/despesa.' , 'error');
    },
  }

}
</script>
