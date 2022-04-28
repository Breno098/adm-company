import Vue from 'vue';
import moment from 'moment';


Vue.filter("formatMoney", value => (parseFloat(!value ? 0 : value)).toLocaleString('pt-br', {style: 'currency', currency: 'BRL'}) );

Vue.filter("formatDMY", date =>  date ? moment(date).format('DD/MM/YYYY') : '');

Vue.filter("textLimitChar", (text, limit = 20, caseStringNull = '', concat = '...') => {
  if(typeof text !== 'string') {
    return caseStringNull;
  }

  return text.length > limit ? text.substring(0, limit).concat(concat) : text;
});

Vue.filter("paymentStatusColor", (value) => {
    switch (value) {
      case 'PAGO TOTAL': return 'green';
      case 'EM ABERTO': return 'primary';
      case 'INADIMPLENTE': return 'red';
      default: return '';
    }
});

Vue.filter("paymentStatusIcon", (value) => {
  switch (value) {
    case 'PAGO TOTAL': return 'mdi-check';
    case 'EM ABERTO': return 'mdi-dots-horizontal';
    case 'INADIMPLENTE': return 'mdi-close';
    default: return '';
  }
});

Vue.filter("statusColor", (value) => {
  switch (value) {
    case 'CANCELADO': return 'red accent-3';
    case 'AGUARDANDO APROVAÇÃO': return 'orange accent-3';
    case 'EM ANDAMENTO': return 'primary';
    case 'CONCLUÍDO': return 'green';
    default: return '';
  }
});

Vue.filter("statusIcon", (value) => {
  switch (value) {
    case 'CANCELADO': return 'mdi-close';
    case 'AGUARDANDO APROVAÇÃO': return 'mdi-thumb-up';
    case 'EM ANDAMENTO': return 'mdi-dots-horizontal';
    case 'CONCLUÍDO': return 'mdi-check';
    default: return '';
  }
});

Vue.filter("statusColorHover", (value, active, noHover = '') => {
  if(!active) {
    return noHover;
  }

  switch (value) {
    case 'CANCELADO': return 'red accent-3';
    case 'AGUARDANDO APROVAÇÃO': return 'orange accent-3';
    case 'EM ANDAMENTO': return 'primary';
    case 'CONCLUÍDO': return 'green';
    default: return '';
  }
});

Vue.filter("paymentInstallmentStatusColor", (value) => {
  switch (value) {
    case 'PAGO': return 'green';
    case 'EM ABERTO': return 'primary';
    case 'INADIMPLENTE': return 'red';
    default: return '';
  }
});

Vue.filter("paymentInstallmentStatusIcon", (value) => {
  switch (value) {
    case 'PAGO': return 'mdi-check';
    case 'EM ABERTO': return 'mdi-dots-horizontal';
    case 'INADIMPLENTE': return 'mdi-close';
    default: return '';
  }
});

