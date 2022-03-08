<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <v-card class="mb-4">
      <v-toolbar elevation="0">
        <v-toolbar-title> {{ titlePage }} </v-toolbar-title>
        <v-progress-linear
          indeterminate
          height="4"
          bottom
          absolute
          :active="loading"
        ></v-progress-linear>

        <v-spacer></v-spacer>

        <v-btn
          color="btnPrimary"
          @click="_store(true)"
          :loading="loading"
          rounded
          small
        >
          Salvar <v-icon class="ml-2">mdi-content-save</v-icon>
        </v-btn>
      </v-toolbar>
    </v-card>

    <v-card class="my-3">
      <v-card-text class="d-flex flex-row justify-center">
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="grey"
              @click="budgetDownload"
              :loading="loading"
              v-bind="attrs"
              v-on="on"
              class="mx-3"
              rounded
              dark
              small
            >
              <v-icon>mdi-file-document</v-icon>
            </v-btn>
          </template>
          <span>Gerar Orçamento</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="grey lighten-1"
              @click="serviceOrderDownload"
              :loading="loading"
              v-bind="attrs"
              v-on="on"
              class="mx-3"
              rounded
              small
            >
              <v-icon>mdi-file-export</v-icon>
            </v-btn>
          </template>
          <span>Gerar Ordem de Serviço</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="green lighten-2"
              @click="receiptDownload"
              :loading="loading"
              v-bind="attrs"
              v-on="on"
              class="mx-3"
              rounded
              dark
              small
            >
              <v-icon>mdi-file-check</v-icon>
            </v-btn>
          </template>
          <span>Gerar Recibo</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="orange"
              @click="_generateAppointment"
              :loading="loading"
              v-bind="attrs"
              v-on="on"
              class="mx-3"
              rounded
              small
            >
              <v-icon dark>mdi-calendar-today</v-icon>
            </v-btn>
          </template>
          <span>Agendar Compromisso</span>
        </v-tooltip>
      </v-card-text>
    </v-card>

    <v-card>
      <v-tabs v-model="tab">
        <v-tabs-slider color="primary"></v-tabs-slider>
        <v-tab>Informações <v-icon class="ml-2">mdi-information</v-icon></v-tab>
        <v-tab>Produtos e Serviços <v-icon class="ml-2">mdi-wrench</v-icon></v-tab>
        <v-tab>Pagamento <v-icon class="ml-2">mdi-cash</v-icon></v-tab>
        <v-tab>Financeiro <v-icon class="ml-2">mdi-cash</v-icon></v-tab>
        <v-tab>Garantia <v-icon class="ml-2">mdi-format-align-center</v-icon></v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab" class="pt-5 px-3">
        <!-- Informações -->
        <v-tab-item>
          <v-row>
            <v-col cols="12" md="6">
              <v-autocomplete
                v-model="order.client_id"
                :items="clients"
                item-text="name"
                item-value="id"
                label="CLIENTE"
                v-on:change="_loadAddresses"
                :loading="loadingClients"
                outlined
                dense
                :rules="[rules.required]"
                :error="errors.client_id"
              ></v-autocomplete>
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                v-model="order.status"
                :items="statuses"
                label="STATUS"
                outlined
                dense
              ></v-select>
            </v-col>

            <v-col cols="12">
              <v-autocomplete
                v-model="order.address_id"
                :items="addresses"
                item-value="id"
                label="ENDEREÇO"
                :loading="loadingAddresses"
                outlined
                dense
                no-data-text="Nenhum endereço cadastrado para o cliente selecionado"
              >
                <template slot="selection" slot-scope="data">
                  {{ data.item.street }} {{ data.item.number ? `n° ${data.item.number}` : '' }}, {{ data.item.district }} - {{ data.item.city }}
                </template>
                <template slot="item" slot-scope="data">
                  {{ data.item.street }} {{ data.item.number ? `n° ${data.item.number}` : '' }}, {{ data.item.district }} - {{ data.item.city }}
                </template>
              </v-autocomplete>
            </v-col>

            <v-col cols="12" md="6">
              <v-dialog
                ref="dialog"
                v-model="menu_technical_visit_date"
                :return-value.sync="order.technical_visit_date"
                persistent
                width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    :value="technicalVisitDateFormat"
                    label="DATA VISITA TÉCNICA"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    outlined
                    dense
                    clearable
                    @click:clear="order.technical_visit_date = null"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="order.technical_visit_date"
                  scrollable
                  no-title
                  locale="pt-Br"
                >
                  <v-spacer></v-spacer>
                  <v-btn text @click="menu_technical_visit_date = false">
                    Cancelar
                  </v-btn>
                  <v-btn text @click="$refs.dialog.save(order.technical_visit_date)">
                    OK
                  </v-btn>
                </v-date-picker>
              </v-dialog>
            </v-col>

            <v-col cols="12" md="6">
              <v-menu
                ref="menu_time"
                v-model="menu_time"
                :close-on-content-click="false"
                :nudge-right="40"
                :return-value.sync="order.technical_visit_time"
                transition="scale-transition"
                offset-y
                max-width="290px"
                min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="order.technical_visit_time"
                    label="HORÁRIO VISITA TÉCNICA"
                    prepend-icon="mdi-clock-time-four-outline"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    dense
                    outlined
                  ></v-text-field>
                </template>
                <v-time-picker
                  v-if="menu_time"
                  v-model="order.technical_visit_time"
                  @click:minute="$refs.menu_time.save(order.technical_visit_time)"
                  format="24hr"
                ></v-time-picker>
              </v-menu>
            </v-col>

            <v-col cols="12">
              <v-textarea
                label="PROBLEMA RECLAMADO"
                outlined
                dense
                v-model="order.complaint"
                :loading="loading"
                hint="ORDEM DE SERVIÇO"
                @input="order.complaint = order.complaint.toUpperCase()"
              ></v-textarea>
            </v-col>

            <v-col cols="12">
              <v-textarea
                label="COMENTARIOS INTERNOS"
                outlined
                dense
                v-model="order.comments"
                :loading="loading"
                hint="COMENTÁRIOS E OBSERVAÇÕES INTERNAS"
                @input="order.comments = order.comments.toUpperCase()"
              ></v-textarea>
            </v-col>

            <v-col cols="12">
              <v-textarea
                label="SERVIÇO NECESSÁRIO"
                outlined
                dense
                v-model="order.work_found"
                :loading="loading"
                hint="SERVIÇO NECESSÁRIO A SER FEITO"
                @input="order.work_found = order.work_found.toUpperCase()"
              ></v-textarea>
            </v-col>

            <v-col cols="12">
              <v-textarea
                label="SERVIÇO REALIZADO"
                outlined
                dense
                v-model="order.work_done"
                :loading="loading"
                hint="SERVIÇO REALIZADO"
                @input="order.work_done = order.work_done.toUpperCase()"
              ></v-textarea>
            </v-col>
          </v-row>
        </v-tab-item>

        <!-- Produtos/Serviços -->
        <v-tab-item>
          <v-row>
            <v-col cols="12">
              <div class="text-h6 blue--text"> Produtos </div>
              <v-divider color="grey"/>
            </v-col>

            <v-col cols="12" v-for="(product, index) in order.products" :key="product.id">
              <v-row>
                <v-col cols="12" class="d-flex flex-row justify-end">
                  <v-btn color="btnDanger" @click="order.products.splice(index, 1);" :loading="loading" small rounded>
                    <v-icon color="red darken-4">mdi-delete</v-icon>
                  </v-btn>
                </v-col>

                <v-col cols="12" md="4">
                  <v-autocomplete
                    v-model="product.id"
                    :items="products"
                    item-value="id"
                    item-text="name"
                    label="PRODUTO"
                    outlined
                    dense
                    :loading="loadingProducts"
                    no-data-text="Nenhum produto encontrado"
                    v-on:change="_addDefaultValueProduct(index)"
                  >
                  </v-autocomplete>
                </v-col>

                <v-col cols="12" md="2">
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        prefix="R$"
                        label="PREÇO PADRÃO"
                        outlined
                        dense
                        :value="product.default_value"
                        :loading="loading"
                        readonly
                        color="black"
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <span> Preço cadastrado no produto </span>
                  </v-tooltip>
                </v-col>

                <v-col cols="12" md="2">
                  <v-text-field
                    label="QUANTIDADE"
                    outlined
                    type="number"
                    dense
                    v-model="product.quantity"
                    :loading="loading"
                    v-on:click="_sumTotalValueProduct(index)"
                    v-on:keyup="_sumTotalValueProduct(index)"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="2">
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        prefix="R$"
                        type="number"
                        label="VALOR"
                        outlined
                        dense
                        v-model="product.value"
                        :loading="loading"
                        v-on:click="_sumTotalValueProduct(index)"
                        v-on:keyup="_sumTotalValueProduct(index)"
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <span> Se necessário, use ponto (.) ao invés de virgula (,) </span>
                  </v-tooltip>
                </v-col>

                <v-col cols="12" md="2">
                  <v-text-field
                    prefix="R$"
                    label="VALOR TOTAL"
                    readonly
                    outlined
                    dense
                    :value="product.total_value.toFixed(2)"
                    :loading="loading"
                    color="black"
                  ></v-text-field>
                </v-col>
              </v-row>

              <v-divider color="grey" class="mx-5" v-if="(index + 1) < order.products.length"/>
            </v-col>

            <v-col cols="12" class="d-flex flex-row justify-end">
              <v-btn
                color="btnPrimary"
                @click="order.products.push({
                  quantity: 1,
                  value: 0,
                  default_value: 0,
                  total_value: 0
                })"
                :loading="loading"
                small
                rounded
              >
                Adicionar produto <v-icon>mdi-plus</v-icon>
              </v-btn>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12">
              <div class="text-h6 blue--text"> Serviços </div>
              <v-divider color="grey"/>
            </v-col>

            <v-col cols="12" v-for="(service, index) in order.services" :key="service.id">
              <v-row>
                <v-col cols="12" class="d-flex flex-row justify-end">
                  <v-btn color="btnDanger" @click="order.services.splice(index, 1);" :loading="loading" small rounded>
                    <v-icon color="red darken-4">mdi-delete</v-icon>
                  </v-btn>
                </v-col>

                <v-col cols="12" md="4">
                  <v-autocomplete
                    v-model="service.id"
                    :items="services"
                    item-value="id"
                    item-text="name"
                    label="SERVIÇO"
                    outlined
                    dense
                    :loading="loadingServices"
                    no-data-text="Nenhum serviço encontrado"
                    v-on:change="_addDefaultValueService(index)"
                  >
                  </v-autocomplete>
                </v-col>

                <v-col cols="12" md="2">
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        prefix="R$"
                        label="PREÇO PADRÃO"
                        outlined
                        dense
                        :value="service.default_value"
                        :loading="loading"
                        readonly
                        color="black"
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                      </template>
                    <span> Preço cadastrado no serviço </span>
                  </v-tooltip>
                </v-col>

                <v-col cols="12" md="2">
                  <v-text-field
                    label="QUANTIDADE"
                    outlined
                    type="number"
                    dense
                    v-model="service.quantity"
                    :loading="loading"
                    v-on:click="_sumTotalValueService(index)"
                    v-on:keyup="_sumTotalValueService(index)"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="2">
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        prefix="R$"
                        type="number"
                        label="VALOR"
                        outlined
                        dense
                        v-model="service.value"
                        :loading="loading"
                        v-on:click="_sumTotalValueService(index)"
                        v-on:keyup="_sumTotalValueService(index)"
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <span> Se necessário, use ponto (.) ao invés de virgula (,) </span>
                  </v-tooltip>
                </v-col>

                <v-col cols="12" md="2">
                  <v-text-field
                    prefix="R$"
                    label="VALOR TOTAL"
                    readonly
                    outlined
                    dense
                    :value="service.total_value.toFixed(2)"
                    :loading="loading"
                    color="black"
                  ></v-text-field>
                </v-col>
              </v-row>

              <v-divider color="grey" class="mx-5" v-if="(index + 1) < order.services.length"/>
            </v-col>

            <v-col cols="12" class="d-flex flex-row justify-end">
              <v-btn
                color="btnPrimary"
                @click="order.services.push({
                  quantity: 1,
                  value: 0,
                  default_value: 0,
                  total_value: 0
                })"
                :loading="loading"
                small
                rounded
              >
                Adicionar serviço <v-icon>mdi-plus</v-icon>
              </v-btn>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6" offset-md="6">
              <v-text-field
                outlined
                prefix="R$"
                type="number"
                :value="valueTotal"
                dense
                label="VALOR TOTAL"
                :loading="loading"
                readonly
                color="black"
              ></v-text-field>
            </v-col>
          </v-row>

        </v-tab-item>

        <!-- Pagamentos  -->
        <v-tab-item>
          <v-card-title>
            Formas de pagamento aceitas
          </v-card-title>

          <v-card-subtitle>
            Orçamento/Order de serviço
          </v-card-subtitle>

          <v-card-text>
            <v-row>
              <v-col cols="3" v-for="(payment, index) in payments" :key="index">
                <v-switch
                  inset
                  :label="payment"
                  :value="payment"
                  v-model="paymentMethods"
                ></v-switch>
              </v-col>
            </v-row>
          </v-card-text>
        </v-tab-item>

        <!-- Financeiro  -->
        <v-tab-item>
          <v-row>
            <v-col cols="12">
              <div class="text-h6 blue--text"> Valor </div>
              <v-divider color="grey"/>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                outlined
                prefix="R$"
                :value="valueTotal"
                dense
                label="VALOR TOTAL"
                :loading="loading"
                readonly
                color="black"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                prefix="R$"
                label="VALOR DESCONTO"
                type="number"
                outlined
                dense
                v-model="order.discount_amount"
                :loading="loading"
                hint="Valor do desconto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                outlined
                prefix="R$"
                :value="valueTotalWithDiscont"
                dense
                label="VALOR FINAL"
                :loading="loading"
                readonly
                color="black"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                outlined
                prefix="R$"
                :value="order.amount_paid"
                dense
                label="VALOR PAGO"
                readonly
                color="black"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                outlined
                prefix="R$"
                :value="(valueTotalWithDiscont - order.amount_paid).toFixed(2)"
                dense
                label="VALOR DEVEDOR"
                readonly
                color="black"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-divider color="grey" class="my-5"></v-divider>

          <v-row>
            <v-col cols="12">
              <div class="text-h6 blue--text"> Pagamento(s) </div>
              <v-divider color="grey"/>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="PAGAMENTO"
                outlined
                dense
                v-model="order.type_payment"
                readonly
              >
              </v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="QUANTIDADE PARCELAS"
                outlined
                dense
                v-model="order.number_of_installments"
                readonly
              >
              </v-text-field>
            </v-col>
          </v-row>

          <v-row
            v-for="(installment, index) in order.installments"
            :key="index"
          >
            <v-col cols="12" md="2">
              <v-text-field
                outlined
                prefix="R$"
                type="number"
                dense
                label="VALOR"
                v-model="installment.value"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3">
               <v-select
                :items="payments"
                label="FORMA DE PAGAMENTO"
                outlined
                dense
                v-model="installment.payment_method"
              ></v-select>
            </v-col>

            <v-col cols="12" md="2">
              <v-dialog
                v-model="menu_date_installments_pay_day[index]"
                :close-on-content-click="false"
                max-width="290"
                transition="scale-transition"
                offset-y
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    :value="installment.pay_day | formatDate"
                    label="DATA PAGAMENTO"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    outlined
                    dense
                    clearable
                    @click:clear="installment.pay_day = null"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="installment.pay_day"
                  @change="menu_date_installments_pay_day[index] = false"
                  scrollable
                  no-title
                  crollable
                  locale="pt-Br"
                ></v-date-picker>
              </v-dialog>
            </v-col>

            <v-col cols="12" md="2">
              <v-dialog
                v-model="menu_date_installments_due_date[index]"
                :close-on-content-click="false"
                max-width="290"
                transition="scale-transition"
                offset-y
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    :value="installment.due_date | formatDate"
                    label="DATA VENCIMENTO"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    outlined
                    dense
                    clearable
                    @click:clear="installment.due_date = null"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="installment.due_date"
                  @change="menu_date_installments_due_date[index] = false"
                  scrollable
                  no-title
                  crollable
                  locale="pt-Br"
                ></v-date-picker>
              </v-dialog>
            </v-col>

            <v-col cols="12" md="3">
               <v-select
                :items="['PAGO', 'EM ABERTO', 'CANCELADO', 'INADIMPLENTE']"
                label="STATUS"
                outlined
                dense
                v-model="installment.status"
              ></v-select>
            </v-col>

            <v-divider color="grey" class="mx-5" v-if="(index + 1) < order.installments.length"/>
          </v-row>

          <v-row>
             <v-col cols="12" class="d-flex flex-row justify-end">
              <v-btn
                color="btnDanger"
                @click="deleteLastInstallment"
                :loading="loading"
                small
                rounded
                v-if="order.installments.length > 0"
              >
                Apagar ultima parcela <v-icon color="red darken-4">mdi-delete</v-icon>
              </v-btn>

              <v-btn
                color="btnPrimary"
                class="ml-3"
                :loading="loading" small rounded
                @click="order.installments.push({
                  number: order.installments.length + 1,
                  payment_method: null,
                  status: 'EM ABERTO',
                  due_date: dateInstallment(),
                  pay_day: null,
                  value: null
                })"
              >
                Adicionar parcela <v-icon>mdi-plus</v-icon>
              </v-btn>
            </v-col>
          </v-row>
        </v-tab-item>

        <!-- Garantia -->
        <v-tab-item>
          <v-row>
            <v-col cols="12" md="2">
              <v-text-field
                label="DIAS GARANTIA"
                type="number"
                outlined
                dense
                v-model="order.warranty_days"
                :loading="loading"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="10">
              <v-textarea
                label="CONDIÇÃO DE GARANTIA"
                outlined
                dense
                v-model="order.warranty_conditions"
                :loading="loading"
                hint="TERMOS DE GARANTIA"
                @input="order.warranty_conditions = order.warranty_conditions.toUpperCase()"
              ></v-textarea>
            </v-col>
          </v-row>
        </v-tab-item>
      </v-tabs-items>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          v-if="(!idByRoute && $role.client.add()) || (idByRoute && $role.client.update()) "
          color="btnPrimary"
          @click="_store(true)"
          :loading="loading"
          rounded
        >
          Salvar <v-icon class="ml-2">mdi-content-save</v-icon>
        </v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
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
  watch: {
    'order.installments': {
      deep: true,
      handler() {
        this.calculeAmoutPaid();

        this.order.number_of_installments = this.order.installments.length;

        if(this.order.number_of_installments === 0){
          this.order.type_payment = null;
          this.order.number_of_installments = null;
        } else if(this.order.number_of_installments === 1){
          this.order.type_payment = 'A VISTA';
        } else {
          this.order.type_payment = 'PARCELADO';
        }
      }
    }
  },
  filters: {
    formatDate(date){
      return date ? moment(date).format('DD/MM/YYYY') : '';
    },
  },
  data: () => ({
    tab: null,
    loading: false,
    loadingClients: false,
    loadingProducts: false,
    loadingServices: false,
    loadingAddresses: false,
    loadingPayment: false,
    menu_technical_visit_date: false,
    menu_time: false,
    menu_date_payments: [],
    menu_date_installments_pay_day: [],
    menu_date_installments_due_date: [],
    errors: {
      client_id: false,
      address_id: false,
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
      technical_visit_date : null,
      technical_visit_time : '',
      discount_amount : null,
      warranty_days : null,
      warranty_conditions : null,
      installments: [],
      type_payment: '',
      number_of_installments: null,
      products: [],
      services: [],
      client_id: null,
      status: 'AGUARDANDO APROVAÇÃO',
      address_id: null,
      accepted_payment_methods: 'PIX,DINHEIRO,CARTÃO DÉBITO,CARTÃO CRÉDITO',
    },
    clients: [],
    products: [],
    services: [],
    addresses: [],
    accepted_payment_methods: [],
    payments: [
      'PIX',
      'DINHEIRO',
      'CARTÃO DÉBITO',
      'CARTÃO CRÉDITO',
      'CHEQUE',
      'BOLETO',
      'CONTRATO',
      'TRANSFERÊNCIA'
    ],
    statuses: [
      'AGUARDANDO APROVAÇÃO',
      'EM ANDAMENTO',
      'CONCLUÍDO',
      'CANCELADO',
    ],
  }),
  computed: {
    titlePage(){
      return this.$route.params.id ? `Editar Pedido | nº ${this.$route.params.id}` : 'Adicionar Pedido';
    },
    idByRoute(){
      return this.$route.params.id;
    },
    paymentMethods: {
      get: function() {
        return this.order.accepted_payment_methods ? this.order.accepted_payment_methods.split(',') : [];
      },
      set: function(value) {
        this.order.accepted_payment_methods = value.join(',');
      }
    },
    valueTotal(){
      let valueTotal = 0;
      this.order.products.forEach(product => valueTotal += product.quantity && product.value ? product.value * product.quantity : 0);
      this.order.services.forEach(service => valueTotal += service.quantity && service.value ? service.value * service.quantity : 0);
      return valueTotal.toFixed(2);
    },
    valueTotalWithDiscont(){
      let valueTotal = 0;
      this.order.products.forEach(product => valueTotal += product.quantity && product.value ? product.value * product.quantity : 0);
      this.order.services.forEach(service => valueTotal += service.quantity && service.value ? service.value * service.quantity : 0);
      valueTotal -= this.order.discount_amount;
      return valueTotal.toFixed(2);
    },
    nowFormat () {
      return moment().format('DD/MM/YYYY')
    },
    technicalVisitDateFormat () {
        return this.order.technical_visit_date ? moment(this.order.technical_visit_date).format('DD/MM/YYYY') : ''
    },
  },
  mounted(){
    this._start();
  },
  methods: {
    dateInstallment() {
      return moment().add(this.order.installments.length, 'M').format('YYYY-MM-DD');
    },
    calculeAmoutPaid() {
      let amountPaid = 0;
      this.order.installments.forEach(installment => amountPaid += installment.status === 'PAGO' ? parseFloat(installment.value) : 0);
      this.order.amount_paid = isNaN(amountPaid) ? (0).toFixed(2) : amountPaid.toFixed(2);
    },
    async deleteLastInstallment() {
      const ok = await this.$refs.fireDialog.confirm({
          title: `Deletar parcela ${this.order.installments.length}`,
          textConfirmButton: 'Deletar',
          colorConfirButton: 'btnDanger',
          colorCancelButton: 'btnPrimary'
      })

      if (ok) {
        this.order.installments.pop();
      }
    },
    async _start(){
      if(this.idByRoute){
        await this._load();
      }
      await this._loadClients();
      await this._loadProducts();
      await this._loadServices();
    },
    async _load(){
      let id = this.$route.params.id ? this.$route.params.id : this.order.id ? this.order.id : null;

      this.loading = true;
      await axios.get(`/api/order/${id}`).then(response => {
        if(response.data.success){
          this.order = response.data.data;
          return;
        }

        this.$refs.fireDialog.error({ title: 'Error ao carregar dados da ordem', time: 1500 })
      });
      this.loading = false;
    },
    async _loadClients(){
      this.loadingClients = true;
      await axios.get(`/api/client`).then(response => {
        if(response.data.success){
          this.clients = response.data.data;

          if(this.idByRoute){
            this._loadAddresses();
          }
          return;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar clientes' })
      });
      this.loadingClients = false;
    },
    async _loadAddresses(){
      let params = { client_id: this.order.client_id };

      this.loadingAddresses = true;
      await axios.get(`/api/address`, { params }).then(response => {
        if(response.data.success){
          this.order.address_id = response.data.data.length > 0 ? response.data.data[0].id : null;
          return this.addresses = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar endereços' })
      });
      this.loadingAddresses = false;
    },
    async _loadProducts(){
      this.loadingProducts = true;
      await axios.get(`/api/product`).then(response => {
        if(response.data.success){
          return this.products = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar produtos' })
      });
      this.loadingProducts = false;
    },
    async _loadServices(){
      this.loadingServices = true;
      await axios.get(`/api/service`).then(response => {
        if(response.data.success){
          return this.services = response.data.data;
        }
        this.$refs.fireDialog.error({ title: 'Error ao carregar serviços' })
      });
      this.loadingServices = false;
    },
    _addDefaultValueProduct(index){
      let filterProduct = this.products.filter(prod => prod.id === this.order.products[index].id);
      if(filterProduct[0] && filterProduct[0].default_value){
        this.order.products[index].default_value = filterProduct[0].default_value.toFixed(2);
        this.order.products[index].value = filterProduct[0].default_value.toFixed(2);
      } else {
        this.order.products[index].default_value = 0
        this.order.products[index].value = 0
      }

      this._sumTotalValueProduct(index)
    },
    _sumTotalValueProduct(index){
      this.order.products[index].total_value = this.order.products[index].value * this.order.products[index].quantity;
    },
    _addDefaultValueService(index){
      let filterService = this.services.filter(prod => prod.id === this.order.services[index].id);
      if(filterService[0] && filterService[0].default_value){
        this.order.services[index].default_value = filterService[0].default_value.toFixed(2);
        this.order.services[index].value = filterService[0].default_value.toFixed(2);
      } else {
        this.order.services[index].default_value = 0
        this.order.services[index].value = 0
      }

      this._sumTotalValueService(index)
    },
    _sumTotalValueService(index){
      this.order.services[index].total_value = this.order.services[index].value * this.order.services[index].quantity;
    },
    async _store(backRoute = false){
      /* Validations */
      for (const field in this.errors) {
        if(!this.order[field]){
          this.errors[field] = true;
          this.tab = null;
          return false;
        }
      }

      let installmentWithoutValue = this.order.installments.filter(installment => !installment.value);
      if(installmentWithoutValue.length > 0) {
        return this.$refs.fireDialog.warning({
          title: 'Parcelas',
          message: 'Existem parcelas no pedido sem valor. Adicione o valor antes de salvar.'
        })
      }

      let id = this.order.id;

      this.loading = true;

      this.$refs.fireDialog.loading({ title: id ? 'Atualizando...' : 'Salvando...' })

      this.order.amount = this.valueTotalWithDiscont;

      const response = !id ? await axios.post('/api/order', this.order) : await axios.put(`/api/order/${id}`, this.order);

      this.loading = false;

      if(response.data.success){
        this.order = response.data.data;

        this.$refs.fireDialog.success({ title: 'Salvo com sucesso' });
        this.$refs.fireDialog.hide(1500);
      } else {
        this.$refs.fireDialog.error({ title: 'Erro ao salvar' });
      }

      if(backRoute){
        setTimeout(() => this.$router.push({ name: 'order.index' }), 1500);
      }

      return response.data.success;
    },
    async serviceOrderDownload(){
      await this._store();

      window.open(`/api/docs/service-order/${this.order.id}/download`);
    },
    async budgetDownload(){
      await this._store();

      window.open(`/api/docs/budget/${this.order.id}/download`);
    },
    async receiptDownload(){
      await this._store();

      window.open(`/api/docs/receipt/${this.order.id}/download`);
    },
    async _generateAppointment(){
      if(await this._store()){
        this.$router.push({
          name: 'appointment.create',
          query: {
            orderId: this.order.id,
            clientId: this.order.client_id,
            addressId: this.order.address_id,
          }
        })
      }
    },
  }

}
</script>
