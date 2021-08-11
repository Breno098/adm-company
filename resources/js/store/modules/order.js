import * as types from '../mutation-types'

// state
export const state = {
  budget: null,
}

// getters
export const getters = {
  budget: state => state.budget,
}

// mutations
export const mutations = {
  [types.SET_BUDGET] (state, budget) {
    state.budget = budget
  },
}

// actions
export const actions = {
  setBudget ({ commit }, budget) {
    commit(types.SET_BUDGET, budget)
  },
}
