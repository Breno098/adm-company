<template>
   <v-container fluid fill-height style="background: #7986CB; background: linear-gradient(to bottom, #2196F3, #90CAF9, #E3F2FD);">
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-layout flex align-center justify-center>
      <v-flex xs12 sm8 elevation-6>

        <v-stepper v-model="stepper" vertical>
          <v-stepper-step :complete="stepper > 1" step="1">
            Inicio
            <small>Seu primeiro acesso</small>
          </v-stepper-step>

          <v-stepper-content step="1">
            <v-card class="mb-5" elevation="0">
              <v-card-text class="text-center">
                Como é seu primeiro acesso, precisamos configurar alguns dados do seu usuário. É rapidinho!
              </v-card-text>
            </v-card>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="primary" @click="stepper = 2">
                Vamos lá!
              </v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-stepper-content>

          <v-stepper-step :complete="stepper > 2" step="2">
            Nome
            <small>Como podemos te chamar?</small>
          </v-stepper-step>

          <v-stepper-content step="2">
            <v-card class="mb-5" elevation="0">
              <v-card-text class="text-center">
                <span> Como podemos te chamar? </span>

                <v-text-field
                  class="mt-5"
                  outlined
                  dense
                  v-model="form.name"
                  :rules="[rules.required]"
                  :error="errors.name"
                ></v-text-field>

                <small> 
                  Esse nome não é definitivo, depois que acabarmos de congurar seu usuário, você poderá
                  alterar quantas vezes for necessário. 
                </small>
              </v-card-text>
            </v-card>
           

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn @click="stepper = 1" text>
                Voltar
              </v-btn>
              <v-btn color="primary" @click="stepper = 3">
                Continuar
              </v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-stepper-content>

           <v-stepper-step :complete="stepper > 3" step="3">
            Senhas
            <small>Alteração de senhas.</small>
          </v-stepper-step>

          <v-stepper-content step="3">
            <v-card class="mb-5" elevation="0">
               <v-card-text class="text-center">
                <span> 
                  Por questões de segurança, pedimos que logo no primeiro acesso, o usuário troque a senha. <br/>
                  A senha deve ter no minimo 6 caracteres. <br/> 
                </span>

                <v-text-field
                  class="mt-5"
                  :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="showPassword ? 'text' : 'password'"
                  @click:append="showPassword = !showPassword"
                  label="NOVA SENHA"
                  outlined
                  dense
                  v-model="form.password"
                  :rules="[rules.required]"
                  :error="errors.password"
                ></v-text-field>

                <v-text-field
                  :append-icon="showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="showConfirmPassword ? 'text' : 'password'"
                  @click:append="showConfirmPassword = !showConfirmPassword"
                  label="CONFIRME A SENHA"
                  outlined
                  dense
                  v-model="form.password_confirmation"
                  :rules="[rules.required]"
                  :error="errors.password_confirmation"
                ></v-text-field>

                <small> 
                  Não compartilhe a senha com outros usuários. <br/>
                  Todas as operações no sistemas estão sendo gravadas, como adição, atualização e 
                  exclusão de dados. Entre apenas com seu usuário e senha, facilitando assim a organização
                  das informações do sistema. :)
                </small>
              </v-card-text>
            </v-card>
            <v-btn @click="stepper = 2" text>
              Voltar
            </v-btn>
            <v-btn color="primary" @click="_store">
              Continuar
            </v-btn>
          </v-stepper-content>
        </v-stepper>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import { mapGetters } from 'vuex';
import axios from 'axios';

export default {
  middleware: 'auth',
  layout: 'auth',
  metaInfo () {
    return { title: this.titlePage }
  },
  data: () => ({
    stepper: 1,
    showPassword: false,
    showConfirmPassword: false,
    loading: false,
    errors: {
      name: false,
      password: false,
      password_confirmation: false
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    form:{
      name: '',
      password: '',
      password_confirmation: ''
    },
  }),
  computed: {
    titlePage(){
      return 'Senhas';
    },
    ...mapGetters({
      user: 'auth/user'
    })
  },
  mounted(){
    this.form.name = this.user.name;
  },
  methods: {
    async _store(){
      for (const field in this.errors) {
        if(!this.form[field])
          return this.errors[field] = true;
      }

      if(this.form.password !== this.form.password_confirmation){
        this.$refs.fireDialog.error({ 
          title: 'Senhas incorretas',
          message: 'Verifique se as senhas estão corretas' 
        })
        return;
      }

      if(this.form.password.split('').length < 6){
        this.$refs.fireDialog.error({ 
          title: 'Quantidade de caracteres',
          message: 'A senha deve conter no minimo 6 caracteres' 
        })
        return;
      }

      const ok = await this.$refs.fireDialog.confirm({
          title: 'Confirmar dados',
          message: 'Está tudo certo? Podemos finalizar?',
          textConfirmButton: 'Sim',
          colorConfirButton: 'blue',
          textCancelButton: 'Cancelar',
          colorCancelButton: 'red lighten-2'
      })

      if(!ok){
        return;
      }

      this.loading = true;

      this.$refs.fireDialog.loading({ 
        title: 'Finalizando',
        message: 'Estamos finalizando suas configurações.' 
      })

      await axios
              .patch('/api/profile/first-access', this.form)
              .then(async response => {
                await this.$store.dispatch('auth/fetchUser');

                this.$refs.fireDialog.success({ 
                  title: 'Tudo certo!',
                });

                setTimeout(() => this.$router.push({ name: 'home' }), 1000)
              })
              .catch(error => {
                this.$refs.fireDialog.error({ 
                  title: 'Ops',
                  message: 'Algo errado aconteceu. Tente novamente ou informe o administrador do aplicativo.'
                })
              })

        this.loading = false;
    },
  }

}
</script>
