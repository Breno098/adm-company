<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-info">
    <div class="container-fluid">
      <router-link :to="{ name: user ? 'home' : 'welcome' }" class="navbar-brand mx-4">
        {{ appName }}
      </router-link>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false">
        <span class="navbar-toggler-icon" />
      </button>

      <div id="navbarToggler" class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <!-- <li class="nav-item">
            <a class="nav-link" href="#">Cadastros</a>
          </li> -->
          <li class="nav-item dropdown">
            <div class="navlink dropdown-toggle text-dark" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Cadastros
            </div>

            <div class="dropdown-menu">
              <router-link :to="{ name: 'client.index' }" class="dropdown-item pl-3">
                <fa icon="user-friends" fixed-width /> Clientes
              </router-link>
            </div>
          </li>
        </ul>

        <ul class="navbar-nav ml-auto">
          <!-- Authenticated -->
          <li v-if="user" class="nav-item dropdown mr-5">
            <a class="nav-link dropdown-toggle text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img :src="user.photo_url" class="rounded-circle profile-photo mr-1">
              {{ user.name }}
            </a>

            <div class="dropdown-menu">
              <router-link :to="{ name: 'settings.profile' }" class="dropdown-item pl-3">
                <fa icon="cog" fixed-width /> Configurações
              </router-link>

              <div class="dropdown-divider" />

              <a href="#" class="dropdown-item pl-3" @click.prevent="logout">
                <fa icon="sign-out-alt" fixed-width /> Sair
              </a>
            </div>
          </li>

          <!-- Guest -->
          <template v-else>
            <li class="nav-item">
              <router-link :to="{ name: 'login' }" class="nav-link" active-class="active">
                LOGIN
              </router-link>
            </li>
            <!-- <li class="nav-item">
              <router-link :to="{ name: 'register' }" class="nav-link" active-class="active">
                REGISTRAR-SE
              </router-link>
            </li> -->
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data: () => ({
    appName: window.config.appName
  }),
  computed: mapGetters({
    user: 'auth/user'
  }),
  methods: {
    async logout () {
      await this.$store.dispatch('auth/logout')
      this.$router.push({ name: 'login' })
    }
  }
}
</script>

<style scoped>
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -.375rem 0;
}
</style>
