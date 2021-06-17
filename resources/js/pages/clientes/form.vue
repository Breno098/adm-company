<template>
  <div>
    <v-row class="justify-center mx-5 my-5">
        <v-col cols="12">
            <v-card class="transparent" elevation="0">
                <v-text-field
                  label="Procurar"
                  color="blue"
                  outlined
                  dense
                ></v-text-field>

                {{ clientId }}
            </v-card>
        </v-col>
    </v-row>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  // middleware: 'auth',

  metaInfo () {
    return { title: 'Cliente' }
  },
  data: () => ({
    loading: false
  }),
  computed: {
    clientId(){
      return this.$route.params.id ?? null;
    }
  },
  mounted(){
    this._load();
  },
  methods: {
    async _load(){
      await axios.get(`api/client/1`).then(response => {
        console.log(response.data);
      });

      // // this.loading = true;

      // await axios.get(`api/client/1`).then(response => {
      //   console.log(response.data);
      //     // if(response.data.success){
      //     //     this.table.clients = response.data.data;
      //     //     this.table.loading = false;
      //     // }
      // });
    },
  }

}
</script>

<style>

</style>
