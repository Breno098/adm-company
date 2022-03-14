import * as types from '../mutation-types'

// state
export const state = {
  orders: null,
}

// getters
export const getters = {
  orders: state => state.order,
}

// mutations
export const mutations = {
  [types.SET_FILTER] (state, { order }) {
    state.order = order
  },
}

// actions
export const actions = {
  setData ({ commit }, { order }) {
    commit(types.SET_FILTER, { order })
  },
}
