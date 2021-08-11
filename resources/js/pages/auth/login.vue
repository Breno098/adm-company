<template>
     <v-container fluid fill-height style="background: #7986CB; background: linear-gradient(to bottom, #2196F3, #90CAF9, #E3F2FD);">
        <v-layout flex align-center justify-center>
            <v-flex xs12 sm8 elevation-6>
                <v-card elevation="24" color="blue">
                    <v-row>
                        <v-col cols="4" class="py-0 mx-0 px-0">
                            <v-container
                                align-center
                                justify-space-around
                                fill-height
                                flex-column
                            >
                                <div>
                                    <v-icon dark class="text-h1">mdi-bulletin-board</v-icon>
                                </div>

                                <div class="white--text text-h5">
                                    {{ appName }}
                                </div>
                            </v-container>
                        </v-col>

                        <v-col cols="8" class="py-0 mx-0 px-0">
                            <v-card class="py-12 px-10">
                                <v-row>
                                    <v-col cols="12">
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
                                    </v-col>

                                    <v-col cols="12">
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
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12">
                                        <v-btn color="blue white--text" v-on:click="login" large>
                                            Entrar
                                        </v-btn>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </v-col>
                    </v-row>
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
