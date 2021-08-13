<template>
  <div>
    <v-row>
      <v-col cols="12" md="3" v-for="(status) in statuses" :key="status.id" >
        <v-btn :color="status.color" block @click="_load({ status_id: status.id })">
            {{ status.name }}
        </v-btn>
      </v-col>
      <v-col cols="12" md="3" >
        <v-btn block @click="_load">
          Todas
        </v-btn>
      </v-col>
    </v-row>

    <v-row>
      <!-- <v-col cols="12">
        <v-btn text color="blue" @click="_add">
            Adicionar <v-icon dark>mdi-plus</v-icon>
        </v-btn>
      </v-col> -->

      <v-col cols="12" v-if="loading">
        <v-row>
          <v-col cols="12" md="6" v-for="index in [0, 1, 2, 3]" :key="index">
            <v-skeleton-loader type="image"></v-skeleton-loader>
          </v-col>
        </v-row>
      </v-col>
      
      <v-col cols="12" md="6" v-for="appointment in appointments" :key="appointment.id" v-else>
        <v-card v-on:click="_edit(appointment)">
          <v-list-item three-line>
            <v-list-item-content>
              <div class="text-overline mb-4 d-flex justify-space-between">
                {{ appointment.order.client.name }} 
                <v-chip v-if="appointment.status" :color="appointment.status.color" class="py-2" label>{{ appointment.status.name }}</v-chip>
              </div>
              <v-list-item-subtitle>
                {{ appointment.date }}
              </v-list-item-subtitle>
              <v-list-item-subtitle>
                {{ appointment.order.address.street }} {{ appointment.order.address.number ? `n° ${appointment.order.address.number}` : '' }}, {{ appointment.order.address.district }} - {{ appointment.order.address.city }}
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

          <v-card-actions>
            <v-spacer/>
            <v-btn text color="blue" v-on:click="_edit(appointment)">
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
    statuses: []
  }),
  mounted() {
     this._start();
  },
  methods: {
    async _start(){
      await this._loadStatuses();
    },
    async _loadStatuses(){
       let params = { type: 'appointment' };

      this.loading = true;
      await axios.get(`api/status`, { params }).then(response => {
        if(response.data.success){
          return this.statuses = response.data.data;
        }
      });
      this.loading = false;
    },
    async _load(filters = {}){
      let params = { page: this.page, ...filters }

      this.loading = true;
      await axios.get(`api/appointment`, { params }).then(response => {
        if(response.data.data.data.length === 0 && this.page != 1){
          this.page = 1;
          this._load(filters)
        }

        if(response.data.success){
            this.appointments = response.data.data.data;
            this.pageCount = response.data.data.last_page;
            this.loading = false;
        }
      });
    },
    async _edit(appointment){
      await this.$store.dispatch('order/setData', { order: appointment.order })
      this.$router.push({ name: 'appointment.form', params: { id: appointment.id } })
    },
     _add(){
      this.$router.push({ name: 'appointment.form' })
    }
  }
}
</script>
