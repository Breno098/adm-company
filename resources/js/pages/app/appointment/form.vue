<template>
  <div>
    <v-row>
      <v-col cols="12" v-if="appointment.concluded == 'N'">
        <v-btn color="green" @click="_conclude" :loading="loading">
          Concluír Compromisso <v-icon dark class="ml-3">mdi-check</v-icon>
        </v-btn>
      </v-col>
      <v-col cols="12" md="6" v-if="appointment.concluded == 'S'">
        <v-btn color="blue" @click="_unconclude" :loading="loading">
          Voltar Compromisso como Pendente <v-icon dark class="ml-3">mdi-backburger</v-icon>
        </v-btn>
      </v-col>

      <v-col cols="12">
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
              label="Data"
              readonly
              v-bind="attrs"
              v-on="on"
              @click:clear="appointment.date_start = null"
              outlined
              dense
              :loading="loading"
          ></v-text-field>
      </template>
      <v-date-picker
          v-model="appointment.date_start"
          @change="menu_date_start = false"
          no-title
          crollable
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
                label="HORA"
                prepend-icon="mdi-clock-time-four-outline"
                readonly
                v-bind="attrs"
                v-on="on"
                dense
                outlined
                :loading="loading"
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
                label="Data"
                readonly
                v-bind="attrs"
                v-on="on"
                @click:clear="appointment.date_end = null"
                outlined
                dense
                :loading="loading"
            ></v-text-field>
        </template>
        <v-date-picker
            v-model="appointment.date_end"
            @change="menu_date_end = false"
            no-title
            crollable
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
                label="HORA"
                prepend-icon="mdi-clock-time-four-outline"
                readonly
                v-bind="attrs"
                v-on="on"
                dense
                outlined
                :loading="loading"
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

      <v-col cols="4">
        <v-text-field
          :value="ClientNameOrder"
          outlined
          dense
          label="CLIENTE"
          readonly
          color="black"
          :loading="loading"
        ></v-text-field>
      </v-col>

      <v-col cols="8">
        <v-text-field
          :value="AddressFormat"
          outlined
          dense
          label="ENDEREÇO"
          readonly
          color="black"
          :loading="loading"
        ></v-text-field>
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

      <v-col cols="12">
        <v-text-field
          :value="order.id"
          outlined
          dense
          label="Nº ORDEM"
          readonly
          color="black"
          :loading="loading"
        ></v-text-field>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="8" md="10">
        <v-btn color="green" @click="_store" :loading="loading">
          Salvar <v-icon dark class="ml-3">mdi-content-save</v-icon>
        </v-btn>
      </v-col>
      <v-col cols="4" md="2" v-if="appointment.id">
        <v-btn color="red" @click="_delete" :loading="loading">
          Apagar <v-icon dark class="ml-2">mdi-delete</v-icon>
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
import { mapGetters } from 'vuex'
import axios from 'axios';
import moment from 'moment';
import { format, parseISO } from 'date-fns'

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: 'Compromisso' }
  },
  data: () => ({
    menu_date_start: false,
    menu_time_start: false,
    menu_date_end: false,
    menu_time_end: false,
    appointment: {
      title: null,
      date_start : format( parseISO(new Date().toISOString()), 'yyyy-MM-dd'),
      time_start : '',
      date_end: format( parseISO(new Date().toISOString()), 'yyyy-MM-dd'),
      time_end : '',
      description: null,
      order_id: null,
      status_id: null
    },
    loading: false,
    dialog: {
      show: false,
      message: '',
      status: null
    },
    errors: {
      title: false,
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
  }),
  computed: {
    DateStartFormat () {
      return this.appointment.date_start ? moment(this.appointment.date_start).format('DD/MM/YYYY') : ''
    },
    DateEndFormat () {
      return this.appointment.date_end ? moment(this.appointment.date_end).format('DD/MM/YYYY') : ''
    },
    AddressFormat() {
      return this.order.address.street + ' n° ' + this.order.address.number + ', ' + this.order.address.district + ' - ' +  this.order.address.city;
    },
    ClientNameOrder(){
      return this.order ? this.order.client.name : '';
    },
    ...mapGetters({
      order: 'order/order'
    })
  },
  mounted(){
    this._start();
  },
  methods: {
    async _start(){
      if(this.$route.params.id){
        await this._load();
      }
    },
    _showModal(message, status = ''){
      this.dialog.message = message;
      this.dialog.status = status;
      this.dialog.show = true;
    },
    _hideModal(){
      this.dialog.show = false;
    },
    async _load(){
      const id = this.$route.params.id;

      this.loading = true;
      await axios.get(`api/appointment/${id}`).then(response => {
        if(response.data.success){
          return this.appointment = response.data.data;
        }
        this._showModal('Error ao carregar compromisso', 'error');
        setTimeout(() => this.$router.push({ name: 'appointment.index' }), 1500);
      });
      this.loading = false;
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

      let id = this.$route.params.id;

      this.loading = true;
      this._showModal(id ? 'Atualizando...' : 'Salvando...');

      this.appointment.order_id = this.order.id;

      let response = !id ? await axios.post('api/appointment', this.appointment) : await axios.put(`api/appointment/${id}`, this.appointment);

      this.loading = false;

      if(response.data.success){
        this._showModal('Compromisso salvo com sucesso', 'success');
      } else {
        this._showModal('Error ao salvar compromisso', 'error');
      }

      if(!this.appointment.concluded){
         return setTimeout(() => this.$router.go(-1), 1500);
      }
    },
    async _delete(){
      let id = this.$route.params.id;
      
      let response = await axios.delete(`api/appointment/${id}`);
      
      if(response.data.success){
        this._showModal('Compromisso deletado com sucesso', 'success');
        return setTimeout(() => this.$router.go(-1), 1500);
      }
    }
  },
 
}
</script>