<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>
    
    <v-row>
      <v-col cols="12">
        <v-card elevation="0">
          <v-toolbar elevation="0">
            <v-toolbar-title> Usuários </v-toolbar-title>
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
              v-if="$role.user.add()"
            >
              Adicionar <v-icon dark>mdi-plus</v-icon>
            </v-btn>
          </v-toolbar>

          <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">NOME</th>
                  <th class="text-left">EMAIL</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr  
                  v-for="user in table.users" 
                  :key="user.id" 
                  v-on:click="$role.user.show() ? _edit(user.id) : null"
                >
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>
                    <v-menu 
                      transition="slide-y-transition" 
                      bottom
                      v-if="$role.user.show() || $role.user.delete()"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn text block v-bind="attrs" v-on="on">
                                <v-icon>mdi-dots-vertical</v-icon>
                            </v-btn>
                        </template>

                        <v-list nav dense>
                            <v-list-item 
                              v-on:click="_edit(user.id)"
                              v-if="$role.user.show()"
                            >
                              <v-list-item-icon>
                                  <v-icon outlined color="green">mdi-eye</v-icon>
                              </v-list-item-icon>
                              <v-list-item-content>
                                  <v-list-item-title> Visualizar </v-list-item-title>
                              </v-list-item-content>
                            </v-list-item>
                            <v-list-item 
                              v-on:click="_delete(user)"
                              v-if="$role.user.delete()"
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
import { mapGetters } from 'vuex';

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: 'Usuários' }
  },
  data: () => ({
    dialog: false,
    search: '',
    table: {
      filters: {},
      page: 1,
      pageCount: 0,
      users: [],
      loading: false,
    }
  }),
   computed: {
    ...mapGetters({
      user: 'auth/user'
    })
  },
  mounted() {
    this._load();

    console.log(this.user)
  },
  methods: {
    async _load(){
       let params = { 
        page: this.table.pagTe, 
        itemsPerPage: 20,
        ...this.filters 
      }

      this.table.loading = true;
      await axios.get('api/user', { params }).then(response => {
        if(response.data.data.data.length === 0 && this.table.page != 1){
          this.table.page = 1;
          this._load()
        }

        this.table.loading = false;

        if(response.data.success){
          let listUser = response.data.data.data.filter(user => user.id !== this.user.id);

          this.table.users = listUser;
          this.table.pageCount = response.data.data.last_page;
        } else {
          this.$refs.fireDialog.error({ title: 'Erro ao carregar usuários'});
        }
      }).catch(error => {
        this.$refs.fireDialog.error({ title: 'Erro ao carregar usuários'});
        this.table.loading = false;
      })
    },
    async _delete(user){
      const ok = await this.$refs.fireDialog.confirm({
          title: 'Deletar Usuário',
          message: 'Deseja realmente deletar a usuário?',
          textConfirmButton: 'Deletar',
          colorConfirButton: 'red'
      })

      if (ok) {
         this.$refs.fireDialog.loading({ title: 'Deletando' });

         await axios.delete(`api/user/${user.id}`).then(response => {
          if(response.data.success){
            this._load();
            this.$refs.fireDialog.hide();
          }
        });
      }
    },
    _edit(id){
      this.$router.push({
          name: 'user.show',
          params: { id }
      })
    },
     _add(){
      this.$router.push({ name: 'user.create' })
    }
  }
}
</script>
