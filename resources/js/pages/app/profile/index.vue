<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-row>
      <v-col cols="12">
        <v-card elevation="0">
          <v-toolbar elevation="0" class="mb-2">
            <v-toolbar-title> {{ titlePage }} </v-toolbar-title>
            <v-progress-linear
              color="primary"
              indeterminate
              height="4"
              bottom
              absolute
              :active="loading"
            ></v-progress-linear>

            <v-spacer></v-spacer>

            <v-btn
              color="primary"
              @click="_goUpdatePass"
              :loading="loading"
              rounded
              dark
              small
              class="mr-3"
            >
              Senhas <v-icon dark class="ml-2" small>mdi-lock</v-icon>
            </v-btn>

            <v-btn
              color="btnPrimary"
              @click="_store"
              :loading="loading"
              rounded
              dark
              small
            >
              Atualizar <v-icon dark class="ml-2" small>mdi-content-save</v-icon>
            </v-btn>
          </v-toolbar>

          <v-row>
            <v-col cols="12">
              <v-text-field
                label="NOME"
                color="primary"
                outlined
                dense
                v-model="form.name"
                :rules="[rules.required]"
                :error="errors.name"
              ></v-text-field>
            </v-col>

            <v-col cols="12">
              <v-text-field
                label="EMAIL"
                outlined
                dense
                v-model="form.email"
                :rules="[rules.required]"
                :error="errors.email"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="btnPrimary"
              @click="_store"
              :loading="loading"
              rounded
              dark
            >
              Atualizar <v-icon dark class="ml-2">mdi-content-save</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
         </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import axios from 'axios';

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: this.titlePage }
  },
  data: () => ({
    showPassword: false,
    showConfirmPassword: false,
    loading: false,
    errors: {
      name: false,
      email: false,
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    form:{
      name: '',
      email: '',
    },
  }),
  computed: {
    titlePage(){
      return 'Perfil';
    },
    ...mapGetters({
      user: 'auth/user'
    })
  },
  mounted(){
  },
  created () {
    this.form.name = this.user.name;
    this.form.email = this.user.email;
  },
  methods: {
    _goUpdatePass(){
      this.$router.push({
          name: 'settings-user-password',
      })
    },
    async _store(){
      for (const field in this.errors) {
        if(!this.form[field])
          return this.errors[field] = true;
      }

      this.loading = true;

      this.$refs.fireDialog.loading({ title:'Atualizando...' })

      await axios
              .patch('/api/profile/update', this.form)
              .then(response => {
                this.$refs.fireDialog.success({ title: 'Perfil atualizado' });
                this.$store.dispatch('auth/updateUser', { user: response.data })
              })
              .catch(error => {
                this.$refs.fireDialog.error({ title: 'Error ao atualizar perfil' })
              })

      this.loading = false;
    },
  }

}
</script>
