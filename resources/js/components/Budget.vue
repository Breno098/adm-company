<template>
  <table id="pdf" >
      <tr>
        <td colspan="4"> ORÇAMENTO 10000-2021 | Desentupimento </td>
      </tr>
      <tr v-if="budget.client">
        <td colspan="4"> {{ `Cliente: ${budget.client.name}` }} </td>
      </tr>
      <tr v-if="budget.address_id">
        <td colspan="4"> {{ `R. ${budget.address.street} nº ${budget.address.number}, ${budget.address.district}` }} </td>
      </tr>
      <tr v-if="budget.address_id">
        <td colspan="4"> {{ `${budget.address.city} - ${budget.address.state}, CEP ${budget.address.cep}` }} </td>
      </tr>
      <tr v-for="(contact, index) in budget.client.contacts" :key="index">
        <td colspan="4"> {{ `${contact.type}: ${contact.contact}` }} </td>
      </tr>
      <tr>
        <td colspan="4"> INFORMAÇÕES BÁSICAS </td>
      </tr>
      <tr v-if="budget.description">
        <td colspan="4"> {{ `Observações: ${budget.description}` }} </td>
      </tr>
      <tr v-if="budget.services">
        <td> Serviço </td>
        <td> Valor </td>
        <td> Quantidade </td>
        <td> Total </td>
      </tr>
      <tr v-for="(service, index) in budget.services" :key="index">
        <td> {{ service.name }} </td>
        <td> {{ _formatMoney(service.value) }} </td>
        <td> {{ service.quantity }} </td>
        <td> {{ _formatMoney(service.value * service.quantity) }} </td>
      </tr>
      <tr v-if="budget.products">
        <td> Produto </td>
        <td> Valor </td>
        <td> Quantidade </td>
        <td> Total </td>
      </tr>
      <tr v-for="(product, index) in budget.products" :key="index">
        <td> {{ product.name }} </td>
        <td> {{ _formatMoney(product.value) }} </td>
        <td> {{ product.quantity }} </td>
        <td> {{ _formatMoney(product.value * product.quantity) }} </td>
      </tr>
      <tr>
        <td colspan="3"> VALOR TOTAL: </td>
        <td colspan="1"> {{ _formatMoney(budget.amount) }} </td>
      </tr>
      <tr>
        <td colspan="4"> Forma(s) de Pagamento </td>
      </tr>
      <tr>
        <td colspan="4"> Cartão de crédito, cartão de débito, dinheiro, cheque, boleto ou transferência bancária (Banco Itaú | Agência 1635 | Conta 35942-3). </td>
      </tr>
      <tr>
        <td colspan="4"> Informações adicionais </td>
      </tr>
      <tr>
        <td colspan="4"> Todos atendimentos com retirada de louças sanitárias o cliente fica responsável em caso de quebra. </td>
      </tr>
      <tr>
        <td colspan="4"> Ribeirão Preto, {{ nowFormat() }} </td>
      </tr>
      <tr>
        <td colspan="4"> Desentupidora Crispim </td>
      </tr>
    </table>
</template>

<script>
import moment from 'moment';

export default {
  name: 'Budget',
  props: {
    budget: {
      type: Object,
      default: {}
    }
  },
  methods: {
    nowFormat () {
      return moment().format('DD/MM/YYYY')
    },
    _formatMoney(value){
      return value.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
    },
  }
}
</script>
