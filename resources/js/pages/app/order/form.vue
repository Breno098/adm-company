<template>
  <div>
    <v-row>
      <v-col cols="12">
        <div class="text-h6 blue--text"> Order de Serviço </div>
        <v-divider color="grey"/>
      </v-col>
    </v-row>

    <v-tabs v-model="tab">
      <v-tabs-slider color="blue"></v-tabs-slider>
      <v-tab>Informações <v-icon class="ml-2">mdi-information</v-icon></v-tab>
      <v-tab>Produtos e Serviços <v-icon class="ml-2">mdi-wrench</v-icon></v-tab>
      <v-tab>Garantias <v-icon class="ml-2">mdi-format-align-center</v-icon></v-tab>
      <v-tab>Pagamento <v-icon class="ml-2">mdi-cash</v-icon></v-tab>
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
              v-on:change="_loadAddresses"
              :loading="loadingClients"
              outlined
              dense
              :rules="[rules.required]"
              :error="errors.client"
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
              :loading="loadingStatuses"
            ></v-select>
          </v-col>

          <v-col cols="12">
            <v-autocomplete
              v-model="order.address_id"
              :items="addresses"
              item-value="id"
              label="ENDEREÇO"
              :loading="loadingAddresses"
              outlined
              dense
            >
              <template slot="selection" slot-scope="data">
                {{ data.item.street }} {{ data.item.number ? `n° ${data.item.number}` : '' }}, {{ data.item.district }} - {{ data.item.city }}
              </template>
              <template slot="item" slot-scope="data">
                 {{ data.item.street }} {{ data.item.number ? `n° ${data.item.number}` : '' }}, {{ data.item.district }} - {{ data.item.city }}
              </template>
            </v-autocomplete>
          </v-col>

          <v-col cols="12">
            <v-textarea
              label="DESCRIÇÃO"
              outlined
              dense
              v-model="order.description"
              :loading="loading"
              hint="Descrição da order de serviço, orçamento ou nota fiscal."
              placeholder="Descrição e observações formais (visível ao cliente)."
            ></v-textarea>
          </v-col>

          <v-col cols="12">
            <v-textarea
              label="COMENTARIOS INTERNOS"
              outlined
              dense
              v-model="order.comments"
              :loading="loading"
              placeholder="Comentários e observaçõs internas (não visível ao cliente)."
              hint="Comentários e observações internas (não visível ao cliente)."
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
        </v-row>
        
        <v-row>
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
                  v-model="product.id"
                  :items="products"
                  label="PRODUTO"
                  outlined
                  dense
                  :loading="loadingProducts"
                  item-value="id"
                >
                  <template v-slot:selection="{ item }">
                    <span>{{ item.name }} {{item.default_value ? `(R$ ${item.default_value.toFixed(2)})` : null}} </span>
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
                  :rules="[rules.required]"
                  :error="errors.products[index]"
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

        <v-row>
          <v-col cols="12">
            <div class="text-h6 blue--text"> Serviços </div>
            <v-divider color="grey"/>
          </v-col>

          <v-col cols="12" v-for="(service, index) in order.services" :key="service.id">
            <v-row>
              <v-col cols="12" class="d-flex flex-row justify-end">
                <v-btn color="red" @click="order.services.splice(index, 1);" :loading="loading" small>
                  <v-icon color="red darken-4">mdi-delete</v-icon>
                </v-btn>
              </v-col>

              <v-col cols="12" md="6">
                <v-select
                  v-model="service.id"
                  :items="services"
                  label="PRODUTO"
                  outlined
                  dense
                  :loading="loadingServices"
                  item-value="id"
                >
                  <template v-slot:selection="{ item }">
                    <span>{{ item.name }} {{item.default_value ? `(R$ ${item.default_value.toFixed(2)})` : null}} </span>
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
                  :rules="[rules.required]"
                  :error="errors.services[index]"
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
              Adicionar produto <v-icon>mdi-plus</v-icon>
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

    <v-row>
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
    return { title: 'Order de Serviço' }
  },
  data: () => ({
    tab: null,
    loading: false,
    loadingClients: false,
    loadingStatuses: false,
    loadingProducts: false,
    loadingServices: false,
    loadingAddresses: false,
    dialog: {
      show: false,
      message: '',
      status: null
    },
    errors: {
      client: false,
      products: [],
      services: []
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    order: {
      type : null,
      description : null,
      comments: null,
      amount : null,
      amount_paid: null,
      execution_date : null,
      discount_amount : null,
      warranty_days : null,
      warranty_conditions : null,
      installments: null,

      products: [{}],
      services: [{}],

      client_id: null,
      status_id: null,
      address_id: null
    },
    clients: [],
    statuses: [],
    products: [],
    services: [],
    addresses: [],
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
      if(this.$route.params.id){
        await this._load();
      }

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
    async _load(){
      let id = this.$route.params.id;

      this.loading = true;
      await axios.get(`api/order/${id}`).then(response => {
        console.log(response.data);
        if(response.data.success){
          return this.order = response.data.data;
        }

        this._modal('Error ao carregar order de serviço', 'error');
        setTimeout(() => this.$router.push({ name: 'order.index' }), 1500);
      });

      this.loading = false;
    },
    async _loadClients(){
      this.loadingClients = true;
      await axios.get(`api/client`).then(response => {
        if(response.data.success){
          this.clients = response.data.data;
          
          if(this.$route.params.id){
            this._loadAddresses();
          }
          return;
        }
        this._modal('Error ao carregar clientes', 'error');
      });
      this.loadingClients = false;
    },
    async _loadAddresses(){
      this.loadingAddresses = true;
      let client_id = await this.order.client_id;

      await axios.get(`api/address/client/${client_id}`).then(response => {
        console.log(response.data);

        if(response.data.success){
          return this.addresses = response.data.data;
        }
        this._modal('Error ao carregar endereços', 'error');
      });
      this.loadingAddresses = false;
    },
    async _loadStatuses(){
      this.loadingStatuses = true;
      await axios.get(`api/status/type/order`).then(response => {
        if(response.data.success){
          return this.statuses = response.data.data;
        }
        this._modal('Error ao carregar status', 'error');
      });
      this.loadingStatuses = false;
    },
    async _loadProducts(){
      this.loadingProducts = true;
      await axios.get(`api/item/type/product`).then(response => {
        if(response.data.success){
          return this.products = response.data.data;
        }
        this._modal('Error ao carregar produtos', 'error');
      });
      this.loadingProducts = false;
    },
    async _loadServices(){
      this.loadingServices = true;
      await axios.get(`api/item/type/service`).then(response => {
        if(response.data.success){
          return this.services = response.data.data;
        }
        this._modal('Error ao carregar serviços', 'error');
      });
      this.loadingServices = false;
    },
    async _store(){
      this.tab = null;

      /* Validations */
      if(!this.order.client_id){
        return this.errors.client = true;
      }
      
      for (const key in this.order.products) {
        const product = this.order.products[key];

        if(product.id && !product.value){
          return this.errors.products[key] = true;
        }

        this.errors.products[key] = false;
      }

      for (const key in this.order.services) {
        const service = this.order.services[key];

        if(service.id && !service.value){
          return this.errors.services[key] = true;
        }

        this.errors.services[key] = false;
      }
      /* End Validations */

      let id = this.$route.params.id;

      this.loading = true;
      this.dialog.show = true;
      this.dialog.message = id ? 'Atualizando...' : 'Salvando...';

      this.order.amount = this.valueTotalWithDiscont;

      let response = null;

      if(!id){
        response = await axios.post('api/order', this.order)
      } else {
        response = await axios.put(`api/order/${id}`, this.order)
      }

      this.loading = false;

      if(response.data.success){
        this._modal('Pedido salvo com sucesso.', 'success');
        return setTimeout(() => this.$router.push({ name: 'order.index' }), 1500);
      }

      this._modal('Error ao salvar orçamento. ' , 'error');
    },
  }

}
</script>
