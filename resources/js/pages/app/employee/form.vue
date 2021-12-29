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
          v-if="(!idByRoute && $role.employee.add()) || (idByRoute && $role.employee.update()) "
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
                v-model="employee.name"
                :loading="loading"
                :rules="[rules.required]"
                :error="errors.name && !employee.name"
                @input="employee.name = employee.name.toUpperCase()"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-autocomplete
                v-model="employee.position_id"
                :items="positions"
                item-text="name"
                item-value="id"
                label="CARGO"
                outlined
                dense
                :loading="loading"
              ></v-autocomplete>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="RG"
                v-mask="'##.###.###-#'"
                outlined
                dense
                v-model="employee.rg"
                :loading="loading"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="CPF"
                v-mask="'###.###.###-##'"
                outlined
                dense
                v-model="employee.cpf"
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
                    @click:clear="employee.birth_date = null"
                    outlined
                    dense
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="employee.birth_date"
                  @change="menuBirthDate = false"
                  no-title
                  crollable
                  locale="pt-Br"
                ></v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="6">
              <v-menu
                :loading="loading"
                v-model="menuAdmissionDate"
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
                    :value="admissionDateFormat"
                    clearable
                    label="DATA DE ADMISSÃO"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    @click:clear="employee.admission_date = null"
                    outlined
                    dense
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="employee.admission_date"
                  @change="menuAdmissionDate = false"
                  no-title
                  crollable
                  locale="pt-Br"
                ></v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" v-if="employee.user">
              <v-text-field
                readonly
                label="EMAIL DE USUÁRIO"
                outlined
                dense
                v-model="employee.user.email"
                :loading="loading"
              ></v-text-field>
            </v-col>

          </v-row>
        </v-tab-item>

        <!-- Contatos -->
        <v-tab-item>
          <v-row>
            <v-col cols="12" v-for="(contact, index) in employee.contacts" :key="contact.id">
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

              <v-divider color="grey" class="mx-5" v-if="(index + 1) < employee.contacts.length"/>
            </v-col>

            <v-col cols="12" class="d-flex flex-row justify-end">
              <v-btn color="btnPrimary" @click="employee.contacts.push({})" :loading="loading" small rounded>
                Adicionar contato <v-icon>mdi-plus</v-icon>
              </v-btn>
            </v-col>
          </v-row>
        </v-tab-item>

        <!-- Endereço -->
        <v-tab-item>
          <v-row>
            <v-col cols="12" v-for="(address, index) in employee.addresses" :key="address.id">
              <v-row>
                <v-col cols="12" class="d-flex flex-row justify-end">
                  <v-btn color="btnDanger" @click="employee.addresses.splice(index, 1);" :loading="loading" small rounded>
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

              <v-divider color="grey" class="mx-5" v-if="(index + 1) < employee.addresses.length"/>
            </v-col>

            <v-col cols="12" class="d-flex flex-row justify-end">
              <v-btn
                color="btnPrimary"
                @click="employee.addresses.push({
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
          v-if="(!idByRoute && $role.employee.add()) || (idByRoute && $role.employee.update()) "
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
    menuAdmissionDate: false,
    errors: {
      name: false,
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    employee: {
      name: null,
      birth_date: null,
      rg: null,
      cpf: null,
      positions_id: null,
      salary: null,
      admission_date: null,
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
    positions: []
  }),
  computed: {
    birthDateFormat () {
      return this.employee.birth_date ? moment(this.employee.birth_date).format('DD/MM/YYYY') : ''
    },
    admissionDateFormat () {
      return this.employee.admission_date ? moment(this.employee.admission_date).format('DD/MM/YYYY') : ''
    },
    titlePage(){
      return this.$route.params.id ? 'Funcionário' : 'Adicionar Funcionário';
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

      await this._loadPositions();
    },
    async _loadPositions(){
       let params = {
        orderBy: 'name',
      }

      this.loading = true;
      await axios.get(`/api/position`,{ params }).then(response => {
        if(response.data.success){
          return this.positions = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar tipos' })
      });
      this.loading = false;
    },
    async _searchCep(indexAddress){
      let cep = this.employee.addresses[indexAddress].cep.replace('-', '');

      if(cep.length != 8)
        return;

      this.$refs.fireDialog.loading({ title: 'Buscando endereço' })

      let params = { cep };
      await axios.get(`/api/address/searchCep`, { params }).then(response => {
        if(response.data.data.erro){
          return this.$refs.fireDialog.error({ title: 'Error ao carregar endereço' })
        }

        let { logradouro, bairro, localidade, uf } = response.data.data;

        this.employee.addresses[indexAddress].street = logradouro.toUpperCase();
        this.employee.addresses[indexAddress].district = bairro.toUpperCase();
        this.employee.addresses[indexAddress].city = localidade.toUpperCase();
        this.employee.addresses[indexAddress].state = uf.toUpperCase();

        this.$refs.fireDialog.hide();
      }).catch(error => {
        this.$refs.fireDialog.error({ title: 'Error ao carregar endereço' })
      })
    },
    async _load(){
      this.loading = true;
      await axios.get(`/api/employee/${this.idByRoute}`).then(response => {
        if(response.data.success){
          return this.employee = response.data.data;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar dados do funcionário' })
        this.$refs.fireDialog.hide(1500);
      });
      this.loading = false;
    },
    async _store(){
      this.tab = 0;

      for (const field in this.errors) {
        if(!this.employee[field])
          return this.errors[field] = true;
      }

      this.loading = true;
      this.$refs.fireDialog.loading({ title: this.idByRoute ? 'Atualizando...' : 'Salvando...' })

      const response = !this.idByRoute ? await axios.post('/api/employee', this.employee) : await axios.put(`/api/employee/${this.idByRoute}`, this.employee);

      this.loading = false;

      if(response.data.success){
        this.$refs.fireDialog.success({ title: 'Funcionário salvo com sucesso' })
        return setTimeout(() => this.$router.push({ name: 'employee.index' }), 1500);
      }

      this.$refs.fireDialog.error({ title: 'Error aos salvar funcionário' })
    },
  }
}
</script>
