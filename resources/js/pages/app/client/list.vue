<template>
  <div>
    <v-row class="justify-center mx-5 my-5">
      <v-col cols="12">
          <v-card class="transparent" elevation="0">
              <v-card-title class="d-flex justify-end mb-6">
                      <v-btn text color="blue" @click="_add">
                          Adicionar <v-icon dark>mdi-plus</v-icon>
                      </v-btn>
              </v-card-title>
              <v-card-text>
                  <v-data-table
                      :headers="table.headers"
                      :items="table.clients"
                      :search="search"
                      :loading="table.loading"
                  >
                      <template v-slot:top>
                          <v-toolbar flat>
                              <v-text-field
                                  v-model="search"
                                  append-icon="mdi-magnify"
                                  label="Procurar"
                                  color="blue"
                                  outlined
                                  dense
                              ></v-text-field>
                          </v-toolbar>
                      </template>
                      <template v-slot:item="row">
                          <tr>
                              <td>{{row.item.name}}</td>
                              <td>{{row.item.fantasy_name}}</td>
                              <td>{{row.item.type}}</td>
                              <td>{{row.item.document}}</td>
                              <td>
                                  <v-menu transition="slide-y-transition" bottom>
                                      <template v-slot:activator="{ on, attrs }">
                                          <v-btn text block v-bind="attrs" v-on="on">
                                              <v-icon>mdi-dots-vertical</v-icon>
                                          </v-btn>
                                      </template>

                                      <v-list nav dense>
                                          <v-list-item v-on:click="_edit(row.item.id)">
                                              <v-list-item-icon>
                                                  <v-icon outlined color="green">mdi-pencil</v-icon>
                                              </v-list-item-icon>
                                              <v-list-item-content>
                                                  <v-list-item-title> Editar </v-list-item-title>
                                              </v-list-item-content>
                                          </v-list-item>
                                          <v-list-item v-on:click="confirmDelete(row.item)">
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
                      </template>
                  </v-data-table>
              </v-card-text>
          </v-card>
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
  metaInfo () {
    return { title: 'Clientes' }
  },
  data: () => ({
    dialog: false,
    deleted: {},
    search: '',
    table: {
      clients: [],
      loading: false,
      headers: [{
          text: 'NOME',
          value: 'name'
      }, {
          text: 'NOME FANTASIA',
          value: 'fantasy_name'
      }, {
          text: 'TIPO',
          value: 'type'
      }, {
          text: 'DOCUMENTO',
          value: 'document'
      }, {} ],
    }
  }),
  mounted() {
    this._load();
  },
  methods: {
    async _load(){
      this.table.loading = true;
      await axios.get('api/client').then(response => {
          if(response.data.success){
              this.table.clients = response.data.data;
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
          name: 'client.form',
          params: { id }
      })
    },
     _add(){
      this.$router.push({ name: 'client.form' })
    }
  }
}
</script>
