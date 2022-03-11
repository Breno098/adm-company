import Vue from 'vue';
import Vuetify from 'vuetify';

import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'

Vue.use(Vuetify)

// purple lighten-5 #F3E5F5

// purple darken-4 #4A148C

export default new Vuetify({
  theme: {
    themes: {
      light: {
        primary: '#311B92',
        secondary: '#e2e7f1',
        error: '#F44336',
        success: '#00BCD4',
        warning: '#E65100',

        sideBar: '#00B8D4',
        sidebarActive: '#000',

        btnSecondary: '#00838F',
        btnDanger: "#F44336",
        btnCleanFilter: '#E0E0E0',

        "btn-primary": '#311B92',
        "btn-delete": "#F44336",
        "btn-save": "#1B5E20",

        "side-bar": "#311B92",
        "side-bar-menu-active": "#651FFF",

        "icon-delete": "#B71C1C"
      },
    },
  },
})

