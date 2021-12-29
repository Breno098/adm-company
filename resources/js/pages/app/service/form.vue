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
            v-if="(!idByRoute && $role.service.add()) || (idByRoute && $role.service.update()) "
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
      </v-card>

      <v-card>
        <v-card-text>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                label="NOME"
                outlined
                dense
                v-model="service.name"
                :loading="loading"
                :rules="[rules.required]"
                :error="errors.name && !service.name"
                @input="service.name = service.name.toUpperCase()"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                v-model="service.categories"
                vali
                :items="categories"
                item-text="name"
                item-value="id"
                label="CATEGORIAS"
                outlined
                dense
                :loading="loading"
                multiple
                no-data-text="Nenhuma categoria encontrada"
              ></v-select>
            </v-col>

            <v-col cols="12">
              <v-textarea
                label="DESCRIÇÃO"
                outlined
                dense
                v-model="service.description"
                :loading="loading"
                hint="Detalhes"
                @input="service.description = service.description.toUpperCase()"
              ></v-textarea>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                type="number"
                prefix="R$"
                label="VALOR"
                outlined
                dense
                v-model="service.default_value"
                :loading="loading"
                hint="Valor padrão"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                type="number"
                prefix="R$"
                label="CUSTO"
                outlined
                dense
                v-model="service.cost"
                :loading="loading"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                type="number"
                prefix="R$"
                label="LUCRO"
                outlined
                dense
                :value="(service.default_value - service.cost).toFixed(2)"
                :loading="loading"
                color="black"
                readonly
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                label="DIAS GARANTIA"
                type="number"
                outlined
                dense
                v-model="service.warranty_days"
                :loading="loading"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="10">
              <v-textarea
                label="CONDIÇÃO DE GARANTIA"
                outlined
                dense
                v-model="service.warranty_conditions"
                :loading="loading"
                hint="Termos de garantia"
                @input="service.warranty_conditions = service.warranty_conditions.toUpperCase()"
              ></v-textarea>
            </v-col>
          </v-row>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            v-if="(!idByRoute && $role.service.add()) || (idByRoute && $role.service.update()) "
            color="btnPrimary"
            @click="_store"
            :loading="loading"
            rounded
            dark
          >
            Salvar <v-icon dark class="ml-2">mdi-content-save</v-icon>
          </v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
      </v-card>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: this.titlePage }
  },
  data: () => ({
    loading: false,
    menuBirthDate: false,
    errors: {
      name: false,
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    service: {
      description: null,
      name: null,
      cost: null,
      default_value: null,
      type: null,
      warranty_conditions: null,
      warranty_days: null,
      bar_code: null,
      categories: [],
    },
    categories: [],
  }),
  computed: {
    titlePage(){
      return this.$route.params.id ? 'Serviço' : 'Adicionar Serviço';
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
    async _load(){
      this.loading = true;
      await axios.get(`/api/service/${this.idByRoute}`).then(response => {
        if(response.data.success){
          return this.service = response.data.data;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar dados serviço' })
        this.$refs.fireDialog.hide(1500);
      });
      this.loading = false;
    },
    async _loadCategories(){
      this.loading = true;
      await axios.get(`/api/category?type=service`).then(response => {
        if(response.data.success){
          return this.categories = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar categorias' })
      });
      this.loading = false;
    },
    async _store(){
      for (const field in this.errors) {
        if(!this.service[field])
          return this.errors[field] = true;
      }

      this.loading = true;
      this.$refs.fireDialog.loading({ title: this.idByRoute ? 'Atualizando...' : 'Salvando...' })

      const response = !this.idByRoute ? await axios.post('/api/service', this.service) : await axios.put(`/api/service/${this.idByRoute}`, this.service);

      this.loading = false;

      if(response.data.success){
        this.$refs.fireDialog.success({ title: 'Serviço salvo com sucesso' })
        return setTimeout(() => this.$router.push({ name: 'service.index' }), 1500);
      }

      this.$refs.fireDialog.error({ title: 'Error aos salvar serviço' })
    },
  }

}
</script>
