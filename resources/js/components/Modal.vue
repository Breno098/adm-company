<template>
  <v-dialog v-model="show" max-width="350">
    <v-card>
        <v-card-title>
            <div class="mx-auto">
              {{ title }}
            </div>
        </v-card-title>

        <v-card-text class="text-center py-5" >
          <v-progress-circular
            v-if="loading"
            :width="7"
            color="primary"
            :size="70"
            indeterminate
          ></v-progress-circular>

          <v-icon :size="70" color="btnPrimary" v-if="status === 'success'"> mdi-check</v-icon>
          <v-icon :size="70" color="btnDanger" v-if="status === 'error'"> mdi-alert-decagram</v-icon>
        </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'Modal',

  props: {
    show: false,
    title: '',
    loading: false,
    status: null
  },
}
</script>
