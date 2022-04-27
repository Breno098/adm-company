import Vue from 'vue';
import store from '~/store'

const authorize = {
  can: (nameRole) => {
    const user = store.getters['auth/user'];
    const can = user.roles.find(role => role.role === nameRole);
    return user && can ? true : false;
  }
};

Vue.prototype.$can = (can) => {
  return authorize.can(can);
}

