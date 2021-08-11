<template>
  <v-app>
      <bars v-if="user"/>
      <v-main class="mx-5 my-5">
          <child />
      </v-main>
  </v-app>
</template>

<script>
import Bars from '~/components/Bars'
import { mapGetters } from 'vuex'

export default {
  name: 'MainLayout',
  data: () => ({
        drawer: true,
  }),
  methods: {
    async logout () {
      await this.$store.dispatch('auth/logout')
      this.$router.push({ name: 'login' })
    }
  },
  components: {
    Bars
  },
  computed: mapGetters({
    user: 'auth/user'
  }),
}
</script>

<style scoped>
  .v-application ::-webkit-scrollbar {
    height: 8px;
    width: 13px;
  }

  .v-application ::-webkit-scrollbar-corner {
    background: transparent;
  }

  .v-application ::-webkit-scrollbar-thumb {
    background: #2196F3;
    border-radius: 8px;
  }
</style>
