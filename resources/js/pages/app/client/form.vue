<template>
  <div>
    <v-row class="justify-center mx-5 my-5">
        <v-col cols="12">
            <v-card class="transparent" elevation="0">
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    label="NOME"
                    color="blue"
                    outlined
                    dense
                    v-model="client.name"
                    :loading="loading"
                    :rules="[rules.required]"
                    :error="errors.name"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    label="NOME FANTASIA"
                    color="blue"
                    outlined
                    dense
                    v-model="client.fantasy_name"
                    :loading="loading"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    label="DOCUMENTO"
                    color="blue"
                    v-mask="client.type === 'PJ' ? '##.###.###/####-##' : '###.###.###-##' "
                    outlined
                    dense
                    v-model="client.document"
                    :loading="loading"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-menu
                    :loading="loading"
                    v-model="menuBirthDate"
                    :close-on-content-click="false"
                    max-width="290"
                    transition="scale-transition"
                    offset-y
                    color="blue"
                    outlined
                    dense
                  >
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            prepend-inner-icon="mdi-calendar"
                            :value="birthDateFormat"
                            clearable
                            label="Data"
                            readonly
                            v-bind="attrs"
                            color="blue"
                            v-on="on"
                            @click:clear="client.birth_date = null"
                            outlined
                            dense
                        ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="client.birth_date"
                        @change="menuBirthDate = false"
                        no-title
                        crollable
                        color="blue"
                    ></v-date-picker>
                  </v-menu>
                </v-col>

                <v-col cols="12" md="6">
                   <v-select
                      v-model="client.type"
                      vali
                      :items="['PF', 'PJ']"
                      label="TIPO"
                      color="blue"
                      outlined
                      dense
                      :loading="loading"
                      :rules="[rules.required]"
                      :error="errors.type"
                  ></v-select>
                </v-col>

                <v-col cols="12">
                    <v-btn color="green darken-1" @click="_store" :loading="loading">
                        Salvar &nbsp; <v-icon dark>mdi-content-save</v-icon>
                    </v-btn>
                </v-col>
              </v-row>
            </v-card>
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
              {{ dialog.status === 'success' ? 'mdi-check' : 'mdi-alert-decagram' }}
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
    return { title: 'Cliente' }
  },
  data: () => ({
    loading: false,
    menuBirthDate: false,
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
    client: {
      birth_date: null,
      document: null,
      fantasy_name: null,
      name: null,
      notes: null,
      status_id: null,
      type: 'PF'
    }
  }),
  computed: {
    birthDateFormat () {
        return this.client.birth_date ? moment(this.client.birth_date).format('DD/MM/YYYY') : ''
    },
  },
  mounted(){
    if(this.$route.params.id){
      this._load();
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
