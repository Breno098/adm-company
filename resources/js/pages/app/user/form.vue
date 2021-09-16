<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-row>
      <v-col cols="12">
        <v-card elevation="0">
          <v-toolbar elevation="0" class="mb-2">
            <v-toolbar-title> {{ titlePage }} </v-toolbar-title>
            <v-progress-linear
              color="blue"
              indeterminate
              height="4"
              bottom
              absolute
              :active="loading"
            ></v-progress-linear>

            <v-spacer></v-spacer>

            <v-btn
              v-if="(!idByRoute && $role.user.add()) || (idByRoute && $role.user.update()) "  
              color="green" 
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
                color="blue"
                outlined
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
                color="blue"
                outlined
                dense
                v-model="user.email"
                :loading="loading"
                :rules="[rules.required]"
                :error="errors.email"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6" v-if="$role.user.roles()">
              <v-btn color="blue" @click="_groupUser('admin')" rounded dark block>
                Admin
              </v-btn>
            </v-col>
            <v-col cols="12" md="6" v-if="$role.user.roles()">
              <v-btn color="green" @click="_groupUser('i')" rounded dark block>
                Visualizar
              </v-btn>
            </v-col>
            <v-col cols="12" md="6" v-if="$role.user.roles()">
              <v-btn color="green" @click="_groupUser('i|a')" rounded dark block>
                Visualizar | Adicionar 
              </v-btn>
            </v-col>
            <v-col cols="12" md="6" v-if="$role.user.roles()">
              <v-btn color="green" @click="_groupUser('i|a|u')" rounded dark block>
                Visualizar | Adicionar | Atualizar
              </v-btn>
            </v-col>
              
            <v-col cols="12" v-if="$role.user.roles()">
              <v-card elevation="2">
                <v-card-title>
                  Permissões de sistemas
                </v-card-title>

                <v-card-text>
                  <v-row>
                    <v-col cols="12" md="4" v-for="(role, index) in roles" :key="index">
                      <v-switch
                        inset
                        :label="role.name"
                        v-model="user.roles_format"
                        :value="role.id"
                      ></v-switch>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn 
              v-if="(!idByRoute && $role.client.add()) || (idByRoute && $role.client.update()) "  
              color="green" 
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

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: this.titlePage }
  },
  data: () => ({
    loading: false,
    loadingRole: false,
    errors: {
      name: false,
      email: false
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    user: {
      name: null,
      email: null,
      roles: [],
      roles_format: [],
    },
    roles: []
  }),
  computed: {
    titlePage(){
      return this.$route.params.id ? 'Usuário' : 'Adicionar Usuário';
    },
    idByRoute(){
      return this.$route.params.id;
    }
  },
  mounted(){
    this._start();
  },
  methods: {
   async _start(){
      if(this.idByRoute){
        await this._load();
      }
      await this._loadRoles();
    },
    _groupUser(type){
      let rolesGroup = [];

      switch (type) {
        case 'admin':
          this.roles.forEach(role => rolesGroup.push(role.id));
          break;

        case 'i':
          this.roles
              .filter(role => role.role.includes('index') || role.role.includes('show') )
              .forEach(role => rolesGroup.push(role.id));
          break;

        case 'i|a':
          this.roles
              .filter(role => role.role.includes('index') || role.role.includes('show') || role.role.includes('add') )
              .forEach(role => rolesGroup.push(role.id));
          break;

        case 'i|a|u':
          this.roles
              .filter(role => role.role.includes('index') || role.role.includes('show') || role.role.includes('add') || role.role.includes('update') )
              .forEach(role => rolesGroup.push(role.id));
          break;
        
        case 'i|a|u|d':
          this.roles
              .filter(role => role.role.includes('index') || role.role.includes('show') || role.role.includes('add') || role.role.includes('update') || role.role.includes('delete'))
              .forEach(role => rolesGroup.push(role.id));
          break;

        default: null
      }

      this.user.roles_format = rolesGroup;
    },
    async _load(){
      this.loading = true;
      await axios.get(`api/user/${this.idByRoute}`).then(response => {
        if(response.data.success){
          this.user = { 
            roles_format: [],
            ...response.data.data
          };

          response.data.data.roles.forEach(role => {
            this.user.roles_format.push(role.id);
          });
          return;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar dados do usuário' })
        this.$refs.fireDialog.hide(1500);
      });
      this.loading = false;
    },
    async _loadRoles(){
      this.loadingRole = true;
      await axios.get(`api/role`).then(response => {
        if(response.data.success){
          return this.roles = response.data.data;
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

      this.user.roles = this.user.roles_format;

      const response = !this.idByRoute ? await axios.post('api/user', this.user) : await axios.put(`api/user/${this.idByRoute}`, this.user);

      this.user.roles_format = this.user.roles;

      this.loading = false;

      if(response.data.success){
        this.$refs.fireDialog.success({ title: 'Usuário salvo com sucesso' })
        return setTimeout(() => this.$router.push({ name: 'user.index' }), 1500);
      }

      this.$refs.fireDialog.error({ title: 'Error aos salvar usuário' })
    },
    
  }

}
</script>
