<template>
  <div>
     <v-tabs v-model="tab">
      <v-tabs-slider color="blue"></v-tabs-slider>
      <v-tab>Informações <v-icon class="ml-2">mdi-information</v-icon></v-tab>
      <v-tab>Produtos e Serviços    <v-icon class="ml-2">mdi-wrench</v-icon></v-tab>
      <v-tab>Garantias    <v-icon class="ml-2">mdi-format-align-center</v-icon></v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab" class="pt-5">
      <!-- Informações -->
      <v-tab-item>
        <v-row>
          <v-col cols="12" md="6">
            <v-autocomplete
              v-model="order.client_id"
              :items="clients"
              item-text="name"
              item-value="id"
              label="CLIENTE"
              :loading="loading"
              outlined
              dense
            ></v-autocomplete>
          </v-col>

          <v-col cols="12" md="6">
            <v-select
              v-model="order.status_id"
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

          <v-col cols="12">
            <v-textarea
              label="DESCRIÇÃO"
              outlined
              dense
              v-model="order.description"
              :loading="loading"
              hint="Detalhes do orçamento"
            ></v-textarea>
          </v-col>

          <v-col cols="12" md="4">
            <v-text-field
              outlined
              prefix="R$"
              type="number"
              :value="valueTotal"
              dense
              label="VALOR TOTAL"
              :loading="loading"
              readonly
              color="black"
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="4">
            <v-text-field
              prefix="R$"
              label="VALOR DESCONTO"
              outlined
              dense
              v-model="order.discount_amount"
              :loading="loading"
              hint="Valor do desconto"
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="4">
            <v-text-field
              outlined
              prefix="R$"
              type="number"
              :value="valueTotalWithDiscont"
              dense
              label="VALOR FINAL"
              :loading="loading"
              readonly
              color="black"
            ></v-text-field>
          </v-col>
        </v-row>
      </v-tab-item>

      <!-- Produtos/Serviços -->
      <v-tab-item>
        <v-row>
          <v-col cols="12" md="6" offset-md="6">
            <v-text-field
              outlined
              prefix="R$"
              type="number"
              :value="valueTotal"
              dense
              label="VALOR TOTAL"
              :loading="loading"
              readonly
              color="black"
            ></v-text-field>
          </v-col>

          <v-col cols="12">
            <div class="text-h6 blue--text"> Produtos </div>
            <v-divider color="grey"/>
          </v-col>

          <v-col cols="12" v-for="(product, index) in order.products" :key="product.id">
            <v-row>
              <v-col cols="12" class="d-flex flex-row justify-end">
                <v-btn color="red" @click="order.products.splice(index, 1);" :loading="loading" small>
                  <v-icon color="red darken-4">mdi-delete</v-icon>
                </v-btn>
              </v-col>

              <v-col cols="12" md="6">
                <v-select
                  v-model="product.item"
                  :items="products"
                  label="PRODUTO"
                  outlined
                  dense
                  :loading="loading"
                >
                  <template v-slot:selection="{ item }">
                    <span>{{ item.name }}</span>
                  </template>
                  <template v-slot:item="{ item }">
                    <span>{{ item.name }} {{item.default_value ? `(R$ ${item.default_value.toFixed(2)})` : null}} </span>
                  </template>
                </v-select>
              </v-col>

              <v-col cols="12" md="2">
                <v-text-field
                  label="QUANTIDADE"
                  outlined
                  type="number"
                  value="1"
                  dense
                  v-model="product.quantity"
                  :loading="loading"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="2">
                <v-text-field
                  prefix="R$"
                  type="number"
                  label="VALOR"
                  outlined
                  dense
                  v-model="product.value"
                  :loading="loading"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="2">
                <v-text-field
                  prefix="R$"
                  type="number"
                  label="VALOR TOTAL"
                  readonly
                  outlined
                  dense
                  :value="((product.value * product.quantity) || 0).toFixed(2)"
                  :loading="loading"
                  color="black"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-divider color="grey" class="mx-5" v-if="(index + 1) < order.products.length"/>
          </v-col>

          <v-col cols="12" class="d-flex flex-row justify-end">
            <v-btn color="green" @click="order.products.push({})" :loading="loading" small>
              Adicionar produto <v-icon>mdi-plus</v-icon>
            </v-btn>
          </v-col>
        </v-row>

        <v-col cols="12">
          <div class="text-h6 blue--text"> Serviços </div>
          <v-divider color="black"/>
        </v-col>

        <v-row>
          <v-col cols="12" v-for="(service, index) in order.services" :key="service.id">
            <v-row>
              <v-col cols="12" class="d-flex flex-row justify-end">
                <v-btn color="red" @click="order.services.splice(index, 1);" :loading="loading" small>
                  <v-icon color="red darken-4">mdi-delete</v-icon>
                </v-btn>
              </v-col>

              <v-col cols="12" md="6">
                <v-select
                  v-model="service.item"
                  :items="services"
                  label="SERVIÇO"
                  outlined
                  dense
                  :loading="loading"
                >
                  <template v-slot:selection="{ item }">
                    <span>{{ item.name }}</span>
                  </template>
                  <template v-slot:item="{ item }">
                    <span>{{ item.name }} {{item.default_value ? `(R$ ${item.default_value.toFixed(2)})` : null}} </span>
                  </template>
                </v-select>
              </v-col>

              <v-col cols="12" md="2">
                <v-text-field
                  label="QUANTIDADE"
                  outlined
                  type="number"
                  value="1"
                  dense
                  v-model="service.quantity"
                  :loading="loading"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="2">
                <v-text-field
                  prefix="R$"
                  type="number"
                  label="VALOR"
                  outlined
                  dense
                  v-model="service.value"
                  :loading="loading"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="2">
                <v-text-field
                  prefix="R$"
                  type="number"
                  label="VALOR TOTAL"
                  readonly
                  outlined
                  dense
                  :value="((service.value * service.quantity) || 0).toFixed(2)"
                  :loading="loading"
                  color="black"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-divider color="grey" class="mx-5" v-if="(index + 1) < order.services.length"/>
          </v-col>

          <v-col cols="12" class="d-flex flex-row justify-end">
            <v-btn color="green" @click="order.services.push({})" :loading="loading" small>
              Adicionar serviço <v-icon>mdi-plus</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </v-tab-item>

      <!-- Garantia -->
      <v-tab-item>
        <v-row>
          <v-col cols="12" md="2">
            <v-text-field
              label="DIAS GARANTIA"
              type="number"
              outlined
              dense
              v-model="order.warranty_days"
              :loading="loading"
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="10">
            <v-textarea
              label="CONDIÇÃO DE GARANTIA"
              outlined
              dense
              v-model="order.warranty_conditions"
              :loading="loading"
              hint="Termos de garantia do orçamento"
            ></v-textarea>
          </v-col>
        </v-row>
      </v-tab-item>
    </v-tabs-items>

    <!-- <v-row>
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
    </v-dialog> -->
  </div>
</template>

<script>
import axios from 'axios';

export default {
  metaInfo () {
    return { title: 'Orçamento' }
  },
  data: () => ({
    tab: null,
    loading: false,
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
    order: {
      type : 'budget',
      description : null,
      total_value : null,
      execution_date : null,
      discount_amount : null,
      warranty_days : null,
      warranty_conditions : null,

      products: [{}],
      services: [{}],

      client_id: null,
      status_id: null
    },
    clients: [],
    statuses: [],
    products: [],
    services: [],
  }),
  computed: {
    valueTotal(){
      let valueTotal = 0;
      this.order.products.forEach(product => valueTotal += product.quantity && product.value ? product.value * product.quantity : 0);
      this.order.services.forEach(service => valueTotal += service.quantity && service.value ? service.value * service.quantity : 0);
      return valueTotal.toFixed(2);
    },
    valueTotalWithDiscont(){
      let valueTotal = 0;
      this.order.products.forEach(product => valueTotal += product.quantity && product.value ? product.value * product.quantity : 0);
      this.order.services.forEach(service => valueTotal += service.quantity && service.value ? service.value * service.quantity : 0);
      valueTotal -= this.order.discount_amount;
      return valueTotal.toFixed(2);
    }
  },
  mounted(){
    this._start();
  },
  methods: {
    async _start(){
      await this._loadClients();
      await this._loadStatuses();
      await this._loadProducts();
      await this._loadServices();
    },
    _modal(message, status){
      this.dialog.message = message;
      this.dialog.status = status;
      this.dialog.show = true;
    },
    async _loadClients(){
      this.loading = true;
      await axios.get(`api/client`).then(response => {
        if(response.data.success){
          return this.clients = response.data.data;
        }
        this._modal('Error ao carregar clientes', 'error');
      });
      this.loading = false;
    },
    async _loadStatuses(){
      this.loading = true;
      await axios.get(`api/status/type/order`).then(response => {
        if(response.data.success){
          return this.statuses = response.data.data;
        }
        this._modal('Error ao carregar status', 'error');
      });
      this.loading = false;
    },
    async _loadProducts(){
      this.loading = true;
      await axios.get(`api/item/type/product`).then(response => {
        if(response.data.success){
          return this.products = response.data.data;
        }
        this._modal('Error ao carregar produtos', 'error');
      });
      this.loading = false;
    },
    async _loadServices(){
      this.loading = true;
      await axios.get(`api/item/type/service`).then(response => {
        if(response.data.success){
          return this.services = response.data.data;
        }
        this._modal('Error ao carregar serviços', 'error');
      });
      this.loading = false;
    },
    async _store(){
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
        this._modal('Orçamento salvo com sucesso.', 'success');
        return setTimeout(() => this.$router.push({ name: 'home' }), 1500);
      }

      this._modal('Error ao salvar orçamento. ' , 'error');
    },
  }

}
</script>
