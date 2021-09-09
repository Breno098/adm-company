<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>
    
    <v-row>
      <v-col cols="12">
        <v-card elevation="0">
          <v-toolbar elevation="0">
            <v-toolbar-title> Categorias </v-toolbar-title>
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
                  <th></th>
                  <th class="text-left">NOME</th>
                  <th class="text-left">DESCRIÇÃO</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="category in table.categories" :key="category.id" v-on:click="_edit(category.id)">
                  <td> <v-icon color="blue">{{ category.icon }}</v-icon> </td>
                  <td>{{ category.name }}</td>
                  <td>{{ category.description }}</td>
                  <td>
                    <v-menu transition="slide-y-transition" bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn text block v-bind="attrs" v-on="on">
                                <v-icon>mdi-dots-vertical</v-icon>
                            </v-btn>
                        </template>

                        <v-list nav dense>
                            <v-list-item v-on:click="_edit(category.id)">
                                <v-list-item-icon>
                                    <v-icon outlined color="green">mdi-pencil</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title> Editar </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item v-on:click="_delete(category)">
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
    return { title: 'Categorias' }
  },
  data: () => ({
    dialog: false,
    deleted: {},
    search: '',
    table: {
      filters: {},
      page: 1,
      pageCount: 0,
      categories: [],
      loading: false,
    }
  }),
  mounted() {
    this._load();
  },
  methods: {
    async _load(){
       let params = { 
        page: this.table.pagTe, 
        pagination: true,
        itemsPerPage: 20,
        ...this.filters 
      }

      this.table.loading = true;
      await axios.get('api/category', { params }).then(response => {
        if(response.data.data.data.length === 0 && this.table.page != 1){
          this.table.page = 1;
          this._load()
        }

        if(response.data.success){
            this.table.categories = response.data.data.data;
            this.table.pageCount = response.data.data.last_page;
            this.table.loading = false;
        }
      });
    },
    async _delete(category){
      const ok = await this.$refs.fireDialog.confirm({
          title: 'Deletar Categoria',
          message: 'Deseja realmente deletar a categorias?',
          textConfirmButton: 'Deletar',
          colorConfirButton: 'red'
      })

      if (ok) {
         this.$refs.fireDialog.loading({ title: 'Deletando' });

         await axios.delete(`api/category/${category.id}`).then(response => {
          if(response.data.success){
            this._load();
            this.$refs.fireDialog.hide();
          }
        });
      }
    },
    _edit(id){
      this.$router.push({
          name: 'category.show',
          params: { id }
      })
    },
     _add(){
      this.$router.push({ name: 'category.create' })
    }
  }
}
</script>
