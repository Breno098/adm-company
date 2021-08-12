import * as types from '../mutation-types'

// state
export const state = {
  order: null,
}

// getters
export const getters = {
  order: state => state.order,
}

// mutations
export const mutations = {
  [types.SET_ORDER] (state, { order }) {
    state.order = order
  },
}

// actions
export const actions = {
  setData ({ commit }, { order }) {
    commit(types.SET_ORDER, order)
  },
}
