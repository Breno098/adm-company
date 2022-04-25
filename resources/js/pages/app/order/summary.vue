<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <p class="font-weight-bold mb-5 text-h5">
      <v-icon color="primary">mdi-format-list-checks</v-icon>
      {{ titlePage }}
    </p>

    <v-row class="mb-2">
      <v-col cols="6" md="9">
        <v-btn
          color="btn-primary"
          small
          text
          @click="$router.go(-1)"
        >
          Voltar <v-icon>mdi-chevron-double-left</v-icon>
        </v-btn>
      </v-col>

      <v-col cols="4" md="2">
        <v-btn
          color="green"
          class="rounded-lg"
          block
          small
          dark
          @click="_edit"
          v-if="canSave"
          :loading="loading"
        >
          Editar <v-icon small class="ml-2">mdi-pencil</v-icon>
        </v-btn>
      </v-col>

      <v-col cols="2" md="1">
        <v-btn
          color="btn-delete"
          class="rounded-lg"
          block
          small
          dark
          @click="showModalStatus"
          v-if="canSave"
          :loading="loading"
        >
          <v-icon small>mdi-delete</v-icon>
        </v-btn>
      </v-col>
    </v-row>

    <v-row class="mb-2">
      <v-col cols="12" md="6">
        <v-card elevation="0" color="grey lighten-3" height="80" class="d-flex justify-start align-center">
          <v-card-text>
            <v-icon large color="primary">mdi-account</v-icon>
            <b>CLIENTE:</b> {{ order.client ? order.client.name : '' }}
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" md="3">
        <v-card elevation="0" color="grey lighten-3" height="80">
          <v-card-text class="text-center">
            Situação do pedido
          </v-card-text>

          <v-chip
            v-if="order.status"
            class="d-flex justify-center"
            label
            :color="order.status | statusColor"
            style="width: 100%;"
            small
          >
            {{ order.status }}
            <v-icon class="ml-2">{{ order.status | statusIcon }}</v-icon>
          </v-chip>
        </v-card>
      </v-col>
      <v-col cols="12" md="3">
        <v-card elevation="0" color="grey lighten-3" height="80">
          <v-card-text class="text-center">
            Status Finaceiro
          </v-card-text>

          <v-chip
            v-if="order.payment_status"
            class="d-flex justify-center"
            label
            :color="order.payment_status | paymentStatusColor"
            style="width: 100%;"
            small
          >
            {{ order.payment_status }}
            <v-icon class="ml-2">{{ order.payment_status | paymentStatusIcon }}</v-icon>
          </v-chip>
        </v-card>
      </v-col>
    </v-row>

    <v-card>
      <v-card-text>
        <v-row>

          <v-col cols="12">
            <v-icon small color="primary">mdi-map-marker-radius</v-icon>
            <b>ENDEREÇO:</b>
            {{ order.address && order.address.street ? order.address.street : '' }}
            {{ order.address && order.address.number ? `n° ${order.address.number}` : '' }},
            {{ order.address && order.address.district }}
            {{ order.address && order.address.city ? `- ${order.address.city}` : '' }}
          </v-col>

          <v-col cols="12">
            <v-icon small color="primary">mdi-calendar</v-icon>
            <b>DATA VISITA TÉCNICA:</b> {{ order.technical_visit_date | formatDMY }} {{ order.technical_visit_time }}
          </v-col>

          <v-col cols="12">
            <v-icon small color="btn-delete">mdi-comment-alert-outline</v-icon>
            <b>PROBLEMA RECLAMADO:</b> {{ order.complaint }}
          </v-col>

          <v-col cols="12">
            <v-icon small color="green">mdi-comment-check-outline</v-icon>
            <b>SERVIÇO REALIZADO:</b> {{ order.work_done }}
          </v-col>

          <v-col cols="12">
            <v-icon small color="primary">mdi-account-outline</v-icon>
            <b>RESPONSÁVEL TÉCNICO:</b> {{ order.technician_id ? order.technician.name : '' }}
          </v-col>
        </v-row>
      </v-card-text>
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
    panels: [0],
    modalAddress: false,
    modalStatus: false,
    loading: false,
    loadingClients: false,
    loadingProducts: false,
    loadingServices: false,
    loadingAddresses: false,
    menu_technical_visit_date: false,
    menu_time: false,
    menu_date_payments: [],
    menu_date_installments_pay_day: [],
    menu_date_installments_due_date: [],
    errors: {
      client_id: false,
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    order: {
      id: null,
      description : null,
      comments: null,
      amount : null,
      amount_paid: null,
      technical_visit_date: null,
      technical_visit_time: '',
      discount_amount: 0,
      discount_percentage: 0,
      warranty_days : null,
      warranty_conditions: null,
      installments: [],
      type_payment: '',
      number_of_installments: null,
      products: [],
      services: [],
      client_id: null,
      status: 'AGUARDANDO APROVAÇÃO',
      address_id: null,
      technician_id: null,
      accepted_payment_methods: '',
      address: {
        number: null,
        district: null,
        city: null,
        state: null,
        street: null,
        cep: null,
        complement: null,
        apartment: null,
        floor: null,
        description: null,
        main: null,
        block: null,
        house: null,
        tower: null,
      }
    },
    clients: [],
    products: [],
    services: [],
    addresses: [],
    accepted_payment_methods: [],
  }),
  computed: {
    titlePage(){
      return this.$route.params.id ? `Editar Pedido | nº ${this.$route.params.id}` : 'Adicionar Pedido';
    },
    canSave(){
      return this.$can('order_add') && !this.idByRoute || this.$can('order_update') && this.idByRoute;
    },
    idByRoute(){
      return this.$route.params.id;
    },
    valueTotal(){
      let valueTotal = 0;
      this.order.products.forEach(product => valueTotal += product.quantity && product.value ? product.value * product.quantity : 0);
      this.order.services.forEach(service => valueTotal += service.quantity && service.value ? service.value * service.quantity : 0);
      return valueTotal.toFixed(2);
    },
    valueTotalWithDiscont(){
      let valueTotalWithDiscont = this.valueTotal - this.order.discount_amount;
      return valueTotalWithDiscont.toFixed(2);
    },
    paymentMethods: {
      get: function() {
        return this.order.accepted_payment_methods ? this.order.accepted_payment_methods.split(',') : [];
      },
      set: function(value) {
        this.order.accepted_payment_methods = value.join(',');
      }
    },
  },
  filters: {
    addressStringParteOne(address) {
      let stringReturn = '';

      stringReturn += address.street ? `R. ${address.street} ` : '';
      stringReturn += address.number ? ` n° ${address.number} ` : '';
      stringReturn += address.district ? address.district : '';
      stringReturn += address.cep ? ` - ${address.cep}` : '';
      stringReturn += address.city ? ` | ${address.city}` : '';
      stringReturn += address.state ? ` - ${address.state}` : '';

      return stringReturn ? stringReturn : 'Escolha uma localização.';
    },
    addressStringParteTwo(address) {
      let stringReturn = '';

      stringReturn += address.block ? ` BLOCO ${address.block} ` : '';
      stringReturn += address.tower ? ` TORRE ${address.tower} ` : '';
      stringReturn += address.floor ? ` ANDAR ${address.floor} ` : '';
      stringReturn += address.apartment ? ` AP.: ${address.apartment} ` : '';
      stringReturn += address.house ? ` CASA: ${address.house} ` : '';

      return stringReturn;
    },
    addressStringParteThree(address) {
      return address.complement;
    },
  },
  mounted(){
    this._start();
  },
  methods: {
    async _start(){
      if(this.idByRoute){
        await this._load();
      }

      await this._loadClients();
    },
    async _loadClients(){
      this.loadingClients = true;

      await axios
              .get(`/api/client`)
              .then(response => {
                if(response.data.success){
                  this.clients = response.data.data;
                } else {
                  this.$refs.fireDialog.error({ title: 'Error ao carregar clientes' })
                }
              })
              .catch(error => {
                this.$refs.fireDialog.error({ title: 'Error ao carregar clientes' })
              })
              .finally(() => {
                this.loadingClients = false;
              });
    },
    async _loadAddresses(){
      this.loadingAddresses = true;

      let params = { client_id: this.order.client_id };

      await axios
              .get(`/api/address`, { params })
              .then(response => {
                if(response.data.success){
                  this.addresses = response.data.data;
                } else {
                  this.$refs.fireDialog.error({ title: 'Error ao carregar endereços' })
                }
              })
              .catch(error => {
                this.$refs.fireDialog.error({ title: 'Error ao carregar endereços' })
              })
              .finally(() => {
                this.loadingAddresses = false;
              });
    },
    async _load(){
      this.loading = true;

      let id = this.$route.params.id ? this.$route.params.id : this.order.id ? this.order.id : null;

      await axios
              .get(`/api/order/${id}`)
              .then(response => {
                if(response.data.success){
                  this.order = response.data.data;

                  if(!response.data.data.address) {
                    this.order.address = {};
                  }
                } else {
                  this.$refs.fireDialog.error({ title: 'Error ao carregar dados da ordem', time: 1500 })
                }
              })
              .catch(error => {
                this.$refs.fireDialog.error({ title: 'Error ao carregar dados da ordem', time: 1500 })
              })
              .finally(() => {
                this.loading = false;
              });
    },
    showModalStatus() {
      /* Validations */
      for (const field in this.errors) {
        if(!this.order[field]){
          this.errors[field] = true;
          window.scrollTo(0, 0);
          return false;
        }
      }

      this.modalStatus = true;
    },
    async _store(){
      this.loading = true;

      let id = this.order.id;

      this.$refs.fireDialog.loading({ title: id ? 'Atualizando...' : 'Salvando...' })

      this.order.amount = this.valueTotalWithDiscont;

      try {
        const response = !id ? await axios.post('/api/order', this.order) : await axios.put(`/api/order/${id}`, this.order);

        this.$refs.fireDialog.success({ title: 'Salvo com sucesso' });
        this.$refs.fireDialog.hide(1500);
      } catch (error) {
        this.$refs.fireDialog.error({ title: 'Erro ao salvar' });
      } finally {
        this.loading = false;
      }
    },
    async searchCep() {
      let cep = this.order.address.cep.replace('-', '');

      if(cep.length != 8)
        return;

      this.$refs.fireDialog.loading({ title: 'Buscando endereço' })

      let params = { cep };
      await axios.get(`/api/address/searchCep`, { params }).then(response => {
        if(response.data.data.erro){
          return his.$refs.fireDialog.error({ title: 'Endereço não encontrado. Verifique o CEP por favor.' })
        }

        let { logradouro, bairro, localidade, uf } = response.data.data;

        this.order.address.street = logradouro.toUpperCase();
        this.order.address.district = bairro.toUpperCase();
        this.order.address.city = localidade.toUpperCase();
        this.order.address.state = uf.toUpperCase();

        this.$refs.fireDialog.hide();
      }).catch(error => {
        this.$refs.fireDialog.error({ title: 'Error ao carregar endereço' })
      })
    },
    _edit(){
      this.$can('order_show')
        ? this.$router.push({
            name: 'order.show',
            params: { id: this.idByRoute }
        }) : null
    },
  }

}
</script>
