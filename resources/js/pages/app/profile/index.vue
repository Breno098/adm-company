<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <p class="font-weight-bold mb-5 text-h5">
      <v-icon color="primary">mdi-account</v-icon>
      {{ titlePage }}
    </p>

     <v-row class="mb-2">
      <v-col cols="6" md="8">
        <v-btn
          color="btn-primary"
          small
          text
          @click="$router.go(-1)"
        >
          Voltar <v-icon>mdi-chevron-double-left</v-icon>
        </v-btn>
      </v-col>

      <v-col cols="6" md="2">
        <v-btn
          color="btn-primary"
          class="rounded-lg"
          block
          small
          dark
          @click="_goUpdatePass"
          :loading="loading"
        >
          Senhas <v-icon class="ml-2" small>mdi-lock</v-icon>
        </v-btn>
      </v-col>

      <v-col cols="6" md="2">
        <v-btn
          color="btn-primary"
          class="rounded-lg"
          block
          small
          dark
          @click="_store"
          :loading="loading"
        >
          Salvar <v-icon class="ml-2" small>mdi-content-save</v-icon>
        </v-btn>
      </v-col>
    </v-row>

    <v-card>
       <v-progress-linear
        indeterminate
        top
        absolute
        :active="loading"
      ></v-progress-linear>

      <v-card-text>
        <v-row>
          <v-col cols="12">
            <v-text-field
              label="NOME"
              color="primary"
              filled
              dense
              v-model="form.name"
              :rules="[rules.required]"
              :error="errors.name"
            ></v-text-field>
          </v-col>

          <v-col cols="12">
            <v-text-field
              label="EMAIL"
              filled
              dense
              v-model="form.email"
              :rules="[rules.required]"
              :error="errors.email"
            ></v-text-field>
          </v-col>
        </v-row>
      </v-card-text>

      <v-card-actions>
        <v-btn
          color="btn-primary"
          class="rounded-lg"
          small
          dark
          @click="_store"
          :loading="loading"
        >
          Salvar <v-icon small class="ml-2">mdi-content-save</v-icon>
        </v-btn>
      </v-card-actions>
    </v-card>
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
