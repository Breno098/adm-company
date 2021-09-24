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
        <td colspan="3"> <v-icon size="14" class="mr-2">mdi-email</v-icon> desentupidoracrispim@gmail.com </td>
      </tr>
      <tr>
        <td colspan="3"> <v-icon size="14" class="mr-2" color="#000">mdi-phone</v-icon> (16) 99187-8532 </td>
      </tr>
      <tr>
        <td colspan="4" style="padding: 20px 5px; font-size: 16px; text-align: center"> Água é vida, cuidar desse patrimônio é nosso compromisso. </td>
      </tr>
      <tr style="background: #eee;">
        <td colspan="4" class="header" > 
          <div class="header-content"> 
            <div class="header-title"> RECIBO </div> 
            <div class="header-subtitle"> {{ order.id }} </div> 
          </div>
        </td>
      </tr>
      <tr v-if="order.client && order.address">
        <td colspan="4" class="py-5 px-3"> 
          {{ messageBody }} 
        </td>
      </tr>
      <tr style="background: #eee;">
        <td colspan="3" class="value-label"> VALOR DO RECIBO: </td>
        <td colspan="1" class="value-label" style="text-align: right"> {{ amountPaid }} </td>
      </tr>
      <tr>
        <td colspan="4" style="padding: 0 5px"> <hr style="color: #eee;"/> </td>
      </tr>
      <tr>
        <td colspan="4" class="footer"> {{ nowFormat }} </td>
      </tr>
    </table>
    </v-col>
  </v-row>
</template>

<style scoped>
  * {
    font-size: 13px
  }

  table, th, td {
    border-collapse: collapse;
  }

  .header {
    padding: 5px; 
    text-align: center; 
  }

  .header-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .header-title {
    font-size: 18px;
  }

  .header-subtitle {
    font-size: 12px;
  }

  .value-label {
    padding: 5px 15px; 
    font-size: 20px; 
    font-weight: bold
  }

  .footer {
    padding: 15px 5px; 
    text-align: center
  }
</style>

<script>
import { mapGetters } from 'vuex'
import moment from 'moment';

export default {
  middleware: 'auth',
  layout: 'order',
  metaInfo () {
    return { title: 'Recibo' }
  },
  data: () => ({
    order: {}
  }),
  mounted(){
    this._start();
  },
  computed: {
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
        types.push(payment.name);
      })

      return types.join(', ');
    },
    messageBody(){
      let message = '';

      message += `RECEBI DE ${this.order.client.name}, COM ENDEREÇO `;
      message += this.order.address.street   ? `R. ${this.order.address.street}` : '';
      message += this.order.address.number   ? ` N° ${this.order.address.number}` : '';
      message += this.order.address.district ? `, ${this.order.address.district}` : '';
      message += this.order.address.city     ? ` ${this.order.address.city}` : '';
      message += this.order.address.state    ? ` - ${this.order.address.state}` : '';
      message += this.order.address.cep      ? ` (${this.order.address.cep})` : '';
      message += `, O VALOR DE ${this.amountPaid}`;
      message += `REFERENTE A PAGAMENTO REALIZADO (${this.typesPayment})`;

      return message;
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
