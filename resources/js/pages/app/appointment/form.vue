<template>
  <div>
    <v-row>
      <v-col cols="12">
        <v-select
          v-model="appointment.status"
          :items="statuses"
          item-text="name"
          item-value="id"
          label="STATUS"
          outlined
          dense
          :loading="loading"
        ></v-select>
      </v-col>

     <v-col cols="12" md="6">
      <v-menu
          v-model="menu_date_appointment"
          :close-on-content-click="false"
          max-width="290"
          transition="scale-transition"
          offset-y
      >
      <template v-slot:activator="{ on, attrs }">
          <v-text-field
              append-icon="mdi-calendar"
              :value="AppointmentDateFormat"
              clearable
              label="Data"
              readonly
              v-bind="attrs"
              v-on="on"
              @click:clear="appointment.date = null"
              outlined
              dense
              :loading="loading"
          ></v-text-field>
      </template>
      <v-date-picker
          v-model="appointment.date"
          @change="menu_date_appointment = false"
          no-title
          crollable
      ></v-date-picker>
      </v-menu>
    </v-col>
    <v-col cols="12" md="6">
        <v-menu
            ref="menu_time_appointment"
            v-model="menu_time_appointment"
            :close-on-content-click="false"
            :nudge-right="40"
            :return-value.sync="appointment.hour"
            transition="scale-transition"
            offset-y
            max-width="290px"
            min-width="290px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
                v-model="appointment.hour"
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
              v-if="menu_time_appointment"
              v-model="appointment.hour"
              @click:minute="$refs.menu_time_appointment.save(appointment.hour)"
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
import { mapGetters } from 'vuex'
import axios from 'axios';
import moment from 'moment';
import { format, parseISO } from 'date-fns'

export default {
  metaInfo () {
    return { title: 'Compromisso' }
  },
  data: () => ({
    menu_date_appointment: false,
    menu_time_appointment: false,
    appointment: {
      date : format( parseISO(new Date().toISOString()), 'yyyy-MM-dd'),
      hour : '',
      description: '',
      order: null,
      status: null
    },
    statuses: [],
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
  }),
  computed: {
     AppointmentDateFormat () {
        return this.appointment.date ? moment(this.appointment.date).format('DD/MM/YYYY') : ''
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
      await this._loadStatuses();
    },
    _modal(message, status){
      this.dialog.message = message;
      this.dialog.status = status;
      this.dialog.show = true;
    },
    async _load(){
      let id = this.$route.params.id ? this.$route.params.id : null;

      this.loading = true;
      await axios.get(`api/appointment/${id}`).then(response => {
        if(response.data.success){
          return this.appointment = response.data.data;
        }

        this._modal('Error ao carregar compromisso', 'error');
        setTimeout(() => this.$router.push({ name: 'appointment.index' }), 1500);
      });

      this.loading = false;
    },
    async _loadStatuses(){
       let params = { type: 'appointment' };

      this.loadingStatuses = true;
      await axios.get(`api/status`, { params }).then(response => {
        if(response.data.success){
          return this.statuses = response.data.data;
        }
        this._modal('Error ao carregar status', 'error');
      });
      this.loadingStatuses = false;
    },
    async _store(){
      let id = this.$route.params.id;

      this.loading = true;
      this.dialog.show = true;
      this.dialog.message = id ? 'Atualizando...' : 'Salvando...';

      this.appointment.order = this.order.id;
      
      let response = null;

      if(!id){
        response = await axios.post('api/appointment', this.appointment)
      } else {
        response = await axios.put(`api/appointment/${id}`, this.appointment)
      }

      this.loading = false;

      if(response.data.success){
        this._modal('Compromisso salvo com sucesso', 'success');
        return setTimeout(() => this.$router.go(-1), 1500);
      }

      this._modal('Error ao salvar compromisso', 'error');
    },
  }

}
</script>

<style>

</style>
