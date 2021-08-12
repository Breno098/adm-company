<template>
  <div>
    <v-row>
      <v-col cols="12">
       
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <v-btn color="green" @click="_store" :loading="loading">
          Salvar <v-icon dark>mdi-content-save</v-icon>
        </v-btn>
      </v-col>
    </v-row>

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
import moment from 'moment';

export default {
  metaInfo () {
    return { title: 'Compromisso' }
  },
  data: () => ({
    tab: null,
    loading: false,
    dialog: {
      show: false,
      message: '',
      status: null
    },
    errors: {
      name: false,
      type: false
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
  }),
  computed: {
  },
  mounted(){
    if(this.$route.params.id){
      // this._load();
    }
  },
  methods: {
    _modal(message, status){
      this.dialog.message = message;
      this.dialog.status = status;
      this.dialog.show = true;
    },
    async _load(){
      let id = this.$route.params.id;

      this.loading = true;

      await axios.get(`api/client/${id}`).then(response => {
        if(response.data.success){
          return this.client = response.data.data;
        }

        this._modal('Error ao carregar dados cliente', 'error');
        setTimeout(() => this.$router.push({ name: 'client.index' }), 1500);
      });

      this.loading = false;

    },
    async _store(){
      this.tab = 0;

      if(!this.client.name){
        return this.errors.name = true;
      }

      if(!this.client.type){
        return this.errors.type = true;
      }

      let id = this.$route.params.id;

      this.loading = true;
      this.dialog.show = true;
      this.dialog.message = id ? 'Atualizando...' : 'Salvando...';

      let response = null;

      if(!id){
        response = await axios.post('api/client', this.client)
      } else {
        response = await axios.put(`api/client/${id}`, this.client)
      }

      this.loading = false;

      if(response.data.success){
        this._modal('Cliente salvo com sucesso', 'success');
        return setTimeout(() => this.$router.push({ name: 'client.index' }), 1500);
      }

      this._modal('Error aos salvar cliente', 'error');
    },
  }

}
</script>

<style>

</style>
