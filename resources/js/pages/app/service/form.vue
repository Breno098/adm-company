<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <p class="font-weight-bold mb-5 text-h5">
      <v-icon color="primary">mdi-wrench</v-icon>
      {{ titlePage }}
    </p>

    <v-row class="mb-2">
      <v-col cols="6" md="10">
        <v-btn
          color="btn-primary"
          small
          text
          @click="$router.go(-1)"
        >
          Voltar <v-icon>mdi-chevron-double-left</v-icon>
        </v-btn>
      </v-col>

      <v-col cols="6" md="2">
        <v-btn
          color="btn-primary"
          class="rounded-lg"
          block
          small
          dark
          @click="_store"
          v-if="canSave"
          :loading="loading"
        >
          Salvar <v-icon small class="ml-2">mdi-content-save</v-icon>
        </v-btn>
      </v-col>
    </v-row>

    <v-card>
      <v-card-text>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field
              label="NOME"
              filled
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
              filled
              dense
              :loading="loading"
              multiple
              no-data-text="Nenhuma categoria encontrada"
            ></v-select>
          </v-col>

          <v-col cols="12">
            <v-textarea
              label="DESCRIÇÃO"
              filled
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
              filled
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
              filled
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
              filled
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
              filled
              dense
              v-model="service.warranty_days"
              :loading="loading"
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="10">
            <v-textarea
              label="CONDIÇÃO DE GARANTIA"
              filled
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
        <v-btn
          color="btn-primary"
          class="rounded-lg"
          small
          dark
          @click="_store"
          v-if="canSave"
          :loading="loading"
        >
          Salvar <v-icon small class="ml-2">mdi-content-save</v-icon>
        </v-btn>
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
    },
    canSave(){
      return this.$can('service_add') && !this.idByRoute || this.$can('service_update') && this.idByRoute;
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

      try {
        const response = !this.idByRoute ? await axios.post('/api/service', this.service) : await axios.put(`/api/service/${this.idByRoute}`, this.service);

        this.$refs.fireDialog.success({ title: 'Serviço salvo com sucesso' })
        setTimeout(() => this.$router.push({ name: 'service.index' }), 1500);
      } catch (error) {
        this.$refs.fireDialog.error({ title: 'Error aos salvar serviço' })
      } finally {
        this.loading = false;
      }
    },
  }

}
</script>
