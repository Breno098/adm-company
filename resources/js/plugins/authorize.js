import Vue from 'vue';
import store from '~/store'

const verify = (nameRole) => {
  const user = store.getters['auth/user']
  return user ? user.roles.find(role => role.role === nameRole) : false;
};

const roles = {
  user: {
    index: () => verify('user_index'),
    add: () => verify('user_add'),
    delete: () => verify('user_delete'),
    show: () => verify('user_show'),
    update: () => verify('user_update'),
    roles: () => verify('user_roles'),
  },
  client: {
    index: () => verify('client_index'),
    add: () => verify('client_add'),
    delete: () => verify('client_delete'),
    show: () => verify('client_show'),
    update: () => verify('client_update'),
  },
  product: {
    index: () => verify('product_index'),
    add: () => verify('product_add'),
    delete: () => verify('product_delete'),
    show: () => verify('product_show'),
    update: () => verify('product_update'),
  },
  service: {
    index: () => verify('service_index'),
    add: () => verify('service_add'),
    delete: () => verify('service_delete'),
    show: () => verify('service_show'),
    update: () => verify('service_update'),
  },
  order: {
    index: () => verify('order_index'),
    add: () => verify('order_add'),
    delete: () => verify('order_delete'),
    show: () => verify('order_show'),
    update: () => verify('order_update'),
  },
  category: {
    index: () => verify('category_index'),
    add: () => verify('category_add'),
    delete: () => verify('category_delete'),
    show: () => verify('category_show'),
    update: () => verify('category_update'),
  },
  expense: {
    index: () => verify('expense_index'),
    add: () => verify('expense_add'),
    delete: () => verify('expense_delete'),
    show: () => verify('expense_show'),
    update: () => verify('expense_update'),
  },
  appointment: {
    index: () => verify('appointment_index'),
    add: () => verify('appointment_add'),
    delete: () => verify('appointment_delete'),
    show: () => verify('appointment_show'),
    update: () => verify('appointment_update'),
  },
};

Vue.use({ install () { Vue.prototype.$role = { ...roles  } } })