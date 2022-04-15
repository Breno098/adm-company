<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <p class="font-weight-bold mb-5 text-h5">
      <v-icon color="primary">mdi-account</v-icon>
      {{ titlePage }}
    </p>

    <v-row class="mb-2">
      <v-col cols="6" md="2">
        <v-btn
          color="btn-primary"
          small
          text
          @click="$router.go(-1)"
          block
        >
          Voltar <v-icon>mdi-chevron-double-left</v-icon>
        </v-btn>
      </v-col>
    </v-row>

    <v-card>
      <v-card-text>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: this.titlePage }
  },
  data: () => ({
    titlePage: 'Relatório do cliente Breno',
    loading: false,
    report: {
      monthly: [],
      annually: {}
    },
    filter: {
      year: moment().year()
    },
  }),
  computed: {
  },
  mounted() {
    // this._loadReport();
  },
  methods: {
    addYear() {
      this.filter.year = this.filter.year + 1;
      this._loadReport();
    },
    subYear() {
      this.filter.year = this.filter.year - 1;
      this._loadReport();
    },
    async _loadReport(){
      let params = { year: this.filter.year };

      this.loading = true;

      await axios.get('/api/report/finance/by-year', { params }).then(response => {
        this.report.monthly = response.data.data.monthly;
        this.report.annually = response.data.data.annually;
      }).catch(error => {
        this.$refs.fireDialog.error({ title: 'Erro ao carregar relatório' });
      })

      this.loading = false;
    },
  }
}
</script>
