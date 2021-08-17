<template>
  <div>
    <v-row>
      <v-col cols="12">
        <v-btn color="blue" @click="_add" rounded dark>
            Adicionar <v-icon dark>mdi-plus</v-icon>
        </v-btn>
      </v-col>

      <v-col cols="12">
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">NOME</th>
                <th class="text-left">VALOR</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in table.items" :key="item.id">
                <td>{{ item.name }}</td>
                <td>{{ item.default_value }}</td>
                <td>
                  <v-menu transition="slide-y-transition" bottom>
                      <template v-slot:activator="{ on, attrs }">
                          <v-btn text block v-bind="attrs" v-on="on">
                              <v-icon>mdi-dots-vertical</v-icon>
                          </v-btn>
                      </template>

                      <v-list nav dense>
                          <v-list-item v-on:click="_edit(item.id)">
                              <v-list-item-icon>
                                  <v-icon outlined color="green">mdi-pencil</v-icon>
                              </v-list-item-icon>
                              <v-list-item-content>
                                  <v-list-item-title> Editar </v-list-item-title>
                              </v-list-item-content>
                          </v-list-item>
                          <v-list-item v-on:click="confirmDelete(item)">
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
  props: {
    type: String
  },
  metaInfo () {
    return { title: 'Produtos' }
  },
  data: () => ({
    dialog: false,
    deleted: {},
    search: '',
    table: {
      filters: {},
      page: 1,
      pageCount: 0,
      items: [],
      loading: false,
    }
  }),
  mounted() {
    this._load();
  },
  methods: {
    async _load(){
      this.table.filters.type = 'product';
      
      let params = { 
        page: this.table.page, 
        pagination: true,
        itemsPerPage: 20,
        ...this.table.filters 
      }

      this.table.loading = true;
      await axios.get('api/item', { params }).then(response => {
        if(response.data.data.data.length === 0 && this.table.page != 1){
          this.table.page = 1;
          this._load()
        }

        if(response.data.success){
            this.table.items = response.data.data.data;
            this.table.pageCount = response.data.data.last_page;
            this.table.loading = false;
        }
      });
    },
    async _delete(){
      this.table.loading = true;
      await axios.delete(`api/item/${this.deleted.id}`).then(response => {
        if(response.data.success){
          this.dialog = false;
          this._load();
        }
      });
    },
    confirmDelete(deleted){
        this.deleted = deleted;
        this.dialog = true;
    },
    _edit(id){
      this.$router.push({
          name: 'product.form',
          params: { id }
      })
    },
     _add(){
      this.$router.push({ name: 'product.form' })
    }
  }
}
</script>
