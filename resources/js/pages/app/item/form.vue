<template>
  <div>
    <v-row>
      <v-col cols="12" md="6" offset-md="6">
        <v-select
          v-model="item.status_id"
          vali
          :items="statuses"
          item-text="name"
          item-value="id"
          label="STATUS"
          outlined
          dense
          :loading="loading"
        ></v-select>
      </v-col>

      <v-col cols="12" md="6">
        <v-text-field
          label="NOME"
          outlined
          dense
          v-model="item.name"
          :loading="loading"
          :rules="[rules.required]"
          :error="errors.name && !item.name"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="6">
        <v-select
          v-model="item.type"
          vali
          :items="item_types"
          item-text="label"
          item-value="value"
          label="TIPO"
          outlined
          dense
          :loading="loading"
          :rules="[rules.required]"
          :error="errors.type && !item.type"
        ></v-select>
      </v-col>

      <v-col cols="12" md="4">
        <v-select
          v-model="item.categories"
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

      <v-col cols="12" md="4">
        <v-text-field
          label="MARCA"
          outlined
          dense
          v-model="item.brand"
          :loading="loading"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="4">
        <v-select
          v-model="item.unit_measure"
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
          v-model="item.description"
          :loading="loading"
          hint="Detalhes do produto/item"
        ></v-textarea>
      </v-col>

      <v-col cols="12" md="4">
        <v-text-field
          prefix="R$"
          label="VALOR"
          outlined
          dense
          v-model="item.default_value"
          :loading="loading"
          hint="Valor padrão do item/produto"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="4">
        <v-text-field
          type="number"
          prefix="R$"
          label="CUSTO"
          outlined
          dense
          v-model="item.cost"
          :loading="loading"
          hint="Custo do item/produto"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="4">
        <v-text-field
          type="number"
          prefix="R$"
          label="LUCRO"
          outlined
          dense
          :value="(item.default_value - item.cost).toFixed(2)"
          :loading="loading"
          color="black"
          readonly
        ></v-text-field>
      </v-col>

      <v-col cols="12">
        <v-text-field
          label="CÓDIGO DE BARRA"
          outlined
          dense
          v-model="item.bar_code"
          :loading="loading"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="2">
        <v-text-field
          label="DIAS GARANTIA"
          type="number"
          outlined
          dense
          v-model="item.warranty_days"
          :loading="loading"
        ></v-text-field>
      </v-col>

      <v-col cols="12" md="10">
        <v-textarea
          label="CONDIÇÃO DE GARANTIA"
          outlined
          dense
          v-model="item.warranty_conditions"
          :loading="loading"
          hint="Termos de garantia do produto/item"
        ></v-textarea>
      </v-col>

      <v-col cols="12">
          <v-btn color="green darken-1" @click="_store" :loading="loading">
              Salvar &nbsp; <v-icon dark>mdi-content-save</v-icon>
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
            <v-icon v-else :size="70" :color="dialog.status === 'success' ? 'green' : 'red' ">
              {{ dialog.status === 'success' ? 'mdi-check' : 'mdi-alert' }}
            </v-icon>
          </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  metaInfo () {
    return { title: 'Produtos/Serviços' }
  },
  data: () => ({
    loading: false,
    menuBirthDate: false,
    dialog: {
      show: false,
      message: '',
      status: null
    },
    errors: {
      name: false,
      type: false
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    item: {
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
    item_types: [{
      label: 'Produto',
      value: 'product'
    }, {
      label: 'Serviço',
      value: 'service'
    }],
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
      await this._loadStatuses();
    },
    _modal(message, status){
      this.dialog.message = message;
      this.dialog.status = status;
      this.dialog.show = true;
    },
    async _load(){
      let id = this.$route.params.id;

      this.loading = true;

      await axios.get(`api/item/${id}`).then(response => {
        if(response.data.success){
          return this.item = response.data.data;
        }

        this._modal('Error ao carregar dados do item', 'error');
        setTimeout(() => this.$router.push({ name: 'item.index' }), 1500);
      });

      this.loading = false;
    },
    async _loadCategories(){
      this.loading = true;
      await axios.get(`api/category`).then(response => {
        if(response.data.success){
          return this.categories = response.data.data;
        }

        this._modal('Error ao carregar categorias', 'error');
      });
      this.loading = false;
    },
    async _loadStatuses(){
      this.loading = true;
      await axios.get(`api/status/type_item`).then(response => {
        if(response.data.success){
          return this.statuses = response.data.data;
        }

        this._modal('Error ao carregar status', 'error');
      });
      this.loading = false;
    },
    async _store(){
      if(!this.item.name){
        return this.errors.name = true;
      }

      if(!this.item.type){
        return this.errors.type = true;
      }

      let id = this.$route.params.id;

      this.loading = true;
      this.dialog.show = true;
      this.dialog.message = id ? 'Atualizando...' : 'Salvando...';

      let response = null;

      if(!id){
        response = await axios.post('api/item', this.item)
      } else {
        response = await axios.put(`api/item/${id}`, this.item)
      }

      this.loading = false;

      if(response.data.success){
        this._modal('Item salvo com sucesso', 'success');
        return setTimeout(() => this.$router.push({ name: 'item.index' }), 1500);
      }

      this._modal('Error ao salvar item. ' , 'error');
    },
  }

}
</script>
