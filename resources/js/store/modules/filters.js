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
  [types.SET_FILTER] (state, { filter }) {
    state.order = filter
  },
}

// actions
export const actions = {
  setFilterOrder ({ commit }, { filter }) {
    commit(types.SET_FILTER, { filter })
  },
}
