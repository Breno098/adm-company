<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-row>
      <v-col cols="12">
        <v-card elevation="0">
          <v-toolbar elevation="0" class="mb-2">
            <v-toolbar-title> {{ titlePage }} </v-toolbar-title>
            <v-progress-linear
              color="blue"
              indeterminate
              height="4"
              bottom
              absolute
              :active="loading"
            ></v-progress-linear>

            <v-spacer></v-spacer>

            <v-btn
              color="green" 
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
                :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                :type="showPassword ? 'text' : 'password'"
                @click:append="showPassword = !showPassword"
                label="NOVA SENHA"
                outlined
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
                outlined
                dense
                v-model="form.password_confirmation"
                :rules="[rules.required]"
                :error="errors.password_confirmation"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn 
              color="green" 
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
