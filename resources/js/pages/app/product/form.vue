<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-row>
      <v-col cols="12">
        <v-card elevation="0">
          <v-toolbar elevation="0" class="mb-2">
            <v-toolbar-title> {{ titlePage }} </v-toolbar-title>
            <v-progress-linear
              color="blue"
              indeterminate
              height="4"
              bottom
              absolute
              :active="loading"
            ></v-progress-linear>

            <v-spacer></v-spacer>

            <v-btn
              v-if="(!idByRoute && $role.product.add()) || (idByRoute && $role.product.update()) "  
              color="green" 
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
            <v-col cols="12" md="6" offset-md="6">
              <v-select
                v-model="product.status_id"
                vali
                :items="statuses"
                item-text="name"
                item-value="id"
                label="STATUS"
                outlined
                dense
                :loading="loading"
                no-data-text="Nenhum status encontrada"
              ></v-select>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="NOME"
                outlined
                dense
                v-model="product.name"
                :loading="loading"
                :rules="[rules.required]"
                :error="errors.name && !product.name"
                @input="product.name = product.name.toUpperCase()"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                v-model="product.categories"
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

            <v-col cols="12" md="6">
              <v-text-field
                label="MARCA"
                outlined
                dense
                v-model="product.brand"
                :loading="loading"
                @input="product.brand = product.brand.toUpperCase()"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                v-model="product.unit_measure"
                :items="unit_measures"
                item-text="label"
                item-value="value"
                label="UN. MEDIDA"
                outlined
                dense
                :loading="loading"
              ></v-select>
            </v-col>

            <v-col cols="12">
              <v-textarea
                label="DESCRIÇÃO"
                outlined
                dense
                v-model="product.description"
                :loading="loading"
                hint="Detalhes do produto"
                @input="product.description = product.description.toUpperCase()"
              ></v-textarea>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                type="number"
                prefix="R$"
                label="VALOR"
                outlined
                dense
                v-model="product.default_value"
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
                v-model="product.cost"
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
                :value="(product.default_value - product.cost).toFixed(2)"
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
                v-model="product.warranty_days"
                :loading="loading"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="10">
              <v-textarea
                label="CONDIÇÃO DE GARANTIA"
                outlined
                dense
                v-model="product.warranty_conditions"
                :loading="loading"
                hint="Termos de garantia do produto/product"
                @input="product.warranty_conditions = product.warranty_conditions.toUpperCase()"
              ></v-textarea>
            </v-col>
          </v-row>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn 
              v-if="(!idByRoute && $role.product.add()) || (idByRoute && $role.product.update()) "  
              color="green" 
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
      </v-col>
    </v-row>
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
    product: {
      description: null,
      name: null,
      brand: null,
      cost: null,
      default_value: null,
      type: null,
      unit_measure: null,
      warranty_conditions: null,
      warranty_days: null,
      bar_code: null,
      categories: [],
      status_id: null
    },
    categories: [],
    statuses: [],
    unit_measures: [{
      label: 'cm (centimetros)',
      value: 'cm'
    }, {
      label: 'cm² (centimetro quadrado)',
      value: 'cm²'
    }, {
      label: 'cm³ (centimetro cúbico)',
      value: 'cm³'
    }, {
      label: 'un. (unidade)',
      value: 'un'
    }, {
      label: 'l (litro)',
      value: 'l'
    } , {
      label: 'mg (miligrama)',
      value: 'mg'
    }],
  }),
  computed: {
    titlePage(){
      return this.$route.params.id ? 'Produto' : 'Adicionar Produto';
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
      await this._loadStatuses();
    },
    async _load(){
      this.loading = true;
      await axios.get(`api/product/${this.idByRoute}`).then(response => {
        if(response.data.success){
          return this.product = response.data.data;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar dados produto' })
        this.$refs.fireDialog.hide(1500);
      });
      this.loading = false;
    },
    async _loadCategories(){
      this.loading = true;
      await axios.get(`api/category?type=product`).then(response => {
        if(response.data.success){
          return this.categories = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar categorias' })
      });
      this.loading = false;
    },
    async _loadStatuses(){
      this.loading = true;
      await axios.get(`api/status?type=product`).then(response => {
        if(response.data.success){
          return this.statuses = response.data.data;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar status' })
      });
      this.loading = false;
    },
    async _store(){
      for (const field in this.errors) {
        if(!this.product[field])
          return this.errors[field] = true;
      }

      this.loading = true;
      this.$refs.fireDialog.loading({ title: this.idByRoute ? 'Atualizando...' : 'Salvando...' })

      const response = !this.idByRoute ? await axios.post('api/product', this.product) : await axios.put(`api/product/${this.idByRoute}`, this.product);

      this.loading = false;

      if(response.data.success){
        this.$refs.fireDialog.success({ title: 'Produto salvo com sucesso' })
        return setTimeout(() => this.$router.push({ name: 'product.index' }), 1500);
      }

      this.$refs.fireDialog.error({ title: 'Error aos salvar produto' })
    },
  }

}
</script>
