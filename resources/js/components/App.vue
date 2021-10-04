<template>
  <div id="app">
    <loading ref="loading" />

    <transition name="page" mode="out-in">
      <component :is="layout" v-if="layout" />
    </transition>
  </div>
</template>

<script>
import Loading from './Loading'

// Load layout components dynamically.
const requireContext = require.context('~/layouts', false, /.*\.vue$/)

const layouts = requireContext.keys()
  .map(file =>
    [file.replace(/(^.\/)|(\.vue$)/g, ''), requireContext(file)]
  )
  .reduce((components, [name, component]) => {
    components[name] = component.default || component
    return components
  }, {})

export default {
  el: '#app',
  components: {
    Loading
  },
  data: () => ({
    layout: null,
    defaultLayout: 'default'
  }),
  metaInfo () {
    const { appName } = window.config
    return {
      title: appName,
      titleTemplate: `%s · ${appName}`
    }
  },
  mounted () {
    this.$loading = this.$refs.loading;
  },
  methods: {
    setLayout (layout) {
      if (!layout || !layouts[layout]) {
        layout = this.defaultLayout
      }
      this.layout = layouts[layout]
    }
  }
}
</script>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap');

  * {
    font-family: 'Ubuntu Mono', monospace;
  }

  .v-application ::-webkit-scrollbar {
    height: 8px;
    width: 13px;
  }

  .v-application ::-webkit-scrollbar-corner {
    background: transparent;
  }

  .v-application ::-webkit-scrollbar-thumb {
    background: #2196F3;
    border-radius: 8px;
  }
</style>
