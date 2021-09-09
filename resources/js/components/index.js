import Vue from 'vue'
import Child from './Child'
import { HasError, AlertError, AlertSuccess } from 'vform'

import Modal from './Modal'

// Components that are registered globaly.
[
  Child,
  HasError,
  AlertError,
  AlertSuccess,
].forEach(Component => {
  Vue.component(Component.name, Component)
})
