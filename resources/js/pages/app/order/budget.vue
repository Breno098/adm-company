<template>
  <v-row>
    <v-col cols="12">
      <v-card elevation="0">
        <v-row>
            <v-col cols="4">
              <v-img src="storage/logo.png"/>
            </v-col>

            <v-col cols="8">
              <v-card elevation="0" >
                <v-card-text>
                  <div v-for="(info, index) in infos" :key="index">
                    <v-icon 
                      size="14" 
                      class="mr-2" 
                      :color="info.iconColor"
                    >{{ info.icon }}</v-icon> 
                    {{ info.label }}
                  </div>
                </v-card-text>
              </v-card>
            </v-col>
        </v-row>

        <v-card-text class="d-flex flex-row justify-center">
          {{ message }}
        </v-card-text>

        <v-card-text class="d-flex flex-column justify-center align-center grey lighten-2 py-1">
          <div class="text-h6"> ORÇAMENTO </div> 
          <small> {{ order.id }} </small> 
        </v-card-text>

        <v-card-text class="py-5">
          <div v-if="order.client">
            {{ `CLIENTE: ${order.client.name}` }}
          </div>
          <div v-if="order.address_id">
            {{ `R. ${order.address.street} nº ${order.address.number}, ${order.address.district}` }}
          </div>
          <div v-if="order.address_id">
            {{ `${order.address.city} - ${order.address.state}, CEP ${order.address.cep}` }}
          </div>
          <div class="d-flex flex-row justify-start" v-if="order.client.contacts">
            <div v-for="(contact, index) in order.client.contacts" :key="index">
              {{ `${contact.type}: ${contact.contact}` }}
            </div>
          </div>

          <div v-if="order.technical_visit_date || order.technical_visit_time" class="d-flex flex-row justify-start">
            {{ order.technical_visit_date ? `DATA VISITA TÉCNICA: ${technicalVisitDateFormat}` : '' }}
            {{ order.technical_visit_time ? `HORA VISITA TÉCNICA: ${order.technical_visit_time}` : '' }}
          </div>
        </v-card-text>

        <v-card-text 
          v-if="order.services.length > 0"
          class="d-flex flex-column justify-center align-center grey lighten-2 py-1"
        >
          <div class="text-h6"> SERVIÇOS | MÃO DE OBRA </div> 
        </v-card-text>

        <v-card-text 
          class="py-1 mt-1 mb-3"  
          v-if="order.services.length > 0"
        >
          <v-row>
            <v-col cols="6" class="font-weight-bold">DESCRIÇÃO</v-col>
            <v-col cols="2" class="font-weight-bold">VALOR</v-col>
            <v-col cols="2" class="font-weight-bold">QUANTIDADE</v-col>
            <v-col cols="2" class="font-weight-bold">TOTAL</v-col>
          </v-row>

          <v-row 
            v-for="(service, index) in order.services" 
            :key="index"
          >
            <v-col cols="6" class="py-1">- {{ service.name }}</v-col>
            <v-col cols="2" class="py-1">{{ service.value | formatMoney }}</v-col>
            <v-col cols="2" class="py-1">{{ service.quantity }}</v-col>
            <v-col cols="2" class="py-1">{{ (service.value * service.quantity) | formatMoney }}</v-col>
          </v-row>
        </v-card-text>

        <v-card-text 
          v-if="order.products.length > 0"
          class="d-flex flex-column justify-center align-center grey lighten-2 py-1"
        >
          <div class="text-h6"> PRODUTOS </div> 
        </v-card-text>

        <v-card-text 
          class="py-1 mt-1 mb-3"  
          v-if="order.products.length > 0"
        >
          <v-row>
            <v-col cols="6" class="font-weight-bold">DESCRIÇÃO</v-col>
            <v-col cols="2" class="font-weight-bold">VALOR</v-col>
            <v-col cols="2" class="font-weight-bold">QUANTIDADE</v-col>
            <v-col cols="2" class="font-weight-bold">TOTAL</v-col>
          </v-row>

          <v-row 
            v-for="(product, index) in order.products" 
            :key="index"
            class="py-0"
          >
            <v-col cols="6" class="py-1">- {{ product.name }}</v-col>
            <v-col cols="2" class="py-1">{{ product.value | formatMoney }}</v-col>
            <v-col cols="2" class="py-1">{{ product.quantity }}</v-col>
            <v-col cols="2" class="py-1">{{ (product.value * product.quantity) | formatMoney }}</v-col>
          </v-row>
        </v-card-text>

        <v-card-text 
          class="grey lighten-2 py-1"
          v-if="order.discount_amount"
        >
          <v-row>
            <v-col cols="10">Desconto:</v-col>
            <v-col cols="2">{{ order.discount_amount | formatMoney }}</v-col>
          </v-row>
        </v-card-text>

        <v-card-text 
          class="grey lighten-2 py-0 mt-5"
          v-if="order.amount"
        >
          <v-row>
            <v-col cols="10">
              <h2> Valor total: </h2>
            </v-col>
            <v-col cols="2">
              <h3> {{ order.amount | formatMoney }} </h3>
            </v-col>
          </v-row>
        </v-card-text>

        <v-card-text class="mt-3">
          <v-row>
            <v-col cols="12" class="py-1">Formas de pagamento:</v-col>
            <v-col cols="12" class="py-1">
              <span v-for="payment in order.form_of_payments" :key="payment">
                ( ){{ payment.name }}
              </span>
            </v-col>
          </v-row>
        </v-card-text>

        <v-card-text class="d-flex flex-row justify-center mt-16">
          {{ nowFormat }}
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<style scoped>
</style>

<script>
import { mapGetters } from 'vuex'
import moment from 'moment';

export default {
  middleware: 'auth',
  layout: 'order',
  metaInfo () {
    return { title: this.titlePage }
  },
  data: () => ({
    order: {}
  }),
  mounted(){
    this._start();
  },
  filters: {
    formatMoney(value){
      return value ? value.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '';
    },
  },
  methods: {
    _start(){
      this.order = JSON.parse(this.$route.params.order);
      setTimeout(() => window.print(), 400)
    },
  },
  computed: {
    titlePage(){
      return `Orçamento ${this.order.id}`;
    },
    technicalVisitDateFormat(){
      return moment(this.order.technical_visit_date).format('DD/MM/YYYY');
    },
    infos() {
      return [
        {
          label: 'LEONOR PENNACHIOTTI GALLO, 350 | PQ, FLAMBOYANS',
          icon: 'mdi-google-maps',
          iconColor: '#ff7403'
        },
        {
          label: 'RIBEIRÃO PRETO - SP | CEP 14093-651 ',
          icon: 'mdi-google-maps',
          iconColor: '#ff7403'
        },
        {
          label: 'CNPJ 17.107.361/0001-34',
          icon: 'mdi-card-account-details',
          iconColor: '#000'
        },
        {
          label: ' /@manutencaocrispim',
          icon: 'mdi-facebook',
          iconColor: 'blue'
        },
        {
          label: '/desentupidoracrispim',
          icon: 'mdi-instagram',
          iconColor: '#ff7403'
        },
        {
          label: 'desentupidoracrispim@gmail.com',
          icon: 'mdi-email',
          iconColor: '#000'
        },
        {
          label: '(16) 99187-8532',
          icon: 'mdi-phone',
          iconColor: '#000'
        },
      ];
    },
    message() {
      return `Água é vida, cuidar desse patrimônio é nosso compromisso.`;
    },
    nowFormat () {
      return moment().format('DD/MM/YYYY')
    },
  },
}
</script>
