<template>
  <div>
    <v-row>
      <v-col cols="12">
        <v-btn dark color="blue" @click="_add" rounded>
            Adicionar <v-icon dark>mdi-plus</v-icon>
        </v-btn>
      </v-col>

      <v-container style="height: 400px;" v-if="table.loading">
        <v-row class="fill-height" align-content="center" justify="center">
          <v-col class="text-subtitle-1 text-center" cols="12">
            CARREGANDO CLIENTES...
          </v-col>
          <v-col cols="6">
            <v-progress-linear
              color="blue"
              indeterminate
              rounded
              height="6"
            ></v-progress-linear>
          </v-col>
        </v-row>
      </v-container>

      <v-col cols="12" v-if="!table.loading">
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
              <tr v-for="client in table.clients" :key="client.id">
                <td>{{ client.name }}</td>
                <td>{{ client.fantasy_name }}</td>
                <td>{{ client.category ? client.category.name : '' }}</td>
                <td>{{ client.cpf  }}</td>
                <td>{{ client.cnpj }}</td>
                <td>
                  <v-menu transition="slide-y-transition" bottom>
                      <template v-slot:activator="{ on, attrs }">
                          <v-btn text block v-bind="attrs" v-on="on">
                              <v-icon>mdi-dots-vertical</v-icon>
                          </v-btn>
                      </template>

                      <v-list nav dense>
                          <v-list-item v-on:click="_edit(client.id)">
                              <v-list-item-icon>
                                  <v-icon outlined color="green">mdi-pencil</v-icon>
                              </v-list-item-icon>
                              <v-list-item-content>
                                  <v-list-item-title> Editar </v-list-item-title>
                              </v-list-item-content>
                          </v-list-item>
                          <v-list-item v-on:click="confirmDelete(client)">
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
      </v-col>

      <v-col cols="12">
        <div class="text-center">
          <v-pagination
            v-model="table.page"
            :length="table.pageCount"
            @input="_load"
            :total-visible="15"
            color="blue"
            circle
          ></v-pagination>
        </div>
      </v-col>
  </v-row>

  <v-dialog v-model="dialog" max-width="350">
    <v-card>
        <v-card-title>
            Confirmar exclusão do cliente?
        </v-card-title>
        <v-card-text>
            {{ deleted.name }}
        </v-card-text>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green" text @click="dialog = false">
                Fechar
            </v-btn>
            <v-btn color="red" text @click="_delete">
                Excluir
            </v-btn>
        </v-card-actions>
    </v-card>
  </v-dialog>
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

        if(response.data.success){
            this.table.clients = response.data.data.data;
            this.table.pageCount = response.data.data.last_page;
            this.table.loading = false;
        }
      });
    },
    async _delete(){
      this.table.loading = true;
      await axios.delete(`api/client/${this.deleted.id}`).then(response => {
        if(response.data.success){
          this.dialog = false;
          this._load();
        }
      });
    },
    filterOnlyCapsText (value, search, item) {
        return value != null && search != null && typeof value === 'string' && value.toString().indexOf(search) !== -1
    },
    confirmDelete(deleted){
        this.deleted = deleted;
        this.dialog = true;
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
