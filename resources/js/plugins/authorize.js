import Vue from 'vue';
import store from '~/store'

const role = (nameRole) => {
  const user = store.getters['auth/user']
  return user ? user.roles.find(role => role.role === nameRole) : false;
};

const roles = {
  user: {
    index: () => role('user_index'),
    add: () => role('user_add'),
    delete: () => role('user_delete'),
    show: () => role('user_show'),
    update: () => role('user_update'),
    roles: () => role('user_roles'),
  },
  client: {
    index: () => role('client_index'),
    add: () => role('client_add'),
    delete: () => role('client_delete'),
    show: () => role('client_show'),
    update: () => role('client_update'),
  },
  employee: {
    index: () => role('employee_index'),
    add: () => role('employee_add'),
    delete: () => role('employee_delete'),
    show: () => role('employee_show'),
    update: () => role('employee_update'),
  },
  employee_receipt: {
    index: () => role('employee_receipt_index'),
    add: () => role('employee_receipt_add'),
    delete: () => role('employee_receipt_delete'),
    show: () => role('employee_receipt_show'),
    update: () => role('employee_receipt_update'),
    download: () => role('employee_receipt_download'),
  },
  product: {
    index: () => role('product_index'),
    add: () => role('product_add'),
    delete: () => role('product_delete'),
    show: () => role('product_show'),
    update: () => role('product_update'),
  },
  service: {
    index: () => role('service_index'),
    add: () => role('service_add'),
    delete: () => role('service_delete'),
    show: () => role('service_show'),
    update: () => role('service_update'),
  },
  order: {
    index: () => role('order_index'),
    add: () => role('order_add'),
    delete: () => role('order_delete'),
    show: () => role('order_show'),
    update: () => role('order_update'),
  },
  category: {
    index: () => role('category_index'),
    add: () => role('category_add'),
    delete: () => role('category_delete'),
    show: () => role('category_show'),
    update: () => role('category_update'),
  },
  expense: {
    index: () => role('expense_index'),
    add: () => role('expense_add'),
    delete: () => role('expense_delete'),
    show: () => role('expense_show'),
    update: () => role('expense_update'),
  },
  appointment: {
    index: () => role('appointment_index'),
    add: () => role('appointment_add'),
    delete: () => role('appointment_delete'),
    show: () => role('appointment_show'),
    update: () => role('appointment_update'),
  },
};

Vue.use({ install () { Vue.prototype.$role = { ...roles  } } })
