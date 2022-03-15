<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>.

    <p class="font-weight-bold mb-5 text-h5">
      <v-icon color="primary">mdi-lock</v-icon>
      {{ titlePage }}
    </p>

    <v-row class="mb-2">
      <v-col cols="6" md="10">
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
              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              :type="showPassword ? 'text' : 'password'"
              @click:append="showPassword = !showPassword"
              label="NOVA SENHA"
              filled
              dense
              v-model="form.password"
              :rules="[rules.required]"
              :error="errors.password"
            ></v-text-field>
          </v-col>

          <v-col cols="12">
            <v-text-field
              :append-icon="showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'"
              :type="showConfirmPassword ? 'text' : 'password'"
              @click:append="showConfirmPassword = !showConfirmPassword"
              label="CONFIRME A SENHA"
              filled
              dense
              v-model="form.password_confirmation"
              :rules="[rules.required]"
              :error="errors.password_confirmation"
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
import moment from 'moment';

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
      password: false,
      password_confirmation: false
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    form:{
      password: '',
      password_confirmation: ''
    },
  }),
  computed: {
    titlePage(){
      return 'Senhas';
    },
    ...mapGetters({
      user: 'auth/user'
    })
  },
  mounted(){
  },
  methods: {
    async _store(){
      for (const field in this.errors) {
        if(!this.form[field])
          return this.errors[field] = true;
      }

      if(this.form.password !== this.form.password_confirmation){
        this.$refs.fireDialog.error({
          title: 'Senhas incorretas',
          message: 'Verifique se as senhas estão corretas'
        })
        return;
      }

      if(this.form.password.split('').length < 6){
        this.$refs.fireDialog.error({
          title: 'Quantidade de caracteres',
          message: 'A senha deve conter no minimo 6 caracteres'
        })
        return;
      }

      this.loading = true;

      this.$refs.fireDialog.loading({ title:'Atualizando...' })

      await axios
              .patch('/api/profile/password', this.form)
              .then(response => {
                this.$refs.fireDialog.success({ title: 'Perfil atualizado' });
              })
              .catch(error => {
                this.$refs.fireDialog.error({ title: 'Error ao atualizar perfil' })
              })

        this.loading = false;

        this.form.password = '';
        this.form.password_confirmation = '';

        for (const field in this.errors) {
          this.errors[field] = false;
        }
    },
  }

}
</script>
