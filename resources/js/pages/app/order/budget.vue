<template>
  <v-row>
    <v-col cols="12" class="d-flex flex-row justify-center">
    <table id="order-budget">
      <tr>
        <td rowspan="8"> <img src="storage/logo.png" style="height: 130px; width: 220px"/> </td>
      </tr>
      <tr>
        <td colspan="3"> 
          <v-icon size="14" class="mr-2" color="#ff7403">mdi-google-maps</v-icon> 
          LEONOR PENNACHIOTTI GALLO, 350 | PQ, FLAMBOYANS
        </td>
      </tr>  
      <tr>
        <td colspan="3"> 
          <v-icon size="14" class="mr-2" color="#ff7403">mdi-google-maps</v-icon> 
          Ribeirão Preto - SP | CEP 14093-651 
        </td>
      </tr>
      <tr>
        <td colspan="3"> <v-icon size="14" class="mr-2">mdi-card-account-details</v-icon> CNPJ 17.107.361/0001-34  </td>
      </tr>
      <tr>
        <td colspan="3"> <v-icon size="14" class="mr-2" color="blue">mdi-facebook</v-icon> /@manutencaocrispim </td>
      </tr>
       <tr>
        <td colspan="3"> <v-icon size="14" class="mr-2" color="#ff7403">mdi-instagram</v-icon> /desentupidoracrispim </td>
      </tr>
      <tr>
        <td colspan="3"> <v-icon size="14" class="mr-2">mdi-email</v-icon> desentupidoracrispim@hotmail.com </td>
      </tr>
      <tr>
        <td colspan="3"> <v-icon size="14" class="mr-2" color="#000">mdi-phone</v-icon> (16) 99187-8532 </td>
      </tr>
      <tr>
        <td colspan="4" style="padding: 20px 5px; font-size: 16px; text-align: center"> Água é vida, cuidar desse patrimônio é nosso compromisso. </td>
      </tr>
      <tr style="background: #eee;">
        <td colspan="4" style="padding: 10px 5px; font-size: 23px; text-align: center; font-weight: bold"> ORÇAMENTO {{ order.id }} </td>
      </tr>
      <tr v-if="order.client">
        <td colspan="4" style="padding: 0px 5px"> {{ `CLIENTE: ${order.client.name}` }} </td>
      </tr>
      <tr v-if="order.address_id">
        <td colspan="4" style="padding: 0px 5px"> {{ `R. ${order.address.street} nº ${order.address.number}, ${order.address.district}` }} </td>
      </tr>
      <tr v-if="order.address_id">
        <td colspan="4" style="padding: 0px 5px"> {{ `${order.address.city} - ${order.address.state}, CEP ${order.address.cep}` }} </td>
      </tr>
      <tr v-for="(contact, index) in order.client.contacts" :key="index">
        <td colspan="4" style="padding: 0px 5px"> {{ `${contact.type}: ${contact.contact}` }} </td>
      </tr>
      <tr>
        <td colspan="4" style="padding: 0 10px"> <hr style="color: #eee;"/> </td>
      </tr> 
      <tr v-if="order.technical_visit" style="padding: 20px 0px;">
        <td colspan="1" style="padding: 0px 5px"> {{ `Data visita técnica: ${technicalVisitDate}` }} </td>
        <td colspan="2" style="padding: 0px 5px"> {{ `Hora visita técnica: ${technicalVisitHour}` }} </td>
      </tr>
      <tr v-if="order.description" style="padding: 20px 0px;">
        <td colspan="4" style="padding: 0px 5px"> {{ `Observações: ${order.description}` }} </td>
      </tr>
      <tr v-if="order.services.length > 0" style="background: #eee;">
        <td colspan="4" style="padding: 8px 5px; font-size: 18px"> SERVIÇOS </td>
      </tr>
      
      <tr v-if="order.services.length > 0">
        <td style="padding: 5px 5px 10px; font-weight: bold"> DESCRIÇÃO </td>
        <td style="padding: 5px 0; font-weight: bold"> VALOR </td>
        <td style="padding: 5px 0; font-weight: bold"> QUANTIDADE </td>
        <td style="padding: 5px 0; font-weight: bold"> TOTAL </td>
      </tr>
      <tr v-for="(service, index) in order.services" :key="index">
        <td style="padding: 0px 5px"> - {{ service.name }} </td>
        <td> {{ _formatMoney(service.value) }} </td>
        <td> {{ service.quantity }} </td>
        <td style="text-align: right"> {{ _formatMoney(service.value * service.quantity) }} </td>
      </tr>
      <tr v-if="order.products.length > 0" style="background: #eee;">
        <td colspan="4" style="padding: 8px 5px; font-size: 18px"> PRODUTOS </td>
      </tr>
      <tr v-if="order.products.length > 0">
        <td style="padding: 5px 5px 10px; font-weight: bold"> DESCRIÇÃO </td>
        <td style="padding: 5px 0; font-weight: bold"> VALOR </td>
        <td style="padding: 5px 0; font-weight: bold"> QUANTIDADE </td>
        <td style="padding: 5px 0; font-weight: bold"> TOTAL </td>
      </tr>
      <tr v-for="(product, index) in order.products" :key="index">
        <td style="padding: 0px 5px"> - {{ product.name }} </td>
        <td> {{ _formatMoney(product.value) }} </td>
        <td> {{ product.quantity }} </td>
        <td style="text-align: right"> {{ _formatMoney(product.value * product.quantity) }} </td>
      </tr>
      <tr v-if="order.discount_amount">
        <td colspan="3" style="padding: 5px 0 5px 15px; font-size: 10px; font-weight: bold"> DESCONTO: </td>
        <td colspan="1" style="padding: 5px 5px; font-size: 10px; text-align: right; font-weight: bold"> - {{ _formatMoney(order.discount_amount) }} </td>
      </tr>
      <tr style="background: #eee;">
        <td colspan="3" style="padding: 5px 0 5px 15px; font-size: 20px; font-weight: bold"> VALOR TOTAL: </td>
        <td colspan="1" style="padding: 5px 5px; font-size: 20px; text-align: right; font-weight: bold"> {{ _formatMoney(order.amount) }} </td>
      </tr>
      <tr>
        <td colspan="4" style="padding: 10px 5px 0px"> FORMAS DE PAGAMENTO </td>
      </tr>
      <tr>
        <td colspan="4"> CARTÃO DE CRÉDITO OU DÉBITO, DINHEIRO, CHEQUE, BOLETO OU TRANSFERÊNCIA BANCÁRIA </td>
      </tr>
      <tr>
        <td colspan="4"> (Banco Itaú | Agência 1635 | Conta 35942-3) </td>
      </tr>
      <tr>
        <td colspan="4" style="padding: 0 5px"> <hr style="color: #eee;"/> </td>
      </tr>
      <tr>
        <td colspan="4" style="padding: 15px 5px; text-align: center"> {{ nowFormat }} </td>
      </tr>
    </table>
    </v-col>
  </v-row>
</template>

<style>
  * {
    font-size: 13px
  }
</style>

<script>
import { mapGetters } from 'vuex'
import moment from 'moment';

export default {
  layout: 'order',
  metaInfo () {
    return { title: 'Orçamento' }
  },
  data: () => ({
    order: {}
  }),
  mounted(){
    this._start();
  },
  computed: {
    technicalVisitDate(){
      return moment(this.order.technical_visit).format('DD/MM/YYYY');
    },
    technicalVisitHour(){
      return moment(this.order.technical_visit).format('HH:mm');
    },
    nowFormat () {
      return moment().format('DD/MM/YYYY')
    },
  },
  methods: {
    _start(){
      this.order = JSON.parse(this.$route.params.order);
      setTimeout(() => window.print(), 200)
    },
    _formatMoney(value){
      return value ? value.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) : '';
    },
  }

}
</script>
