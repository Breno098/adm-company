<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <p class="font-weight-bold mb-5 text-h5">
      <v-icon color="primary">mdi-account</v-icon>
      {{ titlePage }}
    </p>

    <v-row class="mb-2">
      <v-col cols="6" offset="6" md="2" offset-md="10">
        <v-btn
          color="btn-primary"
          class="rounded-lg"
          block
          small
          dark
          @click="_store(true)"
          v-if="canSave"
          :loading="loading"
        >
          Salvar <v-icon class="ml-2">mdi-content-save</v-icon>
        </v-btn>
      </v-col>
    </v-row>

    <v-card>
      <v-tabs v-model="tab">
        <v-tabs-slider color="primary"></v-tabs-slider>
        <v-tab>Informações <v-icon class="ml-2">mdi-information</v-icon></v-tab>
        <v-tab>Permissões <v-icon class="ml-2">mdi-lock</v-icon></v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab" class="pt-5 px-3">
        <v-tab-item>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                label="NOME"
                filled
                dense
                v-model="user.name"
                :loading="loading"
                :rules="[rules.required]"
                :error="errors.name"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="EMAIL"
                filled
                dense
                v-model="user.email"
                :loading="loading"
                :rules="[rules.required]"
                :error="errors.email"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="10" v-if="!idByRoute">
              <v-text-field
                label="SENHA INICIAL"
                filled
                dense
                v-model="user.password"
                :loading="loading"
                :rules="[rules.required]"
                :error="errors.password"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2" v-if="!idByRoute">
              <v-btn color="btn-primary" @click="_generateRandomPass" dark block>
                Gerar senha
              </v-btn>
            </v-col>
          </v-row>
        </v-tab-item>

        <v-tab-item>
          <v-row class="mb-2">
            <v-col cols="12" v-for="(group, index) in rolesGroupByTag" :key="index">
              <v-card>
                <v-card-title class="d-flex flex-row justify-space-between">
                  {{ group.tag }}

                   <v-tooltip left>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn color="btn-primary" @click="selectAllTag(group.tag)" text v-bind="attrs" v-on="on">
                        <v-icon>mdi-checkbox-multiple-marked-circle-outline</v-icon>
                      </v-btn>
                    </template>
                    <span>Marcar todas as permissões para {{ group.tag }}</span>
                  </v-tooltip>
                </v-card-title>

                <v-card-text>
                  <v-row>
                    <v-col cols="12" md="4" v-for="(role, index) in group.roles" :key="index">
                      <v-switch
                        inset
                        :label="role.name"
                        v-model="user.roles"
                        :value="role.id"
                      ></v-switch>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-tab-item>
      </v-tabs-items>

      <v-card-actions>
        <v-btn
          color="btn-primary"
          class="rounded-lg"
          small
          dark
          @click="_store"
          v-if="canSave"
          :loading="loading"
        >
          Salvar <v-icon class="ml-2">mdi-content-save</v-icon>
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: this.titlePage }
  },
  data: () => ({
    tab: null,
    loading: false,
    loadingRole: false,
    errors: {
      name: false,
      email: false,
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    user: {
      name: null,
      email: null,
      password: null,
      roles: [],
    },
    rolesGroupByTag: [],
    roles: []
  }),
  computed: {
    titlePage(){
      return this.$route.params.id ? 'Usuário' : 'Adicionar Usuário';
    },
    idByRoute(){
      return this.$route.params.id;
    },
    canSave(){
      return this.$can('user_add') && !this.idByRoute || this.$can('user_update') && this.idByRoute;
    }
  },
  mounted(){
    this._start();
  },
  methods: {
   async _start(){
      if(this.idByRoute){
        await this._load();
      } else {
        this.errors.password = false;
      }

      await this._loadRolesGroupByTag();
    },
    _generateRandomPass(){
      this.user.password = (Math.random() + 1).toString(36).substring(2);
    },
    async _load(){
      this.loading = true;
      await axios.get(`/api/user/${this.idByRoute}`).then(response => {
        if(response.data.success){
          this.user =response.data.data;
          this.user.roles = this.user.roles.map(role => role.id);
          return;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar dados do usuário', time: 1500 })
      });
      this.loading = false;
    },
     async _loadRolesGroupByTag(){
      this.loadingRole = true;
      await axios.get(`/api/role/group-by-tag`).then(response => {
        if(response.data.success){
          return this.rolesGroupByTag = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar permissões' })
      });
      this.loadingRole = false;
    },
    async _store(){
      for (const field in this.errors) {
        if(!this.user[field])
          return this.errors[field] = true;
      }

      this.loading = true;

      this.$refs.fireDialog.loading({ title: this.idByRoute ? 'Atualizando...' : 'Salvando...' })

      const response = !this.idByRoute ? await axios.post('/api/user', this.user) : await axios.put(`/api/user/${this.idByRoute}`, this.user);

      this.loading = false;

      if(response.data.success){
        this.$refs.fireDialog.success({ title: 'Usuário salvo com sucesso' })
        return setTimeout(() => this.$router.push({ name: 'user.index' }), 1500);
      }

      this.$refs.fireDialog.error({ title: 'Error aos salvar usuário' })
    },
    selectAllTag(tag) {
      let group = this.rolesGroupByTag.filter(group => group.tag === tag);

      let roles = group[0].roles.map(role => role.id);

      roles.forEach(role => {
        let exists = this.user.roles.filter(userRole => userRole == role)

        if(exists.length === 0){
          this.user.roles.push(role);
        }
      });
    }
  }
}
</script>
