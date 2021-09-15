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
};

Vue.use({ install () { Vue.prototype.$role = { ...roles  } } })