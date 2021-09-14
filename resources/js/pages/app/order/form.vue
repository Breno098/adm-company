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

            <v-btn color="green" @click="_store" :loading="loading" rounded dark small>
              Salvar <v-icon dark class="ml-2">mdi-content-save</v-icon>
            </v-btn>
          </v-toolbar>

          <v-row class="mb-1">
            <v-col cols="12" class="d-flex flex-row justify-center">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn 
                    color="grey" 
                    @click="_generateDoc('budget')" 
                    :loading="loading" 
                    v-bind="attrs" 
                    v-on="on" 
                    class="mx-3" 
                    rounded 
                    dark 
                    small
                  >
                    <v-icon>mdi-file-document</v-icon>
                  </v-btn>
                </template>
                <span>Gerar Orçamento</span>
              </v-tooltip>
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn 
                    color="grey lighten-1" 
                    @click="_generateDoc('serviceorder')" 
                    :loading="loading" 
                    v-bind="attrs" 
                    v-on="on" 
                    class="mx-3" 
                    rounded 
                    small
                  >
                    <v-icon>mdi-file-export</v-icon>
                  </v-btn>
                </template>
                <span>Gerar Ordem de Serviço</span>
              </v-tooltip>
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn 
                    color="orange" 
                    @click="_generateAppointment" 
                    :loading="loading"
                    v-bind="attrs" 
                    v-on="on" 
                    class="mx-3" 
                    rounded 
                    small
                  >
                    <v-icon dark>mdi-calendar-today</v-icon>
                  </v-btn>
                </template>
                <span>Agendar Compromisso</span>
              </v-tooltip>
            </v-col>
          </v-row>

          <v-tabs v-model="tab">
            <v-tabs-slider color="blue"></v-tabs-slider>
            <v-tab>Informações <v-icon class="ml-2">mdi-information</v-icon></v-tab>
            <v-tab>Produtos e Serviços <v-icon class="ml-2">mdi-wrench</v-icon></v-tab>
            <v-tab>Garantias <v-icon class="ml-2">mdi-format-align-center</v-icon></v-tab>
            <v-tab>Pagamento <v-icon class="ml-2">mdi-cash</v-icon></v-tab>
          </v-tabs>

          <v-tabs-items v-model="tab" class="pt-5 px-3">
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
                    :error="errors.client_id"
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
                    no-data-text="Nenhum endereço cadastrado para o cliente selecionado"
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
                        :return-value.sync="order.technical_visit_time"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                              v-model="order.technical_visit_time"
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
                            v-model="order.technical_visit_time"
                            @click:minute="$refs.menu_time.save(order.technical_visit_time)"
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
                    hint="DESCRIÇÃO DO ORÇAMENTO E ORDEM DE SERVIÇO"
                    placeholder="DESCRIÇÃO E OBSERVAÇÕES FORMAR (VISÍVEL AO CLIENTE)."
                    @input="order.description = order.description.toUpperCase()"
                  ></v-textarea>
                </v-col>

                <v-col cols="12">
                  <v-textarea
                    label="COMENTARIOS INTERNOS"
                    outlined
                    dense
                    v-model="order.comments"
                    :loading="loading"
                    placeholder="COMENTÁRIOS E OBSERVAÇÕES INTERNAS (NÃO VISÍVEL AO CLIENTE)."
                    hint="COMENTÁRIOS E OBSERVAÇÕES INTERNAS (NÃO VISÍVEL AO CLIENTE)."
                    @input="order.comments = order.comments.toUpperCase()"
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
                      <v-btn color="red" @click="order.products.splice(index, 1);" :loading="loading" small rounded>
                        <v-icon color="red darken-4">mdi-delete</v-icon>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" md="4">
                      <v-autocomplete
                        v-model="product.id"
                        :items="products"
                        item-value="id"
                        item-text="name"
                        label="PRODUTO"
                        outlined
                        dense
                        :loading="loadingProducts"
                        no-data-text="Nenhum produto encontrado"
                        v-on:change="_addDefaultValueProduct(index)"
                      >
                      </v-autocomplete>
                    </v-col>

                    <v-col cols="12" md="2">
                      <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            prefix="R$"
                            label="PREÇO PADRÃO"
                            outlined
                            dense
                            :value="product.default_value"
                            :loading="loading"
                            readonly
                            color="black"
                            v-bind="attrs" 
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <span> Preço cadastrado no produto </span>
                      </v-tooltip>
                    </v-col>

                    <v-col cols="12" md="2">
                      <v-text-field
                        label="QUANTIDADE"
                        outlined
                        type="number"
                        dense
                        v-model="product.quantity"
                        :loading="loading"
                        v-on:click="_sumTotalValueProduct(index)"
                        v-on:keyup="_sumTotalValueProduct(index)"
                      ></v-text-field>
                    </v-col>

                    <v-col cols="12" md="2">
                      <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            prefix="R$"
                            type="number"
                            label="VALOR"
                            outlined
                            dense
                            v-model="product.value"
                            :loading="loading"
                            v-on:click="_sumTotalValueProduct(index)"
                            v-on:keyup="_sumTotalValueProduct(index)"
                            v-bind="attrs" 
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <span> Se necessário, use ponto (.) ao invés de virgula (,) </span>
                      </v-tooltip>
                    </v-col>

                    <v-col cols="12" md="2">
                      <v-text-field
                        prefix="R$"
                        label="VALOR TOTAL"
                        readonly
                        outlined
                        dense
                        :value="product.total_value.toFixed(2)"
                        :loading="loading"
                        color="black"
                      ></v-text-field>
                    </v-col>
                  </v-row>

                  <v-divider color="grey" class="mx-5" v-if="(index + 1) < order.products.length"/>
                </v-col>

                <v-col cols="12" class="d-flex flex-row justify-end">
                  <v-btn 
                    color="green" 
                    @click="order.products.push({ 
                      quantity: 1, 
                      value: 0,
                      default_value: 0,
                      total_value: 0 
                    })" 
                    :loading="loading" 
                    small 
                    rounded
                  >
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
                      <v-btn color="red" @click="order.services.splice(index, 1);" :loading="loading" small rounded>
                        <v-icon color="red darken-4">mdi-delete</v-icon>
                      </v-btn>
                    </v-col>

                    <v-col cols="12" md="4">
                      <v-autocomplete
                        v-model="service.id"
                        :items="services"
                        item-value="id"
                        item-text="name"
                        label="SERVIÇO"
                        outlined
                        dense
                        :loading="loadingServices"
                        no-data-text="Nenhum serviço encontrado"
                        v-on:change="_addDefaultValueService(index)"
                      >
                      </v-autocomplete>
                    </v-col>

                    <v-col cols="12" md="2">
                      <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            prefix="R$"
                            label="PREÇO PADRÃO"
                            outlined
                            dense
                            :value="service.default_value"
                            :loading="loading"
                            readonly
                            color="black"
                            v-bind="attrs" 
                            v-on="on"
                          ></v-text-field>
                         </template>
                        <span> Preço cadastrado no serviço </span>
                      </v-tooltip>
                    </v-col>

                    <v-col cols="12" md="2">
                      <v-text-field
                        label="QUANTIDADE"
                        outlined
                        type="number"
                        dense
                        v-model="service.quantity"
                        :loading="loading"
                        v-on:click="_sumTotalValueService(index)"
                        v-on:keyup="_sumTotalValueService(index)"
                      ></v-text-field>
                    </v-col>

                    <v-col cols="12" md="2">
                      <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            prefix="R$"
                            type="number"
                            label="VALOR"
                            outlined
                            dense
                            v-model="service.value"
                            :loading="loading"
                            v-on:click="_sumTotalValueService(index)"
                            v-on:keyup="_sumTotalValueService(index)"
                            v-bind="attrs" 
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <span> Se necessário, use ponto (.) ao invés de virgula (,) </span>
                      </v-tooltip>
                    </v-col>

                    <v-col cols="12" md="2">
                      <v-text-field
                        prefix="R$"
                        label="VALOR TOTAL"
                        readonly
                        outlined
                        dense
                        :value="service.total_value.toFixed(2)"
                        :loading="loading"
                        color="black"
                      ></v-text-field>
                    </v-col>
                  </v-row>

                  <v-divider color="grey" class="mx-5" v-if="(index + 1) < order.services.length"/>
                </v-col>

                <v-col cols="12" class="d-flex flex-row justify-end">
                  <v-btn 
                    color="green" 
                    @click="order.services.push({ 
                      quantity: 1, 
                      value: 0,
                      default_value: 0,
                      total_value: 0 
                    })" 
                    :loading="loading" 
                    small 
                    rounded
                  >
                    Adicionar serviço <v-icon>mdi-plus</v-icon>
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
                    hint="TERMOS DE GARANTIA"
                    @input="order.warranty_conditions = order.warranty_conditions.toUpperCase()"
                  ></v-textarea>
                </v-col>
              </v-row>
            </v-tab-item>

            <!-- Pagamentos  -->
            <v-tab-item>
              <v-card elevation="2">
                <v-card-title>
                  Formas de pagamento aceitas
                </v-card-title>

                <v-card-subtitle>
                  Orçamento/Order de serviço
                </v-card-subtitle>

                <v-card-text>
                  <v-row>
                    <v-col cols="3" v-for="(payment, index) in payments" :key="index">
                      <v-switch
                        inset
                        :label="payment.name"
                        v-model="order.form_of_payments_format"
                        :value="payment.id"
                      ></v-switch>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>

              <v-card elevation="0" class="mt-3">
                <v-card-title>
                  Pagamento
                </v-card-title>

                <v-card-subtitle>
                  Realizados
                </v-card-subtitle>

                <v-card-text >
                  <v-row>
                    <v-col cols="12" v-for="(payment, index) in order.payments" :key="index">
                      <v-row>
                        <v-col cols="12" class="d-flex flex-row justify-end">
                          <v-btn color="red" @click="order.payments.splice(index, 1);" :loading="loading" small rounded>
                            <v-icon color="red darken-4">mdi-delete</v-icon>
                          </v-btn>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-select
                            :items="paymentsAccept"
                            label="PAGAMENTO"
                            outlined
                            dense
                            :loading="loadingPayment"
                            item-value="id"
                            item-text="name"
                            v-model="payment.id"
                            no-data-text="Selecione formas de pagamento aceitas"
                          >
                          </v-select>
                        </v-col>

                        <v-col cols="12" md="4">
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

                        <v-col cols="12" md="3">
                          <v-menu
                              v-model="menu_date_payments[index]"
                              :close-on-content-click="false"
                              max-width="290"
                              transition="scale-transition"
                              offset-y
                          >
                          <template v-slot:activator="{ on, attrs }">
                              <v-text-field
                                  append-icon="mdi-calendar"
                                  :value="DateFormatPayment(index)"
                                  clearable
                                  label="DATA DO PAGAMENTO"
                                  readonly
                                  v-bind="attrs"
                                  v-on="on"
                                  @click:clear="payment.date = null"
                                  outlined
                                  dense
                              ></v-text-field>
                          </template>
                          <v-date-picker
                              v-model="payment.date"
                              @change="menu_date_payments[index] = false"
                              no-title
                              crollable
                          ></v-date-picker>
                          </v-menu>
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
                      <v-btn color="green" @click="order.payments.push({})" :loading="loading" small rounded>
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
                </v-card-text>
              </v-card>
            </v-tab-item>
          </v-tabs-items>
          
          <v-card-actions>
            <v-row>
              <v-col cols="12" md=3>
                <v-btn color="green darken-1" @click="_store(true)" :loading="loading" rounded dark>
                  Salvar <v-icon dark>mdi-content-save</v-icon>
                </v-btn>
              </v-col>
              <v-col cols="12" md=3>
                <v-btn color="grey lighten-2" @click="_generateDoc('budget')" :loading="loading" rounded>
                  Orçamento <v-icon dark>mdi-file-document</v-icon>
                </v-btn>
              </v-col>
              <v-col cols="12" md=3>
                <v-btn color="grey lighten-2" @click="_generateDoc('serviceorder')" :loading="loading" rounded>
                  Ordem de Serviço <v-icon dark>mdi-file-export</v-icon>
                </v-btn>
              </v-col>
              <v-col cols="12" md=3>
                <v-btn color="orange" @click="_generateAppointment" :loading="loading" rounded>
                  Agendar Compromisso <v-icon dark>mdi-calendar-today</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-card-actions>
         </v-card>
      </v-col>
    </v-row>
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
    menu_date_payments: [],
    errors: {
      client_id: false,
      address_id: false,
      // products: [],
      // services: []
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
      technical_visit_date : null,
      technical_visit_time : '',
      discount_amount : null,
      warranty_days : null,
      warranty_conditions : null,
      installments: null,
      products: [],
      services: [],
      payments: [],
      form_of_payments: [],
      form_of_payments_format: [],
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
    titlePage(){
      return this.$route.params.id ? `Editar Ordem | nº ${this.$route.params.id}` : 'Adicionar Ordem';
    },
    idByRoute(){
      return this.$route.params.id;
    },
    paymentsAccept(){
      let accept = [];

      this.order.form_of_payments_format.forEach(formPayment => {
        this.payments.forEach(payment => { 
          if(payment.id == formPayment){ 
            accept.push(payment);
          }
        })
      });

      return accept;
    },
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
    DateFormatPayment(index) {
        return this.order.payments[index].date ? moment(this.order.payments[index].date).format('DD/MM/YYYY') : ''
    },
    async _start(){
      if(this.idByRoute){
        await this._load();
      }
      await this._loadClients();
      await this._loadProducts();
      await this._loadServices();
      await this._loadStatuses();
      await this._loadPayments();
    },
    async _load(){
      let id = this.$route.params.id ? this.$route.params.id : this.order.id ? this.order.id : null;

      this.loading = true;
      await axios.get(`api/order/${id}`).then(response => {
        if(response.data.success){
          this.order = { 
            form_of_payments_format: [],
            ...response.data.data
          };

          response.data.data.form_of_payments.forEach(payment => {
            this.order.form_of_payments_format.push(payment.id);
          });
          return;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar dados da ordem' })
        this.$refs.fireDialog.hide(1500);
      });
      this.loading = false;
    },
    async _loadClients(){
      this.loadingClients = true;
      await axios.get(`api/client`).then(response => {
        if(response.data.success){
          this.clients = response.data.data;
          
          if(this.idByRoute){
            this._loadAddresses();
          }
          return;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar clientes' })
      });
      this.loadingClients = false;
    },
    async _loadAddresses(){
      let params = { client_id: this.order.client_id };
      
      this.loadingAddresses = true;
      await axios.get(`api/address`, { params }).then(response => {
        if(response.data.success){
          this.order.address_id = response.data.data.length > 0 ? response.data.data[0].id : null;
          return this.addresses = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar endereços' })
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
        this.$refs.fireDialog.error({ title: 'Error ao carregar status' })
      });
      this.loadingStatuses = false;
    },
    async _loadProducts(){
      this.loadingProducts = true;
      await axios.get(`api/item?type=product`).then(response => {
        if(response.data.success){
          return this.products = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar produtos' })
      });
      this.loadingProducts = false;
    },
    async _loadServices(){
      this.loadingServices = true;
      await axios.get(`api/item?type=service`).then(response => {
        if(response.data.success){
          return this.services = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar serviços' })
      });
      this.loadingServices = false;
    },
    async _loadPayments(){
      this.loadingPayment = true;
      await axios.get(`api/payment`).then(response => {
        if(response.data.success){
          return this.payments = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar pagamentos' })
      });
      this.loadingPayment = false;
    },
    _totalValue(index){
      if(this.order.payments[index].all){
        this.order.payments[index].value = this.valueTotalWithDiscont;
      }
    },
    _addDefaultValueProduct(index){
      let filterProduct = this.products.filter(prod => prod.id === this.order.products[index].id);
      if(filterProduct[0] && filterProduct[0].default_value){
        this.order.products[index].default_value = filterProduct[0].default_value.toFixed(2);
        this.order.products[index].value = filterProduct[0].default_value.toFixed(2);
      } else {
        this.order.products[index].default_value = 0
        this.order.products[index].value = 0
      }

      this._sumTotalValueProduct(index)
    },
    _sumTotalValueProduct(index){
      this.order.products[index].total_value = this.order.products[index].value * this.order.products[index].quantity;
    },
    _addDefaultValueService(index){
      let filterService = this.services.filter(prod => prod.id === this.order.services[index].id);
      if(filterService[0] && filterService[0].default_value){
        this.order.services[index].default_value = filterService[0].default_value.toFixed(2);
        this.order.services[index].value = filterService[0].default_value.toFixed(2);
      } else {
        this.order.services[index].default_value = 0
        this.order.services[index].value = 0
      }
      
      this._sumTotalValueService(index)
    },
    _sumTotalValueService(index){
      this.order.services[index].total_value = this.order.services[index].value * this.order.services[index].quantity;
    },
    async _store(backRoute = false){
      this.tab = null;

      /* Validations */
      for (const field in this.errors) {
        if(!this.order[field]){
          this.errors[field] = true;
          return false;
        }
      }

      let id = this.order.id;

      this.loading = true;

      this.$refs.fireDialog.loading({ title: id ? 'Atualizando...' : 'Salvando...' })

      this.order.amount = this.valueTotalWithDiscont;
      this.order.form_of_payments = this.order.form_of_payments_format;

      const response = !id ? await axios.post('api/order', this.order) : await axios.put(`api/order/${id}`, this.order);

      this.loading = false;

      if(response.data.success){
        this.order = { 
          form_of_payments_format: [],
          ...response.data.data
        };

        response.data.data.form_of_payments.forEach(payment => {
          this.order.form_of_payments_format.push(payment.id);
        });

        this.$refs.fireDialog.success({ title: 'Salvo com sucesso' });
        this.$refs.fireDialog.hide(1500);
      } else {
        this.$refs.fireDialog.error({ title: 'Erro ao salvar' });
      }

      if(backRoute){
        setTimeout(() => this.$router.push({ name: 'order.index' }), 1500);
      } 

      return response.data.success;
    },
    _formatMoney(value){
      return value.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
    },
    async _generateDoc(nameRoute){
      this.tab = 3;

      if(this.order.form_of_payments_format.length === 0){
        this.$refs.fireDialog.warning({ 
          title: 'Formas de pagamento',
          message: 'Selecione as fomar de pagamento aceitas (que sairam no Orçamento/Ordem de serviço)'
        });
        return;
      }

      await this._store();
      let routeData = this.$router.resolve({ name: nameRoute, params: { order: JSON.stringify(this.order) } });
      window.open(routeData.href, '_blank');
    },
    async _generateAppointment(){
      if(await this._store()){
        this.$router.push({ 
          name: 'appointment.create', 
          query: {
            orderId: this.order.id,
            clientId: this.order.client_id,
            addressId: this.order.address_id,
          } 
        })
      }
    },
  }

}
</script>
