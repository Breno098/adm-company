<template>
  <div>
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
            label="type"
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
            :events="events"
            :event-overlap-threshold="30"
            :event-color="getEventColor"
            @change="getEvents"
          ></v-calendar>
        </v-sheet>
      </v-col>

      <v-menu v-model="selectedOpen" :close-on-content-click="false" :activator="selectedElement" offset-x>
          <v-card color="grey lighten-4" min-width="350px" flat>
            <v-toolbar :color="selectedEvent.color" dark>
              <v-btn icon>
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
              <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
            </v-toolbar>
            <v-card-text>
              <span v-html="selectedEvent.name"></span>
            </v-card-text>
            <v-card-actions>
              <v-btn text color="secondary" @click="selectedOpen = false" small>
                Fechar
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-menu>
  </v-row>
</div>
</template>

<script>
import axios from 'axios';

export default {
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
    value: '',
    events: [],
    colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
    selectedEvent: {},
    selectedElement: null,
    selectedOpen: false,
  }),
  mounted() {
  },
  methods: {
    async getEvents ({ start, end }) {
      this.events = [];
      this.loading = true;

      let params = {
        date_start: start.date,
        date_end: end.date
      };

      await axios.get('api/home/appointment', { params }).then(response => {
        if(response.data.success){
            response.data.data.map(event => {
              this.events.push({
                name: event.description,
                start: event.date_start,
                end:  event.date_end ?? event.date_start,
                color: event.status.color,
              })
            })
        }
      });

      this.loading = false;
    },
    showEvent ({ nativeEvent, event }) {
      console.log(nativeEvent, event)
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
    rnd (a, b) {
      return Math.floor((b - a + 1) * Math.random()) + a
    },
    getEventColor (event) {
      return event.color
    },
  }
}
</script>
