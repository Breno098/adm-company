<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-card class="mb-4">
      <v-toolbar elevation="0">
        <v-toolbar-title> {{ titlePage }} </v-toolbar-title>
        <v-progress-linear
          indeterminate
          height="4"
          bottom
          absolute
          :active="loading"
        ></v-progress-linear>

        <v-spacer></v-spacer>

        <v-btn
          v-if="(!idByRoute && $role.client.add()) || (idByRoute && $role.client.update()) "
          color="btnPrimary"
          @click="_store"
          :loading="loading"
          rounded
          small
        >
          Salvar <v-icon class="ml-2">mdi-content-save</v-icon>
        </v-btn>
      </v-toolbar>
    </v-card>

    <v-card>
      <v-tabs v-model="tab">
        <v-tabs-slider color="primary"></v-tabs-slider>
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
                  locale="pt-Br"
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
                  <v-btn color="btnDanger" @click="client.contacts.splice(index, 1);" :loading="loading" small rounded>
                    <v-icon color="btnDanger darken-4">mdi-delete</v-icon>
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
              <v-btn color="btnPrimary" @click="client.contacts.push({})" :loading="loading" small rounded>
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
                  <v-btn color="btnDanger" @click="client.addresses.splice(index, 1);" :loading="loading" small rounded>
                    <v-icon color="btnDanger darken-4">mdi-delete</v-icon>
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
              <v-btn
                color="btnPrimary"
                @click="client.addresses.push({
                  cep: null
                })"
                :loading="loading"
                small
                rounded
              >
                Adicionar endereço <v-icon>mdi-plus</v-icon>
              </v-btn>
            </v-col>
          </v-row>
        </v-tab-item>
      </v-tabs-items>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          v-if="(!idByRoute && $role.client.add()) || (idByRoute && $role.client.update()) "
          color="btnPrimary"
          @click="_store"
          :loading="loading"
          rounded
        >
          Salvar <v-icon class="ml-2">mdi-content-save</v-icon>
        </v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: this.titlePage }
  },
  data: () => ({
    tab: null,
    loading: false,
    menuBirthDate: false,
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
      addresses: [],
      contacts: []
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
    titlePage(){
      return this.$route.params.id ? 'Cliente' : 'Adicionar Cliente';
    },
    idByRoute(){
      return this.$route.params.id;
    }
  },
  mounted(){
    this._start();
  },
  methods: {
    async _start(){
      if(this.idByRoute){
        await this._load();
      }

      await this._loadCategories();
    },
    async _loadCategories(){
      this.loading = true;
      await axios.get(`api/category?type=client`).then(response => {
        if(response.data.success){
          return this.categories = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar tipos' })
      });
      this.loading = false;
    },
    async _searchCep(indexAddress){
      let cep = this.client.addresses[indexAddress].cep.replace('-', '');

      if(cep.length != 8)
        return;

      this.$refs.fireDialog.loading({ title: 'Buscando endereço' })

      let params = { cep };
      await axios.get(`api/address/searchCep`, { params }).then(response => {
        if(response.data.data.erro){
          return this.$refs.fireDialog.error({ title: 'Error ao carregar endereço' })
        }

        let { logradouro, bairro, localidade, uf } = response.data.data;

        this.client.addresses[indexAddress].street = logradouro.toUpperCase();
        this.client.addresses[indexAddress].district = bairro.toUpperCase();
        this.client.addresses[indexAddress].city = localidade.toUpperCase();
        this.client.addresses[indexAddress].state = uf.toUpperCase();

        this.$refs.fireDialog.hide();
      }).catch(error => {
        this.$refs.fireDialog.error({ title: 'Error ao carregar endereço' })
      })
    },
    async _load(){
      this.loading = true;
      await axios.get(`api/client/${this.idByRoute}`).then(response => {
        if(response.data.success){
          return this.client = response.data.data;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar dados cliente' })
        this.$refs.fireDialog.hide(1500);
      });
      this.loading = false;
    },
    async _store(){
      this.tab = 0;

      for (const field in this.errors) {
        if(!this.client[field])
          return this.errors[field] = true;
      }

      this.loading = true;
      this.$refs.fireDialog.loading({ title: this.idByRoute ? 'Atualizando...' : 'Salvando...' })

      const response = !this.idByRoute ? await axios.post('api/client', this.client) : await axios.put(`api/client/${this.idByRoute}`, this.client);

      this.loading = false;

      if(response.data.success){
        this.$refs.fireDialog.success({ title: 'Cliente salvo com sucesso' })
        return setTimeout(() => this.$router.push({ name: 'client.index' }), 1500);
      }

      this.$refs.fireDialog.error({ title: 'Error aos salvar cliente' })
    },
  }
}
</script>
