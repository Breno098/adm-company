<template>
  <div>
    <v-row>
      <v-col cols="12" class="d-flex flex-row justify-space-between">
        <span class="text-h5 font-weight-bold"></span>
        <span>{{ hour }}</span>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="4">
        <router-link :to="{ name: 'settings-user-profile' }" style="text-decoration: none">
          <v-card>
            <v-toolbar class="d-flex flex-row justify-center" color="blue">
              <v-toolbar-title>Alterar dados do usuario</v-toolbar-title>
            </v-toolbar>

            <v-card-text class="d-flex flex-row justify-center">
              <v-avatar size="110" color="grey lighten-3">
                <img src="storage/laptop-user.png" alt="user-config"/> 
              </v-avatar>
            </v-card-text>
          </v-card>
        </router-link>
      </v-col>

      <v-col cols="12" md="8">
        <v-card>
          <v-toolbar class="d-flex flex-row justify-center" color="orange">
            <v-toolbar-title>Agenda {{ typeAppointment }}</v-toolbar-title>
          </v-toolbar>
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
              <v-btn icon v-on:click="_edit(selectedEvent.appointment)">
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
              <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
            </v-toolbar>
            <v-card-text v-if="selectedEvent.description">
              <span v-html="selectedEvent.description"></span>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn text color="secondary" @click="selectedOpen = false" small>
                Fechar
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-menu>
</div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';

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
    hour: moment().format('DD/MM/YYYY HH:mm:ss')
  }),
  mounted() {
    this.start();
    this.setHour();
  },
  computed: {
    typeAppointment(){
      return this.type === 'month' ? ' do Mês ' : this.type === 'week' ? ' da Semana ' : ' do Dia ';
    }
  },
  methods: {
    start(){
    },
    setHour(){
      setInterval(() => this.hour = moment().add(1, 'seconds').format('DD/MM/YYYY HH:mm:ss'), 1000)
    },
    async getAppointments({ start, end }) {
      this.appointments = [];
      this.loading = true;

      let params = {
        date_start: start.date,
        date_end: end.date,
      };

      await axios.get('api/appointment', { params }).then(response => {
        if(response.data.success){
            response.data.data.map(appointment => {
              this.appointments.push({
                appointment: appointment,
                name: appointment.title,
                description: appointment.description,
                start: appointment.date_start,
                end:  appointment.date_end ?? appointment.date_start,
                color: appointment.concluded ? 'blue' : 'orange',
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
    async _edit(appointment){
      this.$router.push({ name: 'appointment.show', params: { id: appointment.id } })
    },
  }
}
</script>
