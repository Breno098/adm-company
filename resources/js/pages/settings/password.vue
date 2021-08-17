<template>
  <div>
    <v-card>
      <v-card-text>
        <v-text-field
          :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
          :type="showPassword ? 'text' : 'password'"
          @click:append="showPassword = !showPassword"
          label="NOVA SENHA"
          outlined
          dense
          v-model="form.password"
          :rules="[rules.required]"
          :error="errors.password && !form.password"
        ></v-text-field>

        <v-text-field
          :append-icon="showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'"
          :type="showConfirmPassword ? 'text' : 'password'"
          @click:append="showConfirmPassword = !showConfirmPassword"
          label="CONFIRME A SENHA"
          outlined
          dense
          v-model="form.password_confirmation"
          :rules="[rules.required]"
          :error="errors.password_confirmation && !form.password_confirmation"
        ></v-text-field>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="green" @click="update">
          Atualizar <v-icon dark class="ml-2">mdi-refresh</v-icon>
        </v-btn>
      </v-card-actions>
    </v-card>

    <v-dialog v-model="dialog.show" max-width="350">
      <v-card>
          <v-card-title>
              <div class="mx-auto"> {{ dialog.message }} </div>
          </v-card-title>

          <v-card-text class="text-center py-5" >
            <v-progress-circular v-if="loading" :width="7" color="blue" :size="70" indeterminate></v-progress-circular>
            <v-icon v-else :size="70" :color="dialog.status === 'success' ? 'green' : 'red' ">
              {{ dialog.status === 'success' ? 'mdi-check' : 'mdi-alert' }}
            </v-icon>
          </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  scrollToTop: false,
  metaInfo () {
    return { title: 'Senha' }
  },
  data: () => ({
    showPassword: false,
    showConfirmPassword: false,
    loading: false,
    dialog: {
      show: false,
      message: '',
      status: null
    },
    form: {
      password: '',
      password_confirmation: ''
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    errors: {
      password: false,
      password_confirmation: false
    },
  }),
  methods: {
    _modal(message, status, show = true){
      this.dialog.message = message;
      this.dialog.status = status;
      this.dialog.show = show;
    },
    async update () {
       if(!this.form.password){
        return this.errors.password = true;
      }

      if(!this.form.password_confirmation){
        return this.errors.password_confirmation = true;
      }

      this.loading = true;
      this.dialog.show = true;
      this.dialog.message = 'Atualizando...';

      await axios.patch('/api/settings/password', this.form)

      this.form.password = '';
      this.form.password_confirmation = '';

      this.loading = false;
      this._modal('Atualizado com sucesso', 'success');

      this.$router.go(-1);
    }
  }
}
</script>
