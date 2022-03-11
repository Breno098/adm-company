<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-card class="mb-4">
      <v-toolbar elevation="0">
        <v-toolbar-title> Usuários </v-toolbar-title>
        <v-progress-linear
          indeterminate
          height="4"
          bottom
          absolute
          :active="table.loading"
        ></v-progress-linear>

        <v-spacer></v-spacer>

        <v-btn
          dark
          color="primary"
          @click="_add"
          rounded
          small
          v-if="$role.user.add()"
        >
          Adicionar <v-icon dark>mdi-plus</v-icon>
        </v-btn>
      </v-toolbar>
    </v-card>

    <v-expansion-panels class="mb-4">
      <v-expansion-panel>
        <v-expansion-panel-header>
          <span>
            <v-icon :size="15">mdi-magnify</v-icon>
            Filtros
          </span>
        </v-expansion-panel-header>
        <v-expansion-panel-content>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                label="NOME"
                outlined
                dense
                v-model="table.filters.name"
                :loading="table.loading"
                @input="table.filters.name = table.filters.name.toUpperCase()"
                v-on:keyup.enter="_load"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-card-actions class="pb-4">
            <v-spacer></v-spacer>
            <v-btn
              color="btn-primary"
              @click="_load"
              class="px-5"
              rounded
            >
              Buscar <v-icon dark class="ml-2">mdi-magnify</v-icon>
            </v-btn>
            <v-btn
              color="btnCleanFilter"
              @click="_eraser"
              class="px-5"
              rounded
            >
              Limpar <v-icon dark class="ml-2">mdi-eraser</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>

    <v-card>
      <v-card-text>
        <v-simple-table dense>
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
                                <v-icon outlined color="btn-primary">mdi-eye</v-icon>
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
                                <v-icon outlined color="btn-delete">mdi-delete</v-icon>
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
      </v-card-text>

      <v-card-actions>
        <v-row>
          <v-col cols="12" md="10">
            <v-pagination
              v-show="table.pageCount > 1"
              v-model="table.page"
              :length="table.pageCount"
              @input="_load"
              :total-visible="5"
              color="primary"
              circle
            ></v-pagination>
          </v-col>

          <v-col cols="12" md="2">
            <v-select
              v-model="table.itemsPerPage"
              :items="[5, 10, 15, 20, 40, 50]"
              label="Linhas por página"
              dense
              class="mt-2 mx-5"
              :loading="table.loading"
              v-on:change="_load"
            ></v-select>
          </v-col>
        </v-row>
      </v-card-actions>
    </v-card>
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
      filters: {
         name: '',
      },
      orderBy: 'name',
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
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
  },
  methods: {
    async _load(){
       let params = {
        page: this.table.pagTe,
        itemsPerPage: 20,
        orderBy: this.table.orderBy,
         ...this.table.filters
      }

      this.table.loading = true;
      await axios.get('/api/user', { params }).then(response => {
        if(response.data.data.data.length === 0 && this.table.page != 1){
          this.table.page = 1;
          this._load()
        }

        this.table.loading = false;

        if(response.data.success){
          this.table.users = response.data.data.data;
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
          colorConfirButton: 'btn-delete',
        colorCancelButton: 'btn-primary'
      })

      if (ok) {
         this.$refs.fireDialog.loading({ title: 'Deletando' });

         await axios.delete(`/api/user/${user.id}`).then(response => {
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
    },
     _eraser(){
      for (const field in this.table.filters) {
        this.table.filters[field] = null;
      }

      this._load();
    },
    _orderBy(field = ''){
      this.table.orderBy = field;
      this._load();
    },
  }
}
</script>
