<template>
  <v-app>
      <bars v-if="user"/>
      <v-main>
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
