<template>
  <div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title"> Clientes </h5>
        <p class="card-text">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">Nome</th>
                <th scope="col"> </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="client in table.clients" :key="client.id">
                <td> {{ client.name }} </td>
                <td>
                  <button class="btn btn-success">Editar <fa icon="edit"/></button>
                  <button class="btn btn-danger">Deleter <fa icon="trash"/></button>
                </td>
              </tr>
            </tbody>
          </table>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data: () => ({
    table: {
      clients: [],
      loading: false
    }
  }),
  mounted() {
    this._load();
  },
  methods: {
    async _load(){
      this.table.loading = true;

        await axios.get('api/client').then(response => {
          console.log(response.data);
            if(response.data.success){
                this.table.clients = response.data.data;
                this.table.loading = false;
            }
        });
      },
  }
}
</script>

<style>

</style>
