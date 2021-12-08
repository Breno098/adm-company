<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-card class="mb-4">
      <v-toolbar elevation="0">
        <v-toolbar-title> {{ titlePage }} </v-toolbar-title>
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
          v-if="$role.employee_receipt.add()"
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
                label="TIPO DE recibo"
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
                <th class="text-left">FUNCIONÁRIO</th>
                <th class="text-left">PERIODO</th>
                <th class="text-left">CARGO</th>
                <th class="text-left">DATA EMISSÃO</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="receipt in table.receipts"
                :key="receipt.id"
                v-on:click="$role.employee_receipt.show() ? _edit(receipt.id) : null"
              >
                <td>{{ receipt.employee.name }}</td>
                <td>
                  {{ receipt.date_start | formatDate }} - {{ receipt.date_end | formatDate }}
                </td>
                <td>{{ receipt.employee.position ?  receipt.employee.position.name : '' }}</td>
                <td>{{ receipt.created_at | formatDate }}</td>
                <td>
                  <v-menu
                    transition="slide-y-transition"
                    bottom
                    v-if="$role.employee_receipt.show() || $role.employee_receipt.delete()"
                  >
                      <template v-slot:activator="{ on, attrs }">
                          <v-btn text block v-bind="attrs" v-on="on">
                              <v-icon>mdi-dots-vertical</v-icon>
                          </v-btn>
                      </template>

                      <v-list nav dense>
                          <v-list-item
                            v-on:click="_edit(receipt.id)"
                            v-if="$role.employee_receipt.show()"
                          >
                              <v-list-item-icon>
                                  <v-icon outlined color="btnPrimary">mdi-eye</v-icon>
                              </v-list-item-icon>
                              <v-list-item-content>
                                  <v-list-item-title> Visualizar </v-list-item-title>
                              </v-list-item-content>
                          </v-list-item>
                          <v-list-item
                            v-on:click="_delete(receipt)"
                            v-if="$role.employee_receipt.delete()"
                          >
                              <v-list-item-icon>
                                  <v-icon outlined color="btnDanger">mdi-delete</v-icon>
                              </v-list-item-icon>
                              <v-list-item-content>
                                  <v-list-item-title> Deletar </v-list-item-title>
                              </v-list-item-content>
                          </v-list-item>

                          <v-list-item
                            v-on:click="_download(receipt)"
                            v-if="$role.employee_receipt.download() || $role.employee_receipt.show()"
                          >
                              <v-list-item-icon>
                                  <v-icon outlined color="btnSecondary">mdi-download</v-icon>
                              </v-list-item-icon>
                              <v-list-item-content>
                                  <v-list-item-title> Baixar arquivo </v-list-item-title>
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

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: this.titlePage }
  },
  data: () => ({
    titlePage: 'Recibos de funcionários',
    table: {
      filters: {
      },
      orderBy: 'date_end',
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
      receipts: [],
      loading: false,
    },
    categories: []
  }),
  computed: {
  },
  mounted() {
    this._load();
  },
  filters: {
    formatDate(date){
      return date ? moment(date).format('DD/MM/YYYY') : '';
    },
  },
  methods: {
    async _load(){
      let params = {
        page: this.table.page,
        itemsPerPage: this.table.itemsPerPage,
        orderBy: this.table.orderBy,
        relations: [ 'employee.position' ],
        ...this.table.filters
      }

      this.table.loading = true;
      await axios.get('api/employee-receipt', { params }).then(response => {
        if(response.data.data.data.length === 0 && this.table.page != 1){
          this.table.page = 1;
          this._load()
        }

        this.table.loading = false;

        if(response.data.success){
            this.table.receipts = response.data.data.data;
            this.table.pageCount = response.data.data.last_page;
        } else {
          this.$refs.fireDialog.error({ title: 'Erro ao carregar recibos'});
        }
      }).catch(error => {
        this.$refs.fireDialog.error({ title: 'Erro ao carregar recibos'});
        this.table.loading = false;
      })
    },
    async _delete(employeeReceipt){
      const ok = await this.$refs.fireDialog.confirm({
          title: 'Deletar recibo',
          message: 'Deseja realmente deletar o recibo?',
          textConfirmButton: 'Deletar',
          colorConfirButton: 'btnDanger',
          colorCancelButton: 'btnPrimary'
      })

      if (ok) {
        this.$refs.fireDialog.loading({ title: 'Deletando' });

        await axios.delete(`api/employee-receipt/${employeeReceipt.id}`).then(response => {
          if(response.data.success){
            this._load();
            this.$refs.fireDialog.hide();
          }
        });
      }
    },
    _edit(id){
      this.$router.push({
          name: 'employee-receipt.show',
          params: { id }
      })
    },
    _add(){
      this.$router.push({ name: 'employee-receipt.create' })
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
    _download(receipt){
      window.open(`api/employee-receipt/${receipt.id}/download`);
    }
  }
}
</script>
