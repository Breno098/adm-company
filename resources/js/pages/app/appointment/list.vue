<template>
  <div>
    <p class="font-weight-bold mb-5 text-h5">
      Agenda
    </p>

    <v-row class="mb-2">
      <v-col cols="6" md="9">
        <v-menu
          :close-on-content-click="false"
          :nudge-width="200"
          offset-y
          v-model="menuFilter"
        >
          <template v-slot:activator="{ on, attrs }">
              <v-btn text small v-bind="attrs" v-on="on">
                  Filtros
                  <v-icon color="primary">mdi-chevron-down</v-icon>
              </v-btn>
          </template>

          <v-card width="800">
              <v-card-text>
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-text-field
                        label="CLIENTE"
                        filled
                        dense
                        v-model="table.filters.client_name"
                        :loading="table.loading"
                        @input="table.filters.client_name = table.filters.client_name.toUpperCase()"
                        v-on:keyup.enter="_load"
                      ></v-text-field>
                    </v-col>

                    <v-col cols="12" md="6">
                      <v-select
                        label="STATUS"
                        filled
                        dense
                        v-model="table.filters.concluded"
                        :items="statuses"
                        item-value="value"
                        item-text="text"
                        :loading="table.loading"
                      ></v-select>
                    </v-col>

                    <v-col cols="12">
                      <v-text-field
                        label="ENDEREÇO"
                        filled
                        dense
                        v-model="table.filters.address"
                        :loading="table.loading"
                        @input="table.filters.address = table.filters.address.toUpperCase()"
                        v-on:keyup.enter="_load"
                      ></v-text-field>
                    </v-col>
                  </v-row>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn
                  color="deep-purple lighten-5"
                  small
                  class="rounded-lg mr-2"
                  elevation="0"
                  @click="_eraser"
                >
                    Limpar <v-icon class="ml-2" small>mdi-eraser</v-icon>
                </v-btn>

                <v-btn
                  color="primary"
                  small
                  class="rounded-lg"
                  elevation="0"
                  @click="_load"
                >
                    Buscar <v-icon class="ml-2" small>mdi-magnify</v-icon>
                </v-btn>
              </v-card-actions>
          </v-card>
        </v-menu>
      </v-col>

      <v-col cols="6" md="3">
        <v-btn
            color="btn-primary"
            class="rounded-lg"
            block
            small
            dark
            @click="_add"
            v-if="$can('appointment_add')"
        >
            Criar compromisso <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" v-if="table.loading">
        <v-row>
          <v-col cols="12" md="6" v-for="index in [0, 1, 2, 3]" :key="index">
            <v-skeleton-loader type="image"></v-skeleton-loader>
          </v-col>
        </v-row>
      </v-col>

      <v-col cols="12" v-for="appointment in table.appointments" :key="appointment.id" v-else>
        <v-hover v-slot="{ hover }">
          <v-card
            v-on:click="_edit(appointment.id)"
            :class="{ 'on-hover': hover }"
            :elevation="hover ? 12 : 2"
          >
            <v-list-item three-line>
              <v-list-item-content>
                <div class="text-overline mb-4 d-flex justify-space-between">
                  {{ appointment.client ? appointment.client.name : '' }}
                  <v-chip
                    color="primary"
                    :outlined="appointment.concluded | concluded"
                    label
                    small
                  >
                    {{ appointment.concluded | statusLabel }}
                  </v-chip>
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
              <v-btn text color="primary" v-on:click="_edit(appointment.id)">
                Ver informações
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-hover>
      </v-col>

      <v-col cols="12">
        <div class="text-center pt-2 mt-4">
          <v-pagination
            v-show="table.pageCount > 1"
            v-model="table.page"
            :length="table.pageCount"
            @input="_load"
            :total-visible="15"
            color="primary"
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
    menuFilter: false,
    table: {
      filters: {
        client_name: '',
        address: '',
        concluded: 'N'
      },
      orderBy: 'date_start',
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
      appointments: [],
      loading: false,
    },
    statuses: [{
      value: 'N', text: 'NÃO CONCLUÍDOS'
    }, {
      value: 'S', text: 'CONCLUÍDOS'
    }]
  }),
  filters: {
    statusLabel(value){
      return value == 'S' ? 'CONCLUÍDO' : 'PENDENTE';
    },
    statusColor(value){
      return value == 'S' ? 'green' : 'primary';
    },
    concluded(value){
      return value == 'S';
    },
  },
  mounted() {
    this._start();
  },
  methods: {
    _start(){
      this._load();
    },
    async _load(){
      this.menuFilter = false;

      let params = {
        page: this.table.page,
        itemsPerPage: this.table.itemsPerPage,
        orderBy: this.table.orderBy,
        relations: [ 'client', 'address', 'order' ],
        ...this.table.filters
      }

      this.table.loading = true;
      await axios.get(`/api/appointment`, { params }).then(response => {
        if(response.data.data.data.length === 0 && this.table.page != 1){
          this.page = 1;
        }

        if(response.data.success){
            this.table.appointments = response.data.data.data;
            this.table.pageCount = response.data.data.last_page;
            this.table.loading = false;
        } else {
          this.$refs.fireDialog.error({ title: 'Erro ao carregar agenda'});
        }
      });
    },
    _edit(id){
      this.$can('appointment_show')
        ? this.$router.push({
            name: 'appointment.show',
            params: { id }
        }) : null
    },
     _add(){
      this.$router.push({ name: 'appointment.create' })
    },
    _eraser(){
      for (const field in this.table.filters) {
        this.table.filters[field] = null;
      }

      this._load();
    }
  }
}
</script>
