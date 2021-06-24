<template>
  <div>
    <v-tabs v-model="tab" >
      <v-tabs-slider color="blue"></v-tabs-slider>
      <v-tab>Informações <v-icon class="ml-2">mdi-information</v-icon></v-tab>
      <v-tab>Contatos    <v-icon class="ml-2">mdi-phone</v-icon></v-tab>
      <v-tab>Endereço    <v-icon class="ml-2">mdi-city</v-icon></v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab">
      <!-- Informações -->
      <v-tab-item>
        <v-row class="mt-5">
          <v-col cols="12">
            <v-text-field
              label="NOME"
              color="blue"
              outlined
              dense
              v-model="client.name"
              :loading="loading"
              :rules="[rules.required]"
              :error="errors.name && !client.name"
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
              v-mask="'##.###.###/####-##'"
              outlined
              dense
              v-model="client.document"
              :loading="loading"
              v-if="client.type === 'PJ'"
            ></v-text-field>

              <v-text-field
              label="DOCUMENTO"
              color="blue"
              v-mask="'###.###.###-##' "
              outlined
              dense
              v-model="client.document"
              :loading="loading"
              v-else
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
                  label="DATA DE NASCIMENTO"
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
              :error="errors.type && !client.type"
            ></v-select>
          </v-col>
        </v-row>
      </v-tab-item>

      <!-- Contatos -->
      <v-tab-item>
        <v-row class="mt-5">
          <v-col cols="12" v-for="(contact, index) in client.contacts" :key="contact.id">
            <v-row>
              <v-col cols="12" class="d-flex flex-row justify-end">
                <v-btn color="red" @click="client.contacts.splice(index, 1);" :loading="loading" small>
                  <v-icon color="red darken-4">mdi-delete</v-icon>
                </v-btn>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  label="CONTATO"
                  color="blue"
                  outlined
                  dense
                  v-model="contact.contact"
                  :loading="loading"
                  v-mask="'(##) #####-####'"
                  v-if="contact.type === 'Celular' || contact.type === 'Telefone' || contact.type === 'WhatsApp'"
                ></v-text-field>

                <v-text-field
                  label="CONTATO"
                  color="blue"
                  outlined
                  dense
                  v-model="contact.contact"
                  :loading="loading"
                  v-else
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-select
                  v-model="contact.type"
                  vali
                  :items="contact_types"
                  label="TIPO"
                  color="blue"
                  outlined
                  dense
                  :loading="loading"
                ></v-select>
              </v-col>
            </v-row>

            <v-divider color="grey" class="mx-5" v-if="(index + 1) < client.contacts.length"/>
          </v-col>

          <v-col cols="12" class="d-flex flex-row justify-end">
            <v-btn color="green" @click="client.contacts.push({})" :loading="loading" small>
              Adicionar contato <v-icon>mdi-plus</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </v-tab-item>

      <!-- Endereço -->
      <v-tab-item>
        <v-row class="mt-5">
          <v-col cols="12" v-for="(address, index) in client.addresses" :key="address.id">
            <v-row>
              <v-col cols="12" class="d-flex flex-row justify-end">
                <v-btn color="red" @click="client.addresses.splice(index, 1);" :loading="loading" small>
                  <v-icon color="red darken-4">mdi-delete</v-icon>
                </v-btn>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  label="RUA"
                  color="blue"
                  outlined
                  dense
                  v-model="address.street"
                  :loading="loading"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="3">
                <v-text-field
                  label="CEP"
                  color="blue"
                  outlined
                  dense
                  v-model="address.cep"
                  :loading="loading"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="3">
                <v-text-field
                  label="NÚMERO"
                  color="blue"
                  outlined
                  dense
                  v-model="address.number"
                  :loading="loading"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  label="BAIRRO"
                  color="blue"
                  outlined
                  dense
                  v-model="address.district"
                  :loading="loading"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  label="CIDADE"
                  color="blue"
                  outlined
                  dense
                  v-model="address.city"
                  :loading="loading"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="3">
                <v-text-field
                  label="ESTADO"
                  color="blue"
                  outlined
                  dense
                  v-model="address.state"
                  :loading="loading"
                ></v-text-field>
              </v-col>

              <v-col cols="6" md="3">
                <v-text-field
                  label="APARTAMENTO"
                  color="blue"
                  outlined
                  dense
                  v-model="address.apartment"
                  :loading="loading"
                ></v-text-field>
              </v-col>

              <v-col cols="6" md="3">
                <v-text-field
                  label="ANDAR"
                  color="blue"
                  outlined
                  dense
                  v-model="address.floor"
                  :loading="loading"
                ></v-text-field>
              </v-col>

              <v-col cols="12">
                <v-text-field
                  label="COMPLEMENTO"
                  color="blue"
                  outlined
                  dense
                  v-model="address.complement"
                  :loading="loading"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-divider color="grey" class="mx-5" v-if="(index + 1) < client.addresses.length"/>
          </v-col>

          <v-col cols="12" class="d-flex flex-row justify-end">
            <v-btn color="green" @click="client.addresses.push({})" :loading="loading" small>
              Adicionar endereço <v-icon>mdi-plus</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </v-tab-item>
    </v-tabs-items>

    <v-row>
      <v-col cols="12">
        <v-btn color="green" @click="_store" :loading="loading">
          Salvar &nbsp; <v-icon dark>mdi-content-save</v-icon>
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
    return { title: 'Cliente' }
  },
  data: () => ({
    tab: null,
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
      type: null,
      addresses: [{}],
      contacts: [{}]
    },
    contact_types: [
      'Celular',
      'Email',
      'Telefone',
      'WhatsApp',
      'Outros'
    ]
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
