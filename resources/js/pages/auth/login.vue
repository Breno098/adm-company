<template>
     <v-container fluid fill-height style="background: #7986CB; background: linear-gradient(to bottom, #2196F3, #90CAF9, #E3F2FD);">
        <v-layout flex align-center justify-center>
            <v-flex xs12 sm8 elevation-6>
              <v-card >
                <v-toolbar class="d-flex flex-row justify-center" color="blue">
                  <v-toolbar-title>
                    <v-icon color="black">mdi-wrench</v-icon>
                    {{ appName }} | ENTRAR
                  </v-toolbar-title>
                </v-toolbar>
                  <v-card-text>
                    <v-text-field
                        v-model="form.email"
                        label="Email"
                        color="blue"
                        required
                        v-on:keyup.enter="login"
                        outlined
                        dense
                        prepend-inner-icon="mdi-email"
                    ></v-text-field>

                    <v-text-field
                        v-model="form.password"
                        label="Password"
                        color="blue"
                        required
                        type="password"
                        v-on:keyup.enter="login"
                        outlined
                        dense
                        prepend-inner-icon="mdi-lock-open"
                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="showPassword ? 'text' : 'password'"
                        @click:append="showPassword = !showPassword"
                    ></v-text-field>
  
                    <v-btn color="blue white--text" v-on:click="login" large>
                        Entrar
                    </v-btn>
                </v-card-text>
              </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import Cookies from 'js-cookie'
import axios from 'axios'

export default {
  layout: 'auth',
  middleware: 'guest',
  metaInfo () {
    return { title: 'Entrar' }
  },
  data: () => ({
    showPassword: false,
    form: {
      email: '',
      password: ''
    },
    remember: true,
    appName: window.config.appName,
  }),
  methods: {
    async login () {
      await axios.post('api/login', this.form)
      .then(async (response) => {
        this.$store.dispatch('auth/saveToken', {
          token: response.data.token,
          remember: this.remember
        })

        await this.$store.dispatch('auth/fetchUser');

        this.redirect();
      })
    },
    redirect () {
      const intendedUrl = Cookies.get('intended_url')

      if (intendedUrl) {
        Cookies.remove('intended_url')
        this.$router.push({ path: intendedUrl })
      } else {
        this.$router.push({ name: 'home' })
      }
    }
  }
}
</script>
