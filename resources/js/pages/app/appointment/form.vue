<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-row>
      <v-col cols="12">
        <v-card elevation="0">
          <v-toolbar elevation="0" class="mb-2">
            <v-toolbar-title> {{ titlePage }} </v-toolbar-title>
            <v-progress-linear
              color="primary"
              indeterminate
              height="4"
              bottom
              absolute
              :active="loading"
            ></v-progress-linear>

            <v-spacer></v-spacer>

            <v-btn color="btnPrimary" @click="_store" :loading="loading" rounded dark small>
              Salvar <v-icon dark class="ml-2">mdi-content-save</v-icon>
            </v-btn>
          </v-toolbar>

          <v-row>
            <v-col cols="12" v-if="appointment.concluded == 'N'">
              <v-btn color="btnPrimary" @click="_conclude" :loading="loading" small>
                Concluír Compromisso <v-icon dark class="ml-3">mdi-check</v-icon>
              </v-btn>
            </v-col>
            <v-col cols="12" md="6" v-if="appointment.concluded == 'S'">
              <v-btn color="primary" @click="_unconclude" :loading="loading" small>
                Voltar Compromisso como Pendente <v-icon dark class="ml-3">mdi-backburger</v-icon>
              </v-btn>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="appointment.title"
                outlined
                dense
                label="TITULO"
                :loading="loading"
                :rules="[rules.required]"
                :error="errors.title && !appointment.title"
              ></v-text-field>
            </v-col>

             <v-col cols="12" md="6">
              <v-autocomplete
                :readonly="CreateByOrder"
                v-model="appointment.client_id"
                :items="clients"
                item-text="name"
                item-value="id"
                label="CLIENTE"
                :loading="loadingClients"
                v-on:change="_loadAddresses"
                outlined
                dense
                :color="CreateByOrder ? 'black' : 'blue'"
              ></v-autocomplete>
            </v-col>

            <v-col cols="12">
              <v-autocomplete
                :readonly="CreateByOrder"
                v-model="appointment.address_id"
                :items="addresses"
                item-value="id"
                label="ENDEREÇO"
                :loading="loadingAddresses"
                outlined
                dense
                :color="CreateByOrder ? 'black' : 'blue'"
              >
                <template slot="selection" slot-scope="data">
                  {{ data.item.street }} {{ data.item.number ? `n° ${data.item.number}` : '' }}, {{ data.item.district }} - {{ data.item.city }}
                </template>
                <template slot="item" slot-scope="data">
                    {{ data.item.street }} {{ data.item.number ? `n° ${data.item.number}` : '' }}, {{ data.item.district }} - {{ data.item.city }}
                </template>
              </v-autocomplete>
            </v-col>

            <v-col cols="12" md="6">
              <v-menu
                  v-model="menu_date_start"
                  :close-on-content-click="false"
                  max-width="290"
                  transition="scale-transition"
                  offset-y
              >
              <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    append-icon="mdi-calendar"
                    :value="DateStartFormat"
                    clearable
                    label="DATA INICIO"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    @click:clear="appointment.date_start = null"
                    outlined
                    dense
                    :loading="loading"
                    :rules="[rules.required]"
                    :error="errors.date_start && !appointment.date_start"
                  ></v-text-field>
              </template>
              <v-date-picker
                  v-model="appointment.date_start"
                  @change="menu_date_start = false"
                  no-title
                  crollable
                  locale="pt-Br"
              ></v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="6">
              <v-menu
                  ref="menu_time_start"
                  v-model="menu_time_start"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  :return-value.sync="appointment.time_start"
                  transition="scale-transition"
                  offset-y
                  max-width="290px"
                  min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                      v-model="appointment.time_start"
                      clearable
                      label="HORA INICIO"
                      prepend-icon="mdi-clock-time-four-outline"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      dense
                      outlined
                      :loading="loading"
                      :rules="[rules.required]"
                      :error="errors.time_start && !appointment.time_start"
                  ></v-text-field>
                </template>
                <v-time-picker
                    v-if="menu_time_start"
                    v-model="appointment.time_start"
                    @click:minute="$refs.menu_time_start.save(appointment.time_start)"
                    format="24hr"
                ></v-time-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="6">
              <v-menu
                  v-model="menu_date_end"
                  :close-on-content-click="false"
                  max-width="290"
                  transition="scale-transition"
                  offset-y
              >
              <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    append-icon="mdi-calendar"
                    :value="DateEndFormat"
                    clearable
                    label="DATA FIM"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    @click:clear="appointment.date_end = null"
                    outlined
                    dense
                    :loading="loading"
                    :rules="[rules.required]"
                    :error="errors.date_end && !appointment.date_end"
                  ></v-text-field>
              </template>
              <v-date-picker
                  v-model="appointment.date_end"
                  @change="menu_date_end = false"
                  no-title
                  crollable
                  locale="pt-Br"
              ></v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="6">
              <v-menu
                  ref="menu_time_end"
                  v-model="menu_time_end"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  :return-value.sync="appointment.time_end"
                  transition="scale-transition"
                  offset-y
                  max-width="290px"
                  min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="appointment.time_end"
                    clearable
                    label="HORA FIM"
                    prepend-icon="mdi-clock-time-four-outline"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    dense
                    outlined
                    :loading="loading"
                    :rules="[rules.required]"
                    :error="errors.time_end && !appointment.time_end"
                  ></v-text-field>
                </template>
                <v-time-picker
                    v-if="menu_time_end"
                    v-model="appointment.time_end"
                    @click:minute="$refs.menu_time_end.save(appointment.time_end)"
                    format="24hr"
                ></v-time-picker>
              </v-menu>
            </v-col>

            <v-col cols="12">
              <v-textarea
                label="DESCRIÇÃO"
                outlined
                dense
                v-model="appointment.description"
                :loading="loading"
              ></v-textarea>
            </v-col>

            <v-col cols="12" v-if="CreateByOrder">
              <v-text-field
                v-model="appointment.order_id"
                outlined
                dense
                label="Nº ORDEM"
                readonly
                color="black"
                :loading="loading"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="btnPrimary" @click="_store" :loading="loading" rounded dark>
              Salvar <v-icon dark class="ml-2">mdi-content-save</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn color="btnDanger" @click="_delete" :loading="loading" rounded dark v-if="idByRoute">
              Deletar <v-icon dark class="ml-2">mdi-delete</v-icon>
            </v-btn>
          </v-card-actions>
         </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import { format, parseISO } from 'date-fns'

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: this.titlePage }
  },
  data: () => ({
    menu_date_start: false,
    menu_time_start: false,
    menu_date_end: false,
    menu_time_end: false,
    appointment: {
      title: null,
      date_start : format( parseISO(new Date().toISOString()), 'yyyy-MM-dd'),
      time_start : moment().add(1, 'hour').format('HH:00'),
      date_end: format( parseISO(new Date().toISOString()), 'yyyy-MM-dd'),
      time_end : moment().add(2, 'hour').format('HH:00'),
      description: null,
      order_id: null,
      client_id: null,
      address_id: null,
    },
    clients: [],
    addresses: [],
    loading: false,
    loadingClients: false,
    loadingAddresses: false,
    errors: {
      title: false,
      date_start: false,
      date_end: false,
      time_start: false,
      time_end: false,
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
  }),
  computed: {
    titlePage(){
      return this.$route.params.id ? 'Editar Compromisso' : 'Adicionar Compromisso';
    },
    idByRoute(){
      return this.$route.params.id;
    },
    DateStartFormat () {
      return this.appointment.date_start ? moment(this.appointment.date_start).format('DD/MM/YYYY') : ''
    },
    DateEndFormat () {
      return this.appointment.date_end ? moment(this.appointment.date_end).format('DD/MM/YYYY') : ''
    },
    CreateByOrder(){
      if(this.$route.query.orderId){
        this.appointment.order_id = this.$route.query.orderId;
        this.appointment.client_id = this.$route.query.clientId;
        this.appointment.address_id = this.$route.query.addressId;
        this._loadAddresses();
        return true;
      }

      if(this.appointment.order_id){
        this._loadAddresses();
        return true;
      }

      return false;
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

      await this._loadClients();
    },
    async _load(){
      this.loading = true;
      await axios.get(`api/appointment/${this.idByRoute}`).then(response => {
        if(response.data.success){
          return this.appointment = response.data.data;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar dados do compromisso' });
        this.$refs.fireDialog.hide(1500);
      });
      this.loading = false;
    },
    async _loadClients(){
      this.loadingClients = true;
      await axios.get(`api/client`).then(response => {
        if(response.data.success){
          return this.clients = response.data.data;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar clientes' });
      });
      this.loadingClients = false;
    },
    async _loadAddresses(){
      let params = { client_id: this.appointment.client_id };

      this.loadingAddresses = true;
      await axios.get(`api/address`, { params }).then(response => {
        if(response.data.success){
          return this.addresses = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar endereços' });
      });
      this.loadingAddresses = false;
    },
    _unconclude(){
      this.appointment.concluded = 'N';
      this._store();
    },
    _conclude(){
      this.appointment.concluded = 'S';
      this._store();
    },
    async _store(){
      for (const field in this.errors) {
        if(!this.appointment[field])
          return this.errors[field] = true;
      }

      this.$refs.fireDialog.loading({ title: this.idByRoute ? 'Atualizando...' : 'Salvando...' })

      this.loading = true;

      const response = !this.idByRoute ? await axios.post('api/appointment', this.appointment) : await axios.put(`api/appointment/${this.idByRoute}`, this.appointment);

      this.loading = false;

      if(response.data.success){
        this.$refs.fireDialog.success({ title: 'Compromisso salvo com sucesso' })
      } else {
        this.$refs.fireDialog.error({ title: 'Error ao salvar compromisso' });
      }

      if(!this.appointment.concluded){
         return setTimeout(() => this.$router.go(-1), 1500);
      }
    },
    async _delete(){
      const ok = await this.$refs.fireDialog.confirm({
          title: 'Deletar Compromisso?',
          textConfirmButton: 'Deletar',
          colorConfirButton: 'btnDanger',
        colorCancelButton: 'btnPrimary'
      })

      if (ok) {
        this.$refs.fireDialog.loading({ title: 'Deletando' });

        await axios.delete(`api/appointment/${this.idByRoute}`).then(response => {
          if(response.data.success){
            this.$refs.fireDialog.success({ title: 'Deletado com sucesso' });
            return setTimeout(() => this.$router.go(-1), 1500);
          }

          this.$refs.fireDialog.error({ title: 'Erro ao deletar' });
        }).catch(error => {
          this.$refs.fireDialog.error({ title: 'Erro ao deletar' });
        });
      }
    }
  },

}
</script>
