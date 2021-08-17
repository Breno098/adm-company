<template>
  <div>
    <v-card>
      <v-card-text>
        <v-text-field
          label="NOME"
          outlined
          dense
          v-model="form.name"
          :rules="[rules.required]"
          :error="errors.name && !form.name"
        ></v-text-field>

        <v-text-field
          label="EMAIL"
          outlined
          dense
          v-model="form.email"
          :rules="[rules.required]"
          :error="errors.email && !form.email"
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
import { mapGetters } from 'vuex';
import axios from 'axios';

export default {
  scrollToTop: false,
  metaInfo () {
    return { title: this.$t('settings') }
  },
  data: () => ({
    loading: false,
    dialog: {
      show: false,
      message: '',
      status: null
    },
    form:{
      name: '',
      email: ''
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    errors: {
      name: false,
      email: false
    },
  }),
  computed: mapGetters({
    user: 'auth/user'
  }),
  created () {
    this.form.name = this.user.name;
    this.form.email = this.user.email;
  },
  methods: {
     _modal(message, status, show = true){
      this.dialog.message = message;
      this.dialog.status = status;
      this.dialog.show = show;
    },
    async update () {
      if(!this.form.name){
        return this.errors.name = true;
      }

      if(!this.form.email){
        return this.errors.email = true;
      }

      this.loading = true;
      this.dialog.show = true;
      this.dialog.message = 'Atualizando...';

      const { data }  = await axios.patch('/api/settings/profile', this.form)

      this.loading = false;
      this._modal('Atualizado com sucesso', 'success');

      this.$store.dispatch('auth/updateUser', { user: data })
    }
  }
}
</script>
