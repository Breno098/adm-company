<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-row>
      <v-col cols="12">
        <v-card elevation="0">
          <v-toolbar elevation="0" class="mb-2">
            <v-toolbar-title> {{ titlePage }} </v-toolbar-title>
            <v-progress-linear
              color="primary"
              indeterminate
              height="4"
              bottom
              absolute
              :active="loading"
            ></v-progress-linear>

            <v-spacer></v-spacer>

            <v-btn
              v-if="(!idByRoute && $role.category.add()) || (idByRoute && $role.category.update()) "
              color="btnPrimary"
              @click="_store"
              :loading="loading"
              rounded
              dark
              small
            >
              Salvar <v-icon dark class="ml-2">mdi-content-save</v-icon>
            </v-btn>
          </v-toolbar>

          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                label="NOME"
                color="primary"
                outlined
                dense
                v-model="category.name"
                :loading="loading"
                :rules="[rules.required]"
                :error="errors.name"
                @input="category.name = category.name.toUpperCase()"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                v-model="category.type"
                :items="types"
                item-text="label"
                item-value="value"
                label="TIPO"
                outlined
                dense
                :loading="loading"
                :rules="[rules.required]"
                :error="errors.type"
              ></v-select>
            </v-col>

            <v-col cols="12">
              <v-textarea
                label="DESCRIÇÃO"
                color="primary"
                outlined
                dense
                v-model="category.description"
                :loading="loading"
                hint="Detalhes da categoria"
                @input="category.description = category.description.toUpperCase()"
              ></v-textarea>
            </v-col>

            <v-col cols="12">
              <v-chip-group active-class="green" column v-model="category.icon">
                <v-chip v-for="icon in icons" :key="icon" :value="icon" large color="primary">
                  <v-icon>{{ icon }}</v-icon>
                </v-chip>
              </v-chip-group>
            </v-col>
          </v-row>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              v-if="(!idByRoute && $role.client.add()) || (idByRoute && $role.client.update()) "
              color="btnPrimary"
              @click="_store"
              :loading="loading"
              rounded
              dark
            >
              Salvar <v-icon dark class="ml-2">mdi-content-save</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
         </v-card>
      </v-col>
    </v-row>
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
    loading: false,
    menuBirthDate: false,
    errors: {
      name: false,
      type: false
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    icons: [
      'mdi-star',
      'mdi-wrench',
      'mdi-folder-multiple',
      'mdi-credit-card',
      'mdi-pipe-disconnected',
      'mdi-access-point',
      'mdi-forklift',
      'mdi-mouse',
      'mdi-checkbox-marked-circle-outline',
      'mdi-account-convert',
      'mdi-sitemap',
      'mdi-movie',
      'mdi-sofa',
      'mdi-city',
      'mdi-format-list-checks',
      'mdi-cards',
      'mdi-car-hatchback',
      'mdi-run',
      'mdi-lock-reset',
      'mdi-developer-board',
      'mdi-alert'
    ],
    category: {
      description: null,
      name: null,
      icon: null,
      type: null
    },
    types: [{
      label: 'PRODUTOS',
      value: 'product'
    }, {
      label: 'SERVIÇOS',
      value: 'service'
    }, {
      label: 'CUSTOS',
      value: 'expense'
    }, {
      label: 'CLIENTES',
      value: 'client'
    }, {
      label: 'PERMISSÃO',
      value: 'role'
    }],
  }),
  computed: {
    birthDateFormat () {
        return this.category.birth_date ? moment(this.category.birth_date).format('DD/MM/YYYY') : ''
    },
    titlePage(){
      return this.$route.params.id ? 'Editar Categoria' : 'Adicionar Categoria';
    },
    idByRoute(){
      return this.$route.params.id;
    }
  },
  mounted(){
    if(this.idByRoute){
      this._load();
    }
  },
  methods: {
    async _load(){
      this.loading = true;
      await axios.get(`api/category/${this.idByRoute}`).then(response => {
        if(response.data.success){
            return this.category = response.data.data;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar dados da categoria' })
        this.$refs.fireDialog.hide(1500);
      });
      this.loading = false;
    },
    async _store(){
      for (const field in this.errors) {
        if(!this.category[field])
          return this.errors[field] = true;
      }

      this.loading = true;

      this.$refs.fireDialog.loading({ title: this.idByRoute ? 'Atualizando...' : 'Salvando...' })

      const response = !this.idByRoute ? await axios.post('api/category', this.category) : await axios.put(`api/category/${this.idByRoute}`, this.category);

      this.loading = false;

      if(response.data.success){
        this.$refs.fireDialog.success({ title: 'Categoria salvo com sucesso' })
        return setTimeout(() => this.$router.push({ name: 'category.index' }), 1500);
      }

      this.$refs.fireDialog.error({ title: 'Error aos salvar categoria' })
    },
  }

}
</script>
