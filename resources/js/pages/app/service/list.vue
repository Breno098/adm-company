<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-card class="mb-4">
      <v-toolbar elevation="0">
        <v-toolbar-title> Serviços </v-toolbar-title>
        <v-progress-linear
          color="primary"
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
          v-if="$role.service.add()"
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
              color="btnPrimary"
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
                  <th class="text-left">VALOR</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="item in table.items"
                  :key="item.id"
                  v-on:click="$role.service.show() ? _edit(item.id) : null"
                >
                  <td>{{ item.name }}</td>
                  <td>{{ item.default_value }}</td>
                  <td>
                    <v-menu
                      transition="slide-y-transition"
                      bottom
                      v-if="$role.service.show() || $role.service.delete()"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn text block v-bind="attrs" v-on="on">
                                <v-icon>mdi-dots-vertical</v-icon>
                            </v-btn>
                        </template>

                        <v-list nav dense>
                            <v-list-item
                              v-on:click="_edit(item.id)"
                              v-if="$role.service.show()"
                            >
                              <v-list-item-icon>
                                  <v-icon outlined color="btnPrimary">mdi-eye</v-icon>
                              </v-list-item-icon>
                              <v-list-item-content>
                                  <v-list-item-title> Visualizar </v-list-item-title>
                              </v-list-item-content>
                            </v-list-item>
                            <v-list-item
                              v-on:click="_delete(item)"
                              v-if="$role.client.delete()"
                            >
                              <v-list-item-icon>
                                  <v-icon outlined color="btnDanger">mdi-delete</v-icon>
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
              :total-visible="10"
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

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: 'Serviços' }
  },
  data: () => ({
    table: {
      filters: {
        name: '',
        category_id: null
      },
      orderBy: 'name',
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
      items: [],
      loading: false,
    },
    categories: []
  }),
  mounted() {
    this._start();
  },
  methods: {
    async _start(){
      this._load();
    },
    async _load(){
      let params = {
        page: this.table.page,
        itemsPerPage: this.table.itemsPerPage,
        orderBy: this.table.orderBy,
        relations: [ 'categories' ],
        ...this.table.filters
      }

      this.table.loading = true;
      await axios.get('/api/service', { params }).then(response => {
        if(response.data.data.data.length === 0 && this.table.page != 1){
          this.table.page = 1;
          this._load()
        }

        this.table.loading = false;

        if(response.data.success){
          this.table.items = response.data.data.data;
          this.table.pageCount = response.data.data.last_page;
        } else {
          this.$refs.fireDialog.error({ title: 'Erro ao carregar Serviços'});
        }
      }).catch(error => {
        this.$refs.fireDialog.error({ title: 'Erro ao carregar Serviços'});
        this.table.loading = false;
      })
    },
    async _delete(service){
      const ok = await this.$refs.fireDialog.confirm({
          title: 'Deletar Produto',
          message: 'Deseja realmente deletar o produto?',
          textConfirmButton: 'Deletar',
          colorConfirButton: 'btnDanger',
        colorCancelButton: 'btnPrimary'
      })

      if (ok) {
        this.$refs.fireDialog.loading({ title: 'Deletando' });

        await axios.delete(`/api/service/${service.id}`).then(response => {
          if(response.data.success){
            this._load();
            this.$refs.fireDialog.hide();
          }
        });
      }
    },
    _edit(id){
      this.$router.push({
          name: 'service.show',
          params: { id }
      })
    },
    _add(){
      this.$router.push({ name: 'service.create' })
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
