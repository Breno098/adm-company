<template>
  <div>
    <v-row>
      <v-col cols="12">
        <v-btn color="primary" @click="_add" rounded dark small>
            Adicionar <v-icon dark>mdi-plus</v-icon>
        </v-btn>
      </v-col>
    </v-row>

    <v-row class="d-flex flex-row justify-center">
      <v-col cols="12">
        <v-btn block @click="_loadNotConcluded" :color="filters.concluded == 'N' ? 'blue' : 'grey'" large>
          Compromissos Pendentes
        </v-btn>
      </v-col>
      <v-col cols="10">
        <v-btn block @click="_loadConcluded" :color="filters.concluded == 'S' ? 'green' : 'grey'" small>
          Histórico
        </v-btn>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" v-if="loading">
        <v-row>
          <v-col cols="12" md="6" v-for="index in [0, 1, 2, 3]" :key="index">
            <v-skeleton-loader type="image"></v-skeleton-loader>
          </v-col>
        </v-row>
      </v-col>

      <v-col cols="12" v-for="appointment in appointments" :key="appointment.id" v-else>
        <v-card v-on:click="_edit(appointment)">
          <v-list-item three-line>
            <v-list-item-content>
              <div class="text-overline mb-4 d-flex justify-space-between">
                {{ appointment.client ? appointment.client.name : '' }}
                <v-chip :color="appointment.concluded == 'S' ? 'green' : 'blue'" class="py-2" label>{{ appointment.concluded == 'S' ? 'CONCLUÍDO' : 'PENDENTE' }}</v-chip>
              </div>
              <v-list-item-subtitle>
                {{ appointment.date }}
              </v-list-item-subtitle>
              <v-list-item-subtitle>
                {{ appointment.order_id ? `Pedido: ${appointment.order_id}` : '' }}
              </v-list-item-subtitle>
              <v-list-item-subtitle v-if="appointment.address">
                {{ `${appointment.address.street} n° ${appointment.address.number}, ${appointment.address.district } - ${ appointment.address.city}` }}
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

          <v-card-actions>
            <v-spacer/>
            <v-btn text color="primary" v-on:click="_edit(appointment)">
              Ver informações
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>

      <v-col cols="12">
        <div class="text-center pt-2 mt-4">
          <v-pagination
            v-model="page"
            :length="pageCount"
            @input="_load"
            :total-visible="15"
            circle
          ></v-pagination>
        </div>
      </v-col>
  </v-row>
</div>
</template>

<script>
import axios from 'axios';

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: 'Compromissos' }
  },
  data: () => ({
    dialog: false,
    deleted: {},
    search: '',
    page: 1,
    pageCount: 0,
    appointments: [],
    loading: false,
    statuses: [],
    filters: {
      concluded: 'N'
    }
  }),
  mounted() {
    this._start();
  },
  methods: {
    _start(){
      this._loadNotConcluded();
    },
    _loadNotConcluded(){
      this.filters.concluded = 'N';
      this._load();
    },
    _loadConcluded(){
      this.filters.concluded = 'S';
      this._load();
    },
    async _load(){
      let params = {
        page: this.page,
        itemsPerPage: 20,
        ...this.filters
      }

      this.loading = true;
      await axios.get(`api/appointment`, { params }).then(response => {
        if(response.data.data.data.length === 0 && this.page != 1){
          this.page = 1;
        }

        if(response.data.success){
            this.appointments = response.data.data.data;
            this.pageCount = response.data.data.last_page;
            this.loading = false;
        }
      });
    },
    async _edit(appointment){
      this.$router.push({ name: 'appointment.show', params: { id: appointment.id } })
    },
     _add(){
      this.$router.push({ name: 'appointment.create' })
    }
  }
}
</script>
