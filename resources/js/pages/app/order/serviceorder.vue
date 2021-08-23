<template>
      <table id="order-service-order">
        <!-- <tr>
          <td colspan="4" style="display: flex; flex-direction: row; justify-content: space-between; align-items: center; width: 100%"> 
            <img src="storage/logo.png" style="height: 50px; width: 80px"/>
            <span> CONDOMINÍOS E RESIDÊNNCIAS, SERVIÇOS DE ENCANAMENTO EM GERAL </span>
            <span style="display: flex; flex-direction: column; justify-content: center; align-items: center" colspan="1"> 
              <span style="font-size: 14px; font-weight: bold">16 99187-8532</span> 
              <span style="font-size: 10px;">LEONOR P. GALLO, 350</span> 
              <span style="font-size: 10px;">RIBEIRÃO PRETO - SP</span> 
            </span>
          </td>
        </tr> -->
        <tr>
          <td></td> <td></td> <td></td> <td></td>
        </tr>
        <tr>
          <td colspan="4" style="font-size: 18px; font-weight: bold"> NÚMERO DA ORDEM DE SERVIÇO: {{ order.id }} </td>
        </tr>
        <tr v-if="order.client">
          <td colspan="4"> CLIENTE: {{ order.client.name }} </td>
        </tr>
        <tr v-if="order.address_id">
          <td colspan="4"> ENDEREÇO: {{ `R. ${order.address.street} nº ${order.address.number}` }} </td>
        </tr>
        <tr v-if="order.address_id">
          <td colspan="2"> CIDADE: {{ order.address.city }} </td>
          <td colspan="2"> BAIRRO: {{ order.address.district }} </td>
        </tr>
        <tr>
          <td colspan="2"> PROGRAMAÇÃO: {{ order.address.city }} </td>
          <td colspan="2"> TELEFONE: 16 99999-9999 </td>
        </tr>
        <tr>
          <td colspan="4"> PROBLEMA RECLAMADA:  </td>
        </tr>

        <tr>
          <td colspan="4"> VISTORIA PRÉVIA DO LOCAL </td>
        </tr>
        <tr>
          <td colspan="4"> LOCAL POSSUI AVARIAS OU ALGUMA IRREGULARIDADE? ( ) NÃO  ( ) SIM   QUAIS? DESCREVA ABAIXO: </td>
        </tr>
        <tr v-for="line in [0, 1, 2]" :key="line">
          <td colspan="4"> </td>
        </tr>

        <tr>
          <td colspan="4"> LOCALIZAÇÃO E DESCRIÇÃO DO PROBLEMA CONSTATADO E SUA CAUSA </td>
        </tr>
        <tr v-for="line in [0, 1, 2, 4]" :key="line">
          <td colspan="4"> </td>
        </tr>

        <!-- <tr>
          <td colspan="2" rowspan="2"> 
            AUTORIZAÇÃO PRÉVIA: DECLARO TER ACEITO AS INFORMAÇÕES ACIMA DESCRITAS, AUTORIZO PREVIAMENTE O PRESTADOR DE SERVIÇOS
            A EXECUTAR OS REPAROS NECESSÁRIOS, SOB CONDIÇÕES EXPOSTAS.
          </td>
          <td colspan="2"> ASSINATURA/NOME RESPONSÁVEL: </td>
        </tr> -->

        <tr>
          <td colspan="2">DATA/HORA:</td>
        </tr>

        <tr>
          <td colspan="4"> DESCRIÇÃO DO SERVIÇO EXECUTADO (PARA CONHECIMENTO E AVAL DO RESPONSÁVEL) </td>
        </tr>
        <tr v-for="line in [0, 1, 2, 4, 5, 6 , 7]" :key="line">
          <td colspan="4"> </td>
        </tr>

        <tr>
          <td colspan="1"> MÃO DE OBRA </td>
          <td colspan="1"> R$ </td>
          <td colspan="2" rowspan="2"> TOTAL R$ </td>
        </tr>

        <tr>
          <td colspan="1"> MATERIAIS </td>
          <td colspan="1"> R$ </td>
        </tr>

        <tr>
          <td colspan="4"> 
            FORMAS DE PAGAMENTO: 
            <span v-for="payment in ['DINHEIRO', 'DÉBITO', 'CRÉDITO', 'BOLETO', 'CONTRATO', 'TRANSFÊRENCIA', 'PIX']" :key="payment">
              ( ){{ payment }}
            </span>
          </td>
        </tr>

        <tr>
          <td colspan="4"> FORNECIDO POR: ( ) PRESTADOR  ( ) CLIENTE </td>
        </tr>

        <tr>
          <td colspan="4"> OBS.: ORÇAMENTOS NÃO APROVADOS, SERÁ COBRADO O VALOR DA VISITA TÉCNICA R$ 30,00 </td>
        </tr>

        <!-- <tr>
          <td colspan="4"> 
            DECLARO QUE ACOMPANHEI E APROVEI A EXECUÇÃO DO SERVIÇO REALIZADO POR PROFISSIONAIS DEVIDADAMENTE UNIFORMIZADOS E 
            TODOS OS TESTES FORAM EFETUADOS CONSTATANDO QUE O SERVIÇO FOI REALIZADO A CONTENTO E NÃO HÁ NENHUM DANO.
            DECLARO TAMBÉM QUE FUI ORIENTADO(A) SOBRE A UTILIZAÇÃO DO LOCAL ONDE FOI EFETUADO  O SERVIÇO E QUE RECEBI UMA VIA DESTE DOCUMENTO. 
          </td>
        </tr> -->

        <tr>
          <td colspan="2"> NOME PRESTADOR/ASSINATURA </td>
          <td colspan="2"> LOCAL - DATA - HORA </td>
        </tr>

        <tr>
          <td colspan="2" rowspan="2"> </td>
          <td colspan="2"> NOME DO RESPONSÁVEL: </td>
        </tr>

        <tr>
          <td colspan="2"> ASSINATURA </td>
        </tr>

      </table>
</template>

<style>
  * {
    font-size: 12px
  }

  table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
  }

  th, td {
    padding: 10px;
  }
</style>

<script>
import moment from 'moment';

export default {
  layout: 'order',
  metaInfo () {
    return { title: 'Ordem de Serviço' }
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
      // setTimeout(() => window.print(), 200)
    },
    _formatMoney(value){
      return value ? value.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' }) : '';
    },
  }

}
</script>
