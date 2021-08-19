import store from '~/store'

export default (to, from, next) => {
  console.log( store.getters['auth/user']  );
  if (store.getters['auth/user'].role !== 'admin') {
    next({ name: 'home' })
  } else {
    next()
  }
}
