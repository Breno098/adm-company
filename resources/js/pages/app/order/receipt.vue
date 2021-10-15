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
          <div class="text-h6"> RECIBO </div> 
          <small> {{ order.id }} </small> 
        </v-card-text>

        <v-card-text class="d-flex flex-row justify-center py-5">
          {{ messageBody }}
        </v-card-text>

        <v-card-title class="d-flex flex-row justify-space-between grey lighten-2">
          <div> VALOR DO RECIBO: </div> 
          <div> {{ amountPaid }} </div> 
        </v-card-title>

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
  methods: {
    _start(){
      this.order = JSON.parse(this.$route.params.order);
      setTimeout(() => window.print(), 400)
    },
  },
  computed: {
    titlePage(){
      return `Recibo ${this.order.id}`;
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
    amountPaid(){
      let amountPaid = 0;

      this.order.payments.forEach(payment => {
        amountPaid += payment.value;
      })

      return amountPaid.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
    },
    typesPayment(){
      let types = [];

      this.order.payments.forEach(payment => {
        types.push(payment.name ?? '');
      })

      return types.join(', ');
    },
    messageBody(){
      let message = '';

      message += this.order.client           ? `RECEBI DE ${this.order.client.name}, COM ENDEREÇO ` : '';
      message += this.order.address.street   ? `R. ${this.order.address.street}` : '';
      message += this.order.address.number   ? ` N° ${this.order.address.number}` : '';
      message += this.order.address.district ? `, ${this.order.address.district}` : '';
      message += this.order.address.city     ? ` ${this.order.address.city}` : '';
      message += this.order.address.state    ? ` - ${this.order.address.state}` : '';
      message += this.order.address.cep      ? ` (${this.order.address.cep})` : '';
      message += `, O VALOR DE ${this.amountPaid} `;
      message += `REFERENTE A PAGAMENTO REALIZADO (${this.typesPayment})`;

      return message;
    },
    nowFormat () {
      return moment().format('DD/MM/YYYY')
    },
  },
}
</script>
