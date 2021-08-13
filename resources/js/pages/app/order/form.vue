<template>
  <div>
    <v-row>
      <v-col cols="12" md="6">
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="blue" @click="$router.go(-1)" v-bind="attrs" v-on="on" class="mx-3">
              Voltar <v-icon dark>mdi-arrow-left-bold</v-icon>
            </v-btn>
          </template>
          <span>Voltar</span>
        </v-tooltip>
      </v-col>
      <v-col cols="12" md="6" class="d-flex flex-row justify-end">
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="green darken-1" @click="_store(true)" :loading="loading" v-bind="attrs" v-on="on" class="mx-3">
              <v-icon dark>mdi-content-save</v-icon>
            </v-btn>
          </template>
          <span>Salvar</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="grey lighten-2" @click="_generateDoc('budget')" :loading="loading" v-bind="attrs" v-on="on" class="mx-3">
              <v-icon>mdi-file-document</v-icon>
            </v-btn>
          </template>
          <span>Gerar Orçamento</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="grey lighten-2" @click="_generateDoc('serviceorder')" :loading="loading" v-bind="attrs" v-on="on" class="mx-3">
              <v-icon>mdi-file-export</v-icon>
            </v-btn>
          </template>
          <span>Gerar Ordem de Serviço</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="orange" @click="_generateAppointment" :loading="loading" v-bind="attrs" v-on="on" class="mx-3">
              <v-icon dark>mdi-calendar-today</v-icon>
            </v-btn>
          </template>
          <span>Agendar Compromisso</span>
        </v-tooltip>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <div class="text-h6 blue--text"> Order de Serviço {{ order ? order.id : '' }} </div>
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

          <v-col cols="12" md="6">
              <v-menu
                  v-model="menu_technical_visit_date"
                  :close-on-content-click="false"
                  max-width="290"
                  transition="scale-transition"
                  offset-y
              >
              <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                      append-icon="mdi-calendar"
                      :value="technicalVisitDateFormat"
                      clearable
                      label="DATA VISITA TÉCNICA"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      @click:clear="order.technical_visit_date = null"
                      outlined
                      dense
                  ></v-text-field>
              </template>
              <v-date-picker
                  v-model="order.technical_visit_date"
                  @change="menu_technical_visit_date = false"
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
                  :return-value.sync="order.technical_visit_hour"
                  transition="scale-transition"
                  offset-y
                  max-width="290px"
                  min-width="290px"
              >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="order.technical_visit_hour"
                        label="HORÁRIO VISITA TÉCNICA"
                        prepend-icon="mdi-clock-time-four-outline"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        dense
                        outlined
                    ></v-text-field>
                  </template>
                  <v-time-picker
                      v-if="menu_time"
                      v-model="order.technical_visit_hour"
                      @click:minute="$refs.menu_time.save(order.technical_visit_hour)"
                      format="24hr"
                  ></v-time-picker>
              </v-menu>
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
                  label="SERVIÇO"
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

      <!-- Pagamentos  -->
      <v-tab-item>
        <v-row>
          <v-col cols="12" v-for="(payment, index) in order.payments" :key="index">
            <v-row>
              <v-col cols="12" class="d-flex flex-row justify-end">
                <v-btn color="red" @click="order.payments.splice(index, 1);" :loading="loading" small>
                  <v-icon color="red darken-4">mdi-delete</v-icon>
                </v-btn>
              </v-col>

              <v-col cols="12" md="6">
                <v-select
                  :items="payments"
                  label="Pagamento"
                  outlined
                  dense
                  :loading="loadingPayment"
                  item-value="id"
                  item-text="name"
                  v-model="payment.id"
                >
                </v-select>
              </v-col>
              <v-col cols="12" md="5">
                <v-text-field
                  prefix="R$"
                  type="number"
                  label="VALOR"
                  outlined
                  dense
                  :loading="loadingPayment"
                  v-model="payment.value"
                ></v-text-field>
              </v-col>
              <v-col cols="1">
                <v-checkbox
                  label="Total"
                  color="blue"
                  class="my-auto"
                  v-model="payment.all"
                  v-on:change="_totalValue(index)"
                ></v-checkbox>
              </v-col>
            </v-row>
          </v-col>

          <v-col cols="12" class="d-flex flex-row justify-end">
            <v-btn color="green" @click="order.payments.push({})" :loading="loading" small>
              Adicionar pagamento <v-icon>mdi-plus</v-icon>
            </v-btn>
          </v-col>

          <v-col cols="12" md="6" offset-md="6">
            <v-text-field
              outlined
              prefix="R$"
              type="number"
              :value="valueTotalWithDiscont"
              dense
              label="VALOR TOTAL"
              :loading="loading"
              readonly
              color="black"
            ></v-text-field>
          </v-col>
        </v-row>
      </v-tab-item>
    </v-tabs-items>

    <v-row>
      <v-col cols="12">
          <v-btn color="green darken-1" @click="_store(true)" :loading="loading">
              Salvar <v-icon dark>mdi-content-save</v-icon>
          </v-btn>
          <v-btn color="grey lighten-2" @click="_generateDoc('budget')" :loading="loading">
              Orçamento <v-icon dark>mdi-file-document</v-icon>
          </v-btn>
          <v-btn color="grey lighten-2" @click="_generateDoc('serviceorder')" :loading="loading">
              Ordem de Serviço <v-icon dark>mdi-file-export</v-icon>
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
            <v-icon v-else :size="70" :color="dialog.status === 'error' ? 'red' : 'green' ">
              {{ dialog.status === 'error' ? 'mdi-alert' : 'mdi-check' }}
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
import 'jspdf-autotable';

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
    loadingPayment: false,
    menu_technical_visit_date: false,
    menu_time: false,
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
      id: null,
      description : null,
      comments: null,
      amount : null,
      amount_paid: null,
      technical_visit_date : format( parseISO(new Date().toISOString()), 'yyyy-MM-dd'),
      technical_visit_hour : '',
      discount_amount : null,
      warranty_days : null,
      warranty_conditions : null,
      installments: null,
      products: [],
      services: [],
      payments: [],
      client_id: null,
      status_id: 2,
      address_id: null
    },
    clients: [],
    statuses: [],
    products: [],
    services: [],
    addresses: [],
    payments: []
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
    },
    nowFormat () {
      return moment().format('DD/MM/YYYY')
    },
    technicalVisitDateFormat () {
        return this.order.technical_visit_date ? moment(this.order.technical_visit_date).format('DD/MM/YYYY') : ''
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
      await this._loadClients();
      await this._loadProducts();
      await this._loadServices();
      await this._loadStatuses();
      await this._loadPayments();
    },
    _modal(message, status, show = true){
      this.dialog.message = message;
      this.dialog.status = status;
      this.dialog.show = show;
    },
    async _load(){
      let id = this.$route.params.id ? this.$route.params.id : this.order.id ? this.order.id : null;

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
        if(response.data.success){
          this.order.address_id =  response.data.data[0].id ?? null;
          return this.addresses = response.data.data;
        }
        this._modal('Error ao carregar endereços', 'error');
      });
      this.loadingAddresses = false;
    },
    async _loadStatuses(){
      let params = { type: 'order' };
      
      this.loadingStatuses = true;
      await axios.get(`api/status`, { params }).then(response => {
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
    async _loadPayments(){
      this.loadingPayment = true;
      await axios.get(`api/payment`).then(response => {
        if(response.data.success){
          return this.payments = response.data.data;
        }
        this._modal('Error ao carregar pagamentos', 'error');
      });
      this.loadingPayment = false;
    },
    _totalValue(index){
      if(this.order.payments[index].all){
        this.order.payments[index].value = this.valueTotalWithDiscont;
      }
    },
    async _store(backRoute = false){
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

      let id = this.order.id;

      this.loading = true;

      this._modal(id ? 'Atualizando...' : 'Salvando...');

      this.order.amount = this.valueTotalWithDiscont;

      let response = !id ? await axios.post('api/order', this.order) : await axios.put(`api/order/${id}`, this.order);

      this.loading = false;

      if(response.data.success){
        this.order = response.data.data;
        this._modal('Pedido salvo com sucesso.', 'success');
        setTimeout(() => this._modal('', '', false), 1500);
      } else {
        this._modal('Error ao salvar orçamento. ' , 'error');
      }

      if(backRoute){
        return setTimeout(() => this.$router.push({ name: 'order.index' }), 1500);
      } 
    },
    _formatMoney(value){
      return value.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
    },
    async _generateDoc(nameRoute){
      await this._store();
      let routeData = this.$router.resolve({ name: nameRoute, params: { order: JSON.stringify(this.order) } });
      window.open(routeData.href, '_blank');
    },
    async _generateAppointment(){
      await this._store();
      await this.$store.dispatch('order/setData', { order: this.order })
      this.$router.push({ name: 'appointment.form' })
    },
  }

}
</script>
