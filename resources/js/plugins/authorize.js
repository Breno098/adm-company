import Vue from 'vue';
import store from '~/store'

const verify = (nameRole) => {
  const user = store.getters['auth/user']
  return user.roles.find(role => role.role === nameRole);
};

const roles = {
  client: {
    index: () => verify('index_client'),
    add: () => verify('add_client'),
    delete: () => verify('delete_client'),
    show: () => verify('show_client'),
    update: () => verify('update_client'),
  },
  product: {
    index: () => verify('index_product'),
    add: () => verify('add_product'),
    delete: () => verify('delete_product'),
    show: () => verify('show_product'),
    update: () => verify('update_product'),
  },
  service: {
    index: () => verify('index_service'),
    add: () => verify('add_service'),
    delete: () => verify('delete_service'),
    show: () => verify('show_service'),
    update: () => verify('update_service'),
  },
  order: {
    index: () => verify('index_order'),
    add: () => verify('add_order'),
    delete: () => verify('delete_order'),
    show: () => verify('show_order'),
    update: () => verify('update_order'),
  },
  category: {
    index: () => verify('index_category'),
    add: () => verify('add_category'),
    delete: () => verify('delete_category'),
    show: () => verify('show_category'),
    update: () => verify('update_category'),
  },
  expense: {
    index: () => verify('index_expense'),
    add: () => verify('add_expense'),
    delete: () => verify('delete_expense'),
    show: () => verify('show_expense'),
    update: () => verify('update_expense'),
  },
  appointment: {
    index: () => verify('index_appointment'),
    add: () => verify('add_appointment'),
    delete: () => verify('delete_appointment'),
    show: () => verify('show_appointment'),
    update: () => verify('update_appointment'),
  },
};

Vue.use({ install () { Vue.prototype.$role = { ...roles  } } })