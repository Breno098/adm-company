<template>
     <v-container fluid fill-height style="background: #7986CB; background: linear-gradient(to bottom, #2196F3, #90CAF9, #E3F2FD);">
         <fire-dialog ref="fireDialog"></fire-dialog>

        <v-layout flex align-center justify-center>
            <v-flex xs12 sm8 elevation-6>
              <v-card >
                <v-toolbar class="d-flex flex-row justify-center" color="primary">
                  <v-toolbar-title>
                    <v-icon color="black">mdi-wrench</v-icon>
                    {{ appName }} | ENTRAR
                  </v-toolbar-title>
                </v-toolbar>
                  <v-card-text>
                    <v-text-field
                        v-model="form.email"
                        label="Email"
                        color="primary"
                        required
                        v-on:keyup.enter="login"
                        outlined
                        dense
                        prepend-inner-icon="mdi-email"
                    ></v-text-field>

                    <v-text-field
                        v-model="form.password"
                        label="Password"
                        color="primary"
                        required
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
      this.$refs.fireDialog.loading({
        title: 'Entrando',
        message: 'Estamos buscando seus dados'
      })

      await axios
              .post('api/login', this.form)
              .then(async (response) => {
                this.$store.dispatch('auth/saveToken', {
                  token: response.data.token,
                  remember: this.remember
                })

                this.$refs.fireDialog.success({ title: 'Entrando' })

                await this.$store.dispatch('auth/fetchUser');

                this.redirect();
              })
              .catch(error => {
                this.$refs.fireDialog.error({
                  title: 'Erro',
                  message: 'Não encontramos seus dados. Verifique seu usuário e senha por favor.'
                })
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
