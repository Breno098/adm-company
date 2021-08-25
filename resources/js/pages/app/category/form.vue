<template>
  <div>
    <v-row>
      <v-col cols="12">
        <v-text-field
          label="NOME"
          color="blue"
          outlined
          dense
          v-model="category.name"
          :loading="loading"
          :rules="[rules.required]"
          :error="errors.name"
        ></v-text-field>
      </v-col>

      <v-col cols="12">
        <v-textarea
          label="DESCRIÇÃO"
          color="blue"
          outlined
          dense
          v-model="category.description"
          :loading="loading"
          hint="Detalhes da categoria"
        ></v-textarea>
      </v-col>

      <v-col cols="12">
        <v-chip-group active-class="green" column v-model="category.icon">
          <v-chip v-for="icon in icons" :key="icon" :value="icon" large color="blue">
            <v-icon>{{ icon }}</v-icon>
          </v-chip>
        </v-chip-group>
      </v-col>

      <v-col cols="12">
          <v-btn color="green darken-1" @click="_store" :loading="loading" rounded>
              Salvar <v-icon dark class="ml-2">mdi-content-save</v-icon>
          </v-btn>
      </v-col>
    </v-row>

    <v-dialog v-model="dialog.show" max-width="350">
      <v-card>
          <v-card-title>
              <div class="mx-auto"> {{ dialog.message }} </div>
          </v-card-title>

          <v-card-text class="text-center py-5" >
            <v-progress-circular v-if="loading" :width="7" color="blue" :size="70" indeterminate></v-progress-circular>
            <v-icon v-else :size="70" :color="dialog.status === 'success' ? 'green' : 'red' ">
              {{ dialog.status === 'success' ? 'mdi-check' : 'mdi-alert' }}
            </v-icon>
          </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: 'Categorias' }
  },
  data: () => ({
    loading: false,
    menuBirthDate: false,
    dialog: {
      show: false,
      message: '',
      status: null
    },
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
    }
  }),
  computed: {
    birthDateFormat () {
        return this.category.birth_date ? moment(this.category.birth_date).format('DD/MM/YYYY') : ''
    },
  },
  mounted(){
    if(this.$route.params.id){
      this._load();
    }
  },
  methods: {
    _modal(message, status){
      this.dialog.message = message;
      this.dialog.status = status;
      this.dialog.show = true;
    },
    async _load(){
      let id = this.$route.params.id;

      this.loading = true;

      await axios.get(`api/category/${id}`).then(response => {
        if(response.data.success){
            return this.category = response.data.data;
        }

        this._modal('Error ao carregar dados categoria', 'error');
        setTimeout(() => this.$router.push({ name: 'category.index' }), 1500);
      });

      this.loading = false;

    },
    async _store(){
      if(!this.category.name){
        return this.errors.name = true;
      }

      let id = this.$route.params.id;

      this.loading = true;
      this.dialog.show = true;
      this.dialog.message = id ? 'Atualizando...' : 'Salvando...';

      let response = null;

      if(!id){
        response = await axios.post('api/category', this.category)
      } else {
        response = await axios.put(`api/category/${id}`, this.category)
      }

      this.loading = false;

      if(response.data.success){
        this._modal('Categoria salvo com sucesso', 'success');
        return setTimeout(() => this.$router.push({ name: 'category.index' }), 1500);
      }

      this._modal('Error aos salvar categorye', 'error');
    },
  }

}
</script>
