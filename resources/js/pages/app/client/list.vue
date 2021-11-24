<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-card class="mb-4">
      <v-toolbar elevation="0">
        <v-toolbar-title> Clientes </v-toolbar-title>
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
          v-if="$role.client.add()"
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

            <v-col cols="12" md="6">
              <v-select
                v-model="table.filters.category_id"
                :items="categories"
                item-text="name"
                item-value="id"
                label="TIPO DE CLIENTE"
                outlined
                dense
                :loading="table.loading"
                v-on:change="_load"
              ></v-select>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="CPF"
                v-mask="'###.###.###-##'"
                outlined
                dense
                v-model="table.filters.cpf"
                :loading="table.loading"
                v-on:keyup.enter="_load"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="CNPJ"
                v-mask="'##.###.###/####-##'"
                outlined
                dense
                v-model="table.filters.cnpj"
                :loading="table.loading"
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
                <th class="text-left" v-on:click="_orderBy('name')">NOME</th>
                <th class="text-left" v-on:click="_orderBy('fantasy_name')">NOME FANTASIA</th>
                <th class="text-left" v-on:click="_orderBy('category_id')">TIPO</th>
                <th class="text-left" v-on:click="_orderBy('cpf')">CPF</th>
                <th class="text-left" v-on:click="_orderBy('cnpj')">CNPJ</th>
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
                                  <v-icon outlined color="btnPrimary">mdi-eye</v-icon>
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

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: 'Clientes' }
  },
  data: () => ({
    table: {
      filters: {
        name: '',
        cnpj: '',
        cpf: '',
        category_id: null
      },
      orderBy: 'name',
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
      clients: [],
      loading: false,
    },
    categories: []
  }),
  computed: {
  },
  mounted() {
    this._load();

    this._loadCategories();
  },
  methods: {
    async _load(){
      let params = {
        page: this.table.page,
        itemsPerPage: this.table.itemsPerPage,
        orderBy: this.table.orderBy,
        relations: [ 'category' ],
        ...this.table.filters
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
          colorConfirButton: 'btnDanger',
          colorCancelButton: 'btnPrimary'
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
    async _loadCategories(){
      this.table.loading = true;
      await axios.get(`api/category?type=client`).then(response => {
        if(response.data.success){
          this.categories = [
            { id: null, name: 'TODOS' },
            ...response.data.data
          ];
          return;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar tipos' })
      });
      this.table.loading = false;
    },
  }
}
</script>
