import * as types from '../mutation-types'

// state
export const state = {
  show: false,
  title: null,
  loading: false,
  status: null
}

// getters
export const getters = {
  show: state => state.show,
  title: state => state.title,
  loading: state => state.loading,
  status: state => state.status,
}

// mutations
export const mutations = {
  [types.SHOW_MODAL_SYSTEM] (state, { title, status, loading }) {
    state.title = title;
    state.status = status
    state.loading = loading;
    state.show = true;
  },

  [types.HIDE_MODAL_SYSTEM] (state) {
    state.show = false;
    state.title = null
  },
}

// actions
export const actions = {
  success ({ commit }, { title }) {
    commit(types.SHOW_MODAL_SYSTEM, { title, status: 'success' , loading: false});
    setTimeout(() => commit(types.HIDE_MODAL_SYSTEM), 1500);
  },

  error ({ commit }, { title }) {
    commit(types.SHOW_MODAL_SYSTEM, { title, status: 'error', loading: false });
    setTimeout(() => commit(types.HIDE_MODAL_SYSTEM), 1500);
  },

  loading ({ commit }, { title }) {
    commit(types.SHOW_MODAL_SYSTEM, { title, status : null, loading: true });
  },

  hide ({ commit }) {
    commit(types.HIDE_MODAL_SYSTEM)
  },
}
