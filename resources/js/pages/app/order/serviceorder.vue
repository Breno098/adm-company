<template>
      <table id="order-service-order" style="margin-top: 10px">
        <tr>
          <td colspan="6"> 
            <div  style="display: flex; flex-direction: row; justify-content: space-between; align-items: center; width: 95%; margin: 0 auto">
              <img src="storage/logo.png" style="height: 60px; width: 110px"/>
              <span> CONDOMINÍOS E RESIDÊNCIAS, SERVIÇOS DE ENCANAMENTO EM GERAL </span>
              <span style="display: flex; flex-direction: column; justify-content: center; align-items: center" colspan="1"> 
                <span style="font-size: 14px; font-weight: bold">16 99187-8532</span> 
                <span style="font-size: 10px;">LEONOR P. GALLO, 350</span> 
                <span style="font-size: 10px;">RIBEIRÃO PRETO - SP</span> 
              </span>
            </div>
          </td>
        </tr>
        <tr style=" border: 0">
          <td colspan="1" style=" border: 0"></td>
          <td colspan="1" style=" border: 0"></td>
          <td colspan="1" style=" border: 0"></td>
          <td colspan="1" style=" border: 0"></td>
          <td colspan="1" style=" border: 0"></td>
          <td colspan="1" style=" border: 0"></td>
        </tr>
        <tr>
          <td colspan="6" style="font-size: 16px; font-weight: bold"> NÚMERO DA ORDEM DE SERVIÇO: {{ order.id }} </td>
        </tr>
        <tr v-if="order.client">
          <td colspan="6"> <strong> CLIENTE: </strong> {{ order.client.name }} </td>
        </tr>
        <tr v-if="order.address_id">
          <td colspan="6"> 
            <strong> ENDEREÇO: </strong> 
            {{ `R. ${order.address.street} nº ${order.address.number}` }} 
            {{ order.address.block && `| BLOCO: ${order.address.block}` }}
            {{ order.address.tower && `| TORRE: ${order.address.tower}` }}
            {{ order.address.floor && `| ANDAR: ${order.address.floor}` }}
            {{ order.address.apartment && `| AP.: ${order.address.apartment}` }}
            {{ order.address.house && `| CASA: ${order.address.house}` }}
          </td>
        </tr>
        <tr v-if="order.address_id">
          <td colspan="3"> <strong> CIDADE:  </strong> {{ order.address.city }} </td>
          <td colspan="3"> <strong> BAIRRO:  </strong> {{ order.address.district }} </td>
        </tr>
        <tr>
          <td colspan="3"> <strong> PROGRAMAÇÃO: </strong> {{ programationDateFormat }} </td>
          <td colspan="3"> <strong> TELEFONE(S): </strong> {{ telefonesComputed }} </td>
        </tr>
        <tr>
          <td colspan="6"> <strong> PROBLEMA RECLAMADA: </strong>  </td>
        </tr>

        <tr>
          <td colspan="6"> <strong> VISTORIA PRÉVIA DO LOCAL </strong> </td>
        </tr>

        <tr v-if="order.description">
          <td colspan="6"> OBSERVAÇÕES: {{ order.description.substring(0, 550) }} </td>
        </tr>

        <tr v-if="!order.description">
          <td colspan="6"> OBSERVAÇÕES: </td>
        </tr>
         <tr v-if="!order.description">
          <td colspan="6" class="line-write"></td>
        </tr>
         <tr v-if="!order.description">
          <td colspan="6" class="line-write"></td>
        </tr>

        <tr>
          <td colspan="6"> LOCALIZAÇÃO E DESCRIÇÃO DO PROBLEMA CONSTATADO E SUA CAUSA </td>
        </tr>
        <tr v-for="line in [0, 1, 2, 4]" :key="line">
          <td colspan="6" class="line-write"> </td>
        </tr>

        <tr>
          <td colspan="3" rowspan="2"> 
            AUTORIZAÇÃO PRÉVIA: DECLARO TER ACEITO AS INFORMAÇÕES ACIMA DESCRITAS, AUTORIZO PREVIAMENTE O PRESTADOR DE SERVIÇOS
            A EXECUTAR OS REPAROS NECESSÁRIOS, SOB CONDIÇÕES EXPOSTAS.
          </td>
          <td colspan="3">  <strong> DATA/HORA: </strong></td>
        </tr>

        <tr>
          <td colspan="3">  <strong> ASSINATURA RESPONSÁVEL: </strong> </td>
        </tr>

        <tr>
          <td colspan="6"> DESCRIÇÃO DO SERVIÇO EXECUTADO (PARA CONHECIMENTO E AVAL DO RESPONSÁVEL) </td>
        </tr>
        <tr v-for="line in [0, 1, 2, 4, 5, 6 , 7]" :key="line">
          <td colspan="6" class="line-write"> </td>
        </tr>

        <tr>
          <td colspan="1"> <strong> MÃO DE OBRA </strong> </td>
          <td colspan="2"> R$ </td>
          <td colspan="3" rowspan="2"> <strong> TOTAL R$ </strong> </td>
        </tr>

        <tr>
          <td colspan="1"> <strong> MATERIAIS </strong> </td>
          <td colspan="2"> R$ </td>
        </tr>

        <tr>
          <td colspan="6"> 
            <strong> FORMAS DE PAGAMENTO: 
              <span v-for="payment in order.form_of_payments" :key="payment">
                ( ){{ payment.name }}
              </span>
            </strong>
          </td>
        </tr>

        <tr>
          <td colspan="6"> <strong> FORNECIDO POR: ( ) PRESTADOR  ( ) CLIENTE </strong> </td>
        </tr>

        <tr>
          <td colspan="6"> OBS.: ORÇAMENTOS NÃO APROVADOS, SERÁ COBRADO O VALOR DA VISITA TÉCNICA R$ 30,00 </td>
        </tr>

        <tr>
          <td colspan="6"> 
            DECLARO QUE ACOMPANHEI E APROVEI A EXECUÇÃO DO SERVIÇO REALIZADO POR PROFISSIONAIS DEVIDADAMENTE UNIFORMIZADOS E 
            TODOS OS TESTES FORAM EFETUADOS CONSTATANDO QUE O SERVIÇO FOI REALIZADO A CONTENTO E NÃO HÁ NENHUM DANO.
            DECLARO TAMBÉM QUE FUI ORIENTADO(A) SOBRE A UTILIZAÇÃO DO LOCAL ONDE FOI EFETUADO  O SERVIÇO E QUE RECEBI UMA VIA DESTE DOCUMENTO. 
          </td>
        </tr>

        <tr>
          <td colspan="2"> <strong> NOME PRESTADOR/ASSINATURA </strong> </td>
          <td colspan="4"> <strong> LOCAL-DATA-HORA </strong> </td>
        </tr>

        <tr>
          <td colspan="2" rowspan="2"> </td>
          <td colspan="4"> <strong> NOME DO RESPONSÁVEL: </strong> </td>
        </tr>

        <tr>
          <td colspan="4"> <strong> ASSINATURA </strong> </td>
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

  tr, td {
    padding: 3px 5px;
    width: 16.66%
  }

  .line-write {
    padding: 11px
  }
</style>

<script>
import moment from 'moment';

export default {
  middleware: 'auth',
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
    telefonesComputed(){
      let telefones = [];

      this.order.client.contacts.forEach(contact => { 
        contact.type === 'TELEFONE' || contact.type === 'CELULAR' || contact.type === 'WHATSAPP' ? telefones.push(contact.contact) : '';
      });

      return telefones.join(' | ');
    },
    programationDateFormat () {
      let dateformat = '';

      if(this.order.technical_visit_date){
        dateformat += moment(this.order.technical_visit_date).format('DD/MM/YYYY');
      }

      if(this.order.technical_visit_date && this.order.technical_visit_time){
        dateformat += ' às ';
      }

      if(this.order.technical_visit_time){
        dateformat += this.order.technical_visit_time;
      }

      return dateformat;
    },
    nowFormat () {
      return moment().format('DD/MM/YYYY')
    },
  },
  methods: {
    _start(){
      this.order = JSON.parse(this.$route.params.order);
      setTimeout(() => window.print(), 400)
    },
    _formatMoney(value){
      return value ? value.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' }) : '';
    },
  }

}
</script>
