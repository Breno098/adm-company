<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-row>
      <v-col cols="12">
        <v-card elevation="0">
          <v-toolbar elevation="0">
            <v-toolbar-title> Clientes </v-toolbar-title>
            <v-progress-linear
              color="blue"
              indeterminate
              height="4"
              bottom
              absolute
              :active="table.loading"
            ></v-progress-linear>

            <v-spacer></v-spacer>

            <v-btn 
              dark 
              color="blue" 
              @click="_add" 
              rounded 
              small 
              v-if="$role.client.add()"
            >
              Adicionar <v-icon dark>mdi-plus</v-icon>
            </v-btn>
          </v-toolbar>

          <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">NOME</th>
                  <th class="text-left">NOME FANTASIA</th>
                  <th class="text-left">TIPO</th>
                  <th class="text-left">CPF</th>
                  <th class="text-left">CNPJ</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr 
                  v-for="client in table.clients" 
                  :key="client.id" 
                  v-on:click="$role.client.show() ? _edit(client.id) : null"
                >
                  <td>{{ client.name }}</td>
                  <td>{{ client.fantasy_name }}</td>
                  <td>{{ client.category ? client.category.name : '' }}</td>
                  <td>{{ client.cpf  }}</td>
                  <td>{{ client.cnpj }}</td>
                  <td>
                    <v-menu 
                      transition="slide-y-transition" 
                      bottom
                      v-if="$role.client.show() || $role.client.delete()"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn text block v-bind="attrs" v-on="on">
                                <v-icon>mdi-dots-vertical</v-icon>
                            </v-btn>
                        </template>

                        <v-list nav dense>
                            <v-list-item 
                              v-on:click="_edit(client.id)" 
                              v-if="$role.client.show()"
                            >
                                <v-list-item-icon>
                                    <v-icon outlined color="green">mdi-eye</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title> Visualizar </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item 
                              v-on:click="_delete(client)" 
                              v-if="$role.client.delete()"
                            >
                                <v-list-item-icon>
                                    <v-icon outlined color="red">mdi-delete</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title> Deletar </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-pagination
              v-model="table.page"
              :length="table.pageCount"
              @input="_load"
              :total-visible="15"
              color="blue"
              circle
            ></v-pagination>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-card>
      </v-col>
  </v-row>
</div>
</template>

<script>
import axios from 'axios';

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: 'Clientes' }
  },
  data: () => ({
    dialog: false,
    deleted: {},
    search: '',
    table: {
      filters: {},
      page: 1,
      pageCount: 0,
      clients: [],
      loading: false,
    }
  }),
  computed: {
  },
  mounted() {
    this._load();
  },
  methods: {
    async _load(){
      let params = { 
        page: this.table.page, 
        pagination: true,
        itemsPerPage: 20,
        ...this.filters 
      }

      this.table.loading = true;
      await axios.get('api/client', { params }).then(response => {
        if(response.data.data.data.length === 0 && this.table.page != 1){
          this.table.page = 1;
          this._load()
        }

        this.table.loading = false;

        if(response.data.success){
            this.table.clients = response.data.data.data;
            this.table.pageCount = response.data.data.last_page;
        } else {
          this.$refs.fireDialog.error({ title: 'Erro ao carregar clientes'});
        }
      }).catch(error => {
        this.$refs.fireDialog.error({ title: 'Erro ao carregar clientes'});
        this.table.loading = false;
      })
    },
    async _delete(client){
      const ok = await this.$refs.fireDialog.confirm({
          title: 'Deletar Cliente',
          message: 'Deseja realmente deletar o cliente?',
          textConfirmButton: 'Deletar',
          colorConfirButton: 'red'
      })

      if (ok) {
        this.$refs.fireDialog.loading({ title: 'Deletando' });

        await axios.delete(`api/client/${client.id}`).then(response => {
          if(response.data.success){
            this._load();
            this.$refs.fireDialog.hide();
          }
        });
      }
    },
    _edit(id){
      this.$router.push({
          name: 'client.show',
          params: { id }
      })
    },
    _add(){
      this.$router.push({ name: 'client.create' })
    }
  }
}
</script>
