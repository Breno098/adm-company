<template>
  <div>
    <v-row>
      <v-col cols="12" class="d-flex flex-row justify-space-between">
        <span class="text-h5 font-weight-bold">Olá {{ user.name.split(' ')[0] }}!</span>
        <span>{{ hour }}</span>
      </v-col>
    </v-row>

    <v-row>
      <!-- <v-col cols="12" md="4">
        <router-link :to="{ name: 'settings-user-profile' }" style="text-decoration: none">
          <v-card>
            <v-toolbar class="d-flex flex-row justify-center" color="primary">
              <v-toolbar-title>Alterar dados do usuario</v-toolbar-title>
            </v-toolbar>

            <v-card-text class="d-flex flex-row justify-center">
              <v-avatar size="110" color="grey lighten-3">
                <img src="storage/laptop-user.png" alt="user-config"/>
              </v-avatar>
            </v-card-text>
          </v-card>
        </router-link>
      </v-col> -->

      <v-col cols="12" md="6">
        <router-link :to="{ name: 'client.index' }" style="text-decoration: none">
          <v-card>
            <v-card-title>Número de clientes</v-card-title>

            <v-card-text class="d-flex flex-row justify-space-between">
              <h1> {{ countClients }} </h1>
              <v-icon>mdi-account</v-icon>
            </v-card-text>
          </v-card>
        </router-link>
      </v-col>

      <v-col cols="12">
        <v-card>
          <v-card-title class="d-flex flex-row justify-center">
            Agenda {{ typeAppointment }}
          </v-card-title>

          <v-card-text>
            <v-row>
              <v-col cols="1">
                <v-btn icon class="ma-2" @click="$refs.calendar.prev()">
                  <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
              </v-col>
              <v-col cols="10">
                  <v-select
                    item-text="label"
                    item-value="type"
                    v-model="type"
                    :items="types"
                    dense
                    outlined
                    hide-details
                    class="ma-2"
                    label="TIPO CALENDÁRIO"
                  ></v-select>
              </v-col>
              <v-col cols="1">
                <v-btn icon class="ma-2" @click="$refs.calendar.next()">
                  <v-icon>mdi-chevron-right</v-icon>
                </v-btn>
              </v-col>

              <v-col cols="12" class="d-flex flex-row justify-center">
                {{ calendar.start_date_string }} à {{ calendar.end_date_string }}
              </v-col>

              <v-col cols="12">
                <v-sheet height="600">
                  <v-calendar
                    ref="calendar"
                    @click:event="showEvent"
                    v-model="value"
                    :weekdays="[0, 1, 2, 3, 4, 5, 6]"
                    :type="type"
                    :events="appointments"
                    :event-overlap-threshold="30"
                    :event-color="getEventColor"
                    @change="getAppointments"
                     locale="pt-Br"
                  ></v-calendar>
                </v-sheet>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>


    <v-menu v-model="selectedOpen" :close-on-content-click="false" :activator="selectedElement" offset-x>
        <v-card color="grey lighten-4" min-width="350px" flat>
          <v-toolbar :color="selectedEvent.color" dark>
            <v-toolbar-title>
              {{ selectedEvent.name }}
            </v-toolbar-title>

            <v-spacer/>

            <v-btn @click="selectedOpen = false" x-small icon class="mr-3">
              <v-icon small>mdi-close</v-icon>
            </v-btn>
          </v-toolbar>
          <v-card-text>
            <div v-if="selectedEvent.client_name">
              <v-icon size="15">mdi-account-circle</v-icon> {{ selectedEvent.client_name }}
            </div>

            <div>
              <v-icon size="15">mdi-calendar-clock</v-icon>
              {{ selectedEvent.start | formatDate }} ás  {{ selectedEvent.end | formatDate }}
            </div>

            <div v-if="selectedEvent.description">
              <v-icon color="primary" size="15">mdi-comment-text-outline</v-icon> {{ selectedEvent.description  }}
            </div>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="showAppointment(selectedEvent.appointment)" small>
              Ver compromisso
              <v-icon small class="ml-2">mdi-calendar-today</v-icon>
            </v-btn>
            <v-btn
              v-if="$can('order_show') && selectedEvent.order_id"
              color="primary"
              @click="showOrder(selectedEvent.order_id)"
              small
            >
              Ver pedido
              <v-icon small class="ml-2">mdi-file-document</v-icon>
            </v-btn>

          </v-card-actions>
        </v-card>
      </v-menu>
</div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import { mapGetters } from 'vuex';

moment.locale('pt-br');

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: 'Home' }
  },
  data: () => ({
    type: 'month',
    loading: false,
    types: [
      { label: 'Mês', type: 'month'},
      { label: 'Semana', type: 'week' },
      { label: 'Dia', type: 'day'}
    ],
    statuses: [],
    value: '',
    appointments: [],
    selectedEvent: {},
    selectedElement: null,
    selectedOpen: false,
    hour: moment().format('DD/MM/YYYY HH:mm:ss'),
    countClients: '--',
    calendar: {
      start_date_string: '',
      end_date_string: ''
    }
  }),
  mounted() {
    this.start();
    this.setHour();
  },
  filters: {
    formatDate(date){
      return date ? moment(date).format('DD/MM/YYYY HH:mm') : '';
    },
  },
  computed: {
    typeAppointment(){
      return this.type === 'month' ? ' do Mês ' : this.type === 'week' ? ' da Semana ' : ' do Dia ';
    },
    ... mapGetters({
      user: 'auth/user'
    }),
  },
  methods: {
    start(){
      this._loadCountClients();
    },
    setHour(){
      setInterval(() => this.hour = moment().add(1, 'seconds').format('DD/MM/YYYY HH:mm:ss'), 1000)
    },
    async getAppointments({ start, end }) {    this.$refs.calendar.checkChange();
      this.appointments = [];
      this.loading = true;

      let params = {
        date_start: start.date,
        date_end: end.date,
      };

      this.calendar.start_date_string = moment(start.date).format('LL');
      this.calendar.end_date_string = moment(end.date).format('LL');

      await axios.get('/api/appointment', { params }).then(response => {
        if(response.data.success){
          response.data.data.map(appointment => {
            let date_start = appointment.time_start ? `${appointment.date_start} ${appointment.time_start}` : appointment.date_start;
            let date_end = appointment.time_end ? `${appointment.date_end} ${appointment.time_end}` : appointment.date_end;

            this.appointments.push({
              appointment: appointment,
              name: appointment.title,
              description: appointment.description,
              client_name: appointment.client_id ? appointment.client.name : '',
              start: date_start,
              end: date_end,
              color: appointment.concluded === 'N' ? 'primary' : 'orange',
              order_id: appointment.order_id
            })
          })
        }
      });

      this.loading = false;
    },
    showEvent ({ nativeEvent, event }) {
      const open = () => {
        this.selectedEvent = event
        this.selectedElement = nativeEvent.target
        requestAnimationFrame(() => requestAnimationFrame(() => this.selectedOpen = true))
      }

      if (this.selectedOpen) {
        this.selectedOpen = false
        requestAnimationFrame(() => requestAnimationFrame(() => open()))
      } else {
        open()
      }

      nativeEvent.stopPropagation()
    },
    getEventColor (event) {
      return event.color
    },
    showAppointment(appointment){
      this.$router.push({ name: 'appointment.show', params: { id: appointment.id } })
    },
    showOrder(id){
      this.$can('order_show')
        ? this.$router.push({
            name: 'order.show',
            params: { id }
        }) : null
    },
    async _loadCountClients(){
      this.loading = true;
      await axios.get(`/api/client/count`).then(response => {
        if(response.data.success){
          return this.countClients = response.data.data.count;
        }
      });
      this.loading = false;
    },
  }
}
</script>
