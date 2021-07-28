<template>
  <div>
    <v-row>
      <v-col cols="12">
        <v-btn text color="blue" @click="_add">
            Adicionar <v-icon dark>mdi-plus</v-icon>
        </v-btn>
      </v-col>
      <v-col cols="12">
        <v-data-table
            :headers="table.headers"
            :items="table.items"
            :loading="table.loading"
            hide-default-footer
        >
            <template v-slot:item="row">
              <tr>
                  <td>{{row.item.client ? row.item.client.name : '' }}</td>
                  <td>{{row.item.address ? row.item.address.street : ''}}</td>
                  <td>{{row.item.amount ? `R$ ${row.item.amount}` : ''}}</td>
                  <td>
                    <v-chip v-if="row.item.status" :color="row.item.status.color" class="py-2">{{ row.item.status.name }}</v-chip>
                  </td>
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

        <div class="text-center pt-2 mt-4">
          <v-pagination
            v-model="table.page"
            :length="table.pageCount"
            @input="_load"
            :total-visible="15"
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
  metaInfo () {
    return { title: 'Ordens de Serviços' }
  },
  data: () => ({
    dialog: false,
    deleted: {},
    search: '',
    table: {
      page: 1,
      pageCount: 0,
      items: [],
      loading: false,
      headers: [{
          text: 'Cliente',
          value: 'client.name'
      }, {
          text: 'Endereço',
          value: 'addrees.street'
      }, {
          text: 'VALOR TOTAL',
          value: 'amount'
      }, {
          text: 'STATUS',
          value: 'status.name'
      }, {} ],
    }
  }),
  mounted() {
    this._load();
  },
  methods: {
    async _load(){
      this.table.loading = true;
      await axios.get(`api/order?page=${this.table.page}`).then(response => {
        console.log(response.data.data);

        if(response.data.success){
            this.table.items = response.data.data.data;
            this.table.pageCount = response.data.data.last_page;
            this.table.loading = false;
        }
      });
    },
    async _delete(){
      this.table.loading = true;
      await axios.delete(`api/order/${this.deleted.id}`).then(response => {
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
          name: 'order.form',
          params: { id }
      })
    },
     _add(){
      this.$router.push({ name: 'order.form' })
    }
  }
}
</script>
