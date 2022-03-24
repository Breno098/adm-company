import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/components/App'

import '~/plugins'
import '~/components'

import vuetify from './plugins/vuetify'


Vue.config.productionTip = false

import moment from 'moment';

Vue.filter("formatMoney", value => (parseFloat(!value ? 0 : value)).toLocaleString('pt-br', {style: 'currency', currency: 'BRL'}) );
Vue.filter("formatDMY", date =>  date ? moment(date).format('DD/MM/YYYY') : '');

/* eslint-disable no-new */
new Vue({
  vuetify,
  i18n,
  store,
  router,
  ...App
})
