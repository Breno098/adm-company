<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-row>
      <v-col cols="12">
        <v-card elevation="0">
          <v-toolbar elevation="0">
            <v-toolbar-title> Produtos/Peças </v-toolbar-title>
            <v-progress-linear
              color="blue"
              indeterminate
              height="4"
              bottom
              absolute
              :active="table.loading"
            ></v-progress-linear>

            <v-spacer></v-spacer>

            <v-btn dark color="blue" @click="_add" rounded small>
              Adicionar <v-icon dark>mdi-plus</v-icon>
            </v-btn>
          </v-toolbar>

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
                <tr v-for="item in table.items" :key="item.id" v-on:click="_edit(item.id)">
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
                            <v-list-item v-on:click="_delete(item)">
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
    async _delete(product){
      const ok = await this.$refs.fireDialog.confirm({
          title: 'Deletar Produto',
          message: 'Deseja realmente deletar o produto?',
          textConfirmButton: 'Deletar',
          colorConfirButton: 'red'
      })

      if (ok) {
        this.$refs.fireDialog.loading({ title: 'Deletando' });

        await axios.delete(`api/item/${product.id}`).then(response => {
          if(response.data.success){
            this._load();
            this.$refs.fireDialog.hide();
          }
        });
      }
    },
    _edit(id){
      this.$router.push({
          name: 'product.show',
          params: { id }
      })
    },
     _add(){
      this.$router.push({ name: 'product.create' })
    }
  }
}
</script>
