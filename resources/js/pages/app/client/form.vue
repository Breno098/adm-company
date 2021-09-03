<template>
  <div>
    <v-tabs v-model="tab">
      <v-tabs-slider color="blue"></v-tabs-slider>
      <v-tab>Informações <v-icon class="ml-2">mdi-information</v-icon></v-tab>
      <v-tab>Contatos    <v-icon class="ml-2">mdi-phone</v-icon></v-tab>
      <v-tab>Endereço    <v-icon class="ml-2">mdi-city</v-icon></v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab" class="pt-5 px-3">
      <!-- Informações -->
      <v-tab-item>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field
              label="NOME"
              outlined
              dense
              v-model="client.name"
              :loading="loading"
              :rules="[rules.required]"
              :error="errors.name && !client.name"
              @input="client.name = client.name.toUpperCase()"
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="6">
            <v-text-field
              label="NOME FANTASIA"
              outlined
              dense
              v-model="client.fantasy_name"
              :loading="loading"
              @input="client.fantasy_name = client.fantasy_name.toUpperCase()"
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="6">
            <v-select
              v-model="client.category_id"
              :items="categories"
              item-text="name"
              item-value="id"
              label="TIPO DE CLIENTE"
              outlined
              dense
              :loading="loading"
            ></v-select>
          </v-col>

          <v-col cols="12" md="6">
            <v-text-field
              label="CNPJ"
              v-mask="'##.###.###/####-##'"
              outlined
              dense
              v-model="client.cnpj"
              :loading="loading"
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="6">
            <v-text-field
              label="CPF"
              v-mask="'###.###.###-##'"
              outlined
              dense
              v-model="client.cpf"
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
              ></v-date-picker>
            </v-menu>
          </v-col>
        </v-row>
      </v-tab-item>

      <!-- Contatos -->
      <v-tab-item>
        <v-row>
          <v-col cols="12" v-for="(contact, index) in client.contacts" :key="contact.id">
            <v-row>
              <v-col cols="12" class="d-flex flex-row justify-end">
                <v-btn color="red" @click="client.contacts.splice(index, 1);" :loading="loading" small rounded>
                  <v-icon color="red darken-4">mdi-delete</v-icon>
                </v-btn>
              </v-col>

              <v-col cols="12" md="6">
                <v-select
                  v-model="contact.type"
                  :items="contact_types"
                  label="TIPO"
                  outlined
                  dense
                  :loading="loading"
                ></v-select>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  label="CONTATO"
                  outlined
                  dense
                  v-model="contact.contact"
                  :loading="loading"
                  v-mask="'(##) #####-####'"
                  v-if="contact.type === 'CELULAR' || contact.type === 'WHATSAPP'"
                ></v-text-field>

                <v-text-field
                  @input="contact.contact = contact.contact.toUpperCase()"
                  label="CONTATO"
                  outlined
                  dense
                  v-model="contact.contact"
                  :loading="loading"
                   v-mask="'(##) ####-####'"
                  v-else-if="contact.type === 'TELEFONE'"
                ></v-text-field>

                <v-text-field
                  @input="contact.contact = contact.contact.toUpperCase()"
                  label="CONTATO"
                  outlined
                  dense
                  v-model="contact.contact"
                  :loading="loading"
                  v-else
                ></v-text-field>
              </v-col>
            </v-row>

            <v-divider color="grey" class="mx-5" v-if="(index + 1) < client.contacts.length"/>
          </v-col>

          <v-col cols="12" class="d-flex flex-row justify-end">
            <v-btn color="green" @click="client.contacts.push({})" :loading="loading" small rounded>
              Adicionar contato <v-icon>mdi-plus</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </v-tab-item>

      <!-- Endereço -->
      <v-tab-item>
        <v-row>
          <v-col cols="12" v-for="(address, index) in client.addresses" :key="address.id">
            <v-row>
              <v-col cols="12" class="d-flex flex-row justify-end">
                <v-btn color="red" @click="client.addresses.splice(index, 1);" :loading="loading" small rounded>
                  <v-icon color="red darken-4">mdi-delete</v-icon>
                </v-btn>
              </v-col>

              <v-col cols="12" md="3">
                <v-text-field
                  label="CEP"
                  v-mask="'#####-###'"
                  outlined
                  dense
                  v-model="address.cep"
                  :loading="loading"
                  v-on:keyup.enter="_searchCep(index)"
                  v-on:keyup="_searchCep(index)"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  label="RUA"
                  outlined
                  dense
                  v-model="address.street"
                  :loading="loading"
                  @input="address.street = address.street.toUpperCase()"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="3">
                <v-text-field
                  label="NÚMERO"
                  outlined
                  dense
                  v-model="address.number"
                  :loading="loading"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  label="BAIRRO"
                  outlined
                  dense
                  v-model="address.district"
                  :loading="loading"
                  @input="address.district = address.district.toUpperCase()"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="4">
                <v-text-field
                  label="CIDADE"
                  outlined
                  dense
                  v-model="address.city"
                  :loading="loading"
                  @input="address.city = address.city.toUpperCase()"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="2">
                <v-text-field
                  label="ESTADO"
                  outlined
                  dense
                  v-model="address.state"
                  :loading="loading"
                  @input="address.state = address.state.toUpperCase()"
                ></v-text-field>
              </v-col>

              <v-col cols="6" md="2">
                <v-text-field
                  label="APARTAMENTO"
                  outlined
                  dense
                  v-model="address.apartment"
                  :loading="loading"
                  @input="address.apartment = address.apartment.toUpperCase()"
                ></v-text-field>
              </v-col>

              <v-col cols="6" md="2">
                <v-text-field
                  label="BLOCO"
                  outlined
                  dense
                  v-model="address.block"
                  :loading="loading"
                  @input="address.block = address.block.toUpperCase()"
                ></v-text-field>
              </v-col>

              <v-col cols="6" md="2">
                <v-text-field
                  label="ANDAR"
                  outlined
                  dense
                  v-model="address.floor"
                  :loading="loading"
                  @input="address.floor = address.floor.toUpperCase()"
                ></v-text-field>
              </v-col>

              <v-col cols="6" md="2">
                <v-text-field
                  label="TORRE"
                  outlined
                  dense
                  v-model="address.tower"
                  :loading="loading"
                  @input="address.tower = address.tower.toUpperCase()"
                ></v-text-field>
              </v-col>

              <v-col cols="6" md="2">
                <v-text-field
                  label="CASA"
                  outlined
                  dense
                  v-model="address.house"
                  :loading="loading"
                  @input="address.house = address.house.toUpperCase()"
                ></v-text-field>
              </v-col>

              <v-col cols="12">
                <v-text-field
                  label="COMPLEMENTO"
                  outlined
                  dense
                  v-model="address.complement"
                  :loading="loading"
                  @input="address.complement = address.complement.toUpperCase()"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-divider color="grey" class="mx-5" v-if="(index + 1) < client.addresses.length"/>
          </v-col>

          <v-col cols="12" class="d-flex flex-row justify-end">
            <v-btn color="green" @click="client.addresses.push({})"  :loading="loading" small rounded>
              Adicionar endereço <v-icon>mdi-plus</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </v-tab-item>
    </v-tabs-items>

    <v-row class="pt-2 px-3">
      <v-col cols="12">
        <v-btn color="green" @click="_store" :loading="loading" rounded>
          Salvar <v-icon dark class="ml-2">mdi-content-save</v-icon>
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
            <v-icon v-else :size="70" :color="dialog.status === 'success' ? 'green' : dialog.status === 'error' ? 'red' : '' ">
              {{ dialog.status === 'success' ? 'mdi-check' : dialog.status === 'error' ? 'mdi-alert' : '' }}
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
  middleware: 'auth',
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
      status: null,
    },
    errors: {
      name: false,
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    client: {
      birth_date: null,
      cnpj: null,
      cpf: null,
      category_id: null,
      fantasy_name: null,
      name: null,
      notes: null,
      status_id: null,
      type: null,
      addresses: [{}],
      contacts: [{}]
    },
    contact_types: [
      'CELULAR',
      'EMAIL',
      'TELEFONE',
      'WHATSAPP',
      'OUTROS'
    ],
    categories: []
  }),
  computed: {
    birthDateFormat () {
      return this.client.birth_date ? moment(this.client.birth_date).format('DD/MM/YYYY') : ''
    },
  },
  mounted(){
    this._start();
  },
  methods: {
    async _start(){
      if(this.$route.params.id){
        await this._load();
      }

      await this._loadCategories();
    },
    _showModal(message, status = ''){
      this.dialog.message = message;
      this.dialog.status = status;
      this.dialog.show = true;
    },
    _hideModal(){
      this.dialog.show = false;
    },
    async _loadCategories(){
      this.loading = true;
      await axios.get(`api/category?type=client`).then(response => {
        if(response.data.success){
          return this.categories = response.data.data;
        }
        this._showModal('Error ao carregar categorias', 'error');
      });
      this.loading = false;
    },
    async _searchCep(indexAddress){
      let cep = this.client.addresses[indexAddress].cep.replace('-', '');

      if(cep.length != 8)
        return;

      this.loading = true;
      this._showModal('Buscando endereço');

      let params = { cep };
      await axios.get(`api/address/searchCep`, { params }).then(response => {
        if(response.data.data.erro){
          this.loading = false;
          return this._showModal('Endereço não encontrado', 'error');
        }

        let { logradouro, bairro, localidade, uf } = response.data.data;

        this.client.addresses[indexAddress].street = logradouro.toUpperCase();
        this.client.addresses[indexAddress].district = bairro.toUpperCase();
        this.client.addresses[indexAddress].city = localidade.toUpperCase();
        this.client.addresses[indexAddress].state = uf.toUpperCase();

        this._hideModal();
        this.loading = false;
      }).catch(error => {
        this.loading = false;
        this._showModal('Error ao carregar endereço', 'error');
      })
    },
    async _load(){
      const id = this.$route.params.id;

      this.loading = true;
      await axios.get(`api/client/${id}`).then(response => {
        if(response.data.success){
          return this.client = response.data.data;
        }
        this._showModal('Error ao carregar dados cliente', 'error');
        setTimeout(() => this.$router.push({ name: 'client.index' }), 1500);
      });
      this.loading = false;
    },
    async _store(){
      this.tab = 0;

      for (const field in this.errors) {
        if(!this.client[field])
          return this.errors[field] = true;
      }

      let id = this.$route.params.id;

      this.loading = true;
      this._showModal(id ? 'Atualizando...' : 'Salvando...');

      const response = !id ? await axios.post('api/client', this.client) : await axios.put(`api/client/${id}`, this.client);

      this.loading = false;

      if(response.data.success){
        this._showModal('Cliente salvo com sucesso', 'success');
        return setTimeout(() => this.$router.push({ name: 'client.index' }), 1500);
      }

      this._showModal('Error aos salvar cliente', 'error');
    },
  }
}
</script>