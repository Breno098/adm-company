import * as types from '../mutation-types'

// state
export const state = {
  company: null,
}

// getters
export const getters = {
  company: state => state.company,
}

// mutations
export const mutations = {
  [types.SET_COMPANY] (state, { company }) {
    state.company = company
  },
}

// actions
export const actions = {
  setData ({ commit }, { company }) {
    commit(types.SET_COMPANY, { company })
  },
}
