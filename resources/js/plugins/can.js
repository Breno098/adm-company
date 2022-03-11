import Vue from 'vue';
import store from '~/store'

const authorize = {
  can: (nameRole) => {
    const user = store.getters['auth/user']
    return user ? user.roles.find(role => role.role === nameRole) : false;
  }
};

Vue.prototype.$can = (can) => {
  return authorize.can(can);
}

