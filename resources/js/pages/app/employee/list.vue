<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <p class="font-weight-bold mb-5 text-h5">
      <v-icon color="primary">mdi-account-outline</v-icon>
      Funcionários
    </p>

    <v-row class="mb-2">
      <v-col cols="6" md="10">
        <v-menu
          :close-on-content-click="false"
          :nudge-width="200"
          offset-y
          v-model="menuFilter"
        >
          <template v-slot:activator="{ on, attrs }">
              <v-btn text small v-bind="attrs" v-on="on">
                  Filtros
                  <v-icon color="primary">mdi-chevron-down</v-icon>
              </v-btn>
          </template>

          <v-card width="800">
              <v-card-text>
                  <v-row>
                   <v-col cols="12" md="6">
                    <v-text-field
                      label="NOME"
                      filled
                      dense
                      v-model="table.filters.name"
                      :loading="table.loading"
                      @input="table.filters.name = table.filters.name.toUpperCase()"
                      v-on:keyup.enter="_load"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" md="6">
                    <v-text-field
                      label="CPF"
                      v-mask="'###.###.###-##'"
                      filled
                      dense
                      v-model="table.filters.cpf"
                      :loading="table.loading"
                      v-on:keyup.enter="_load"
                    ></v-text-field>
                  </v-col>
                  </v-row>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn
                  color="deep-purple lighten-5"
                  small
                  class="rounded-lg mr-2"
                  elevation="0"
                  @click="_eraser"
                >
                    Limpar <v-icon class="ml-2" small>mdi-eraser</v-icon>
                </v-btn>

                <v-btn
                  color="primary"
                  small
                  class="rounded-lg"
                  elevation="0"
                  @click="_load"
                >
                    Buscar <v-icon class="ml-2" small>mdi-magnify</v-icon>
                </v-btn>
              </v-card-actions>
          </v-card>
        </v-menu>

        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn text small @click="_eraser" v-bind="attrs" v-on="on">
               <v-icon small>mdi-eraser</v-icon>
            </v-btn>
          </template>
          <span>Limpar filtros</span>
        </v-tooltip>
      </v-col>

      <v-col cols="6" md="2">
        <v-btn
            color="btn-primary"
            class="rounded-lg"
            block
            small
            dark
            @click="_add"
            v-if="$can('employee_add')"
        >
            Adicionar <v-icon small class="ml-2">mdi-plus</v-icon>
        </v-btn>
      </v-col>
    </v-row>

    <v-card>
      <v-progress-linear
        indeterminate
        top
        :active="table.loading"
      ></v-progress-linear>

      <v-card-text>
        <v-simple-table dense>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left" v-on:click="_orderBy('name')">NOME</th>
                <th class="text-left" v-on:click="_orderBy('admission_date')">DATA ADMISSÃO</th>
                <th class="text-left" v-on:click="_orderBy('cpf')">CPF</th>
                <th class="text-left" v-on:click="_orderBy('rg')">CNPJ</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="employee in table.employees"
                :key="employee.id"
                v-on:click="_edit(employee.id)"
              >
                <td>{{ employee.name }}</td>
                <td>{{ employee.admission_date | formatDMY }}</td>
                <td>{{ employee.cpf  }}</td>
                <td>{{ employee.rg }}</td>
                <td>
                  <v-menu
                    transition="slide-y-transition"
                    bottom
                    v-if="$can('employee_show') || $can('employee_delete')"
                  >
                      <template v-slot:activator="{ on, attrs }">
                          <v-btn text block v-bind="attrs" v-on="on">
                              <v-icon>mdi-dots-vertical</v-icon>
                          </v-btn>
                      </template>

                      <v-list nav dense>
                          <v-list-item
                            v-on:click="_edit(employee.id)"
                            v-if="$can('employee_show')"
                          >
                              <v-list-item-icon>
                                  <v-icon outlined color="btn-primary">mdi-eye</v-icon>
                              </v-list-item-icon>
                              <v-list-item-content>
                                  <v-list-item-title> Visualizar </v-list-item-title>
                              </v-list-item-content>
                          </v-list-item>
                          <v-list-item
                            v-on:click="_delete(employee)"
                            v-if="$can('employee_delete')"
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
import moment from 'moment';

moment.locale('pt-br');

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: 'Funcionários' }
  },
  data: () => ({
    menuFilter: false,
    table: {
      filters: {
        name: '',
        cpf: '',
      },
      orderBy: 'name',
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
      employees: [],
      loading: false,
    },
    categories: []
  }),
  mounted() {
    this._load();
  },
  methods: {
    async _load(){
      this.menuFilter = false;

      let params = {
        page: this.table.page,
        itemsPerPage: this.table.itemsPerPage,
        orderBy: this.table.orderBy,
        relations: [],
        ...this.table.filters
      }

      this.table.loading = true;
      await axios.get('/api/employee', { params }).then(response => {
        if(response.data.data.data.length === 0 && this.table.page != 1){
          this.table.page = 1;
          this._load()
        }

        this.table.loading = false;

        if(response.data.success){
            this.table.employees = response.data.data.data;
            this.table.pageCount = response.data.data.last_page;
        } else {
          this.$refs.fireDialog.error({ title: 'Erro ao carregar Funcionários'});
        }
      }).catch(error => {
        this.$refs.fireDialog.error({ title: 'Erro ao carregar Funcionários'});
        this.table.loading = false;
      })
    },
    async _delete(employee){
      const ok = await this.$refs.fireDialog.confirm({
          title: 'Deletar Funcionário',
          message: 'Deseja realmente deletar o Funcionário?',
          textConfirmButton: 'Deletar',
          colorConfirButton: 'btn-delete',
          colorCancelButton: 'btn-primary'
      })

      if (ok) {
        this.$refs.fireDialog.loading({ title: 'Deletando' });

        await axios.delete(`/api/employee/${employee.id}`).then(response => {
          if(response.data.success){
            this._load();
            this.$refs.fireDialog.hide();
          }
        });
      }
    },
    _edit(id){
      this.$can('employee_show')
        ? this.$router.push({
            name: 'employee.show',
            params: { id }
        }) : null
    },
    _add(){
      this.$router.push({ name: 'employee.create' })
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
