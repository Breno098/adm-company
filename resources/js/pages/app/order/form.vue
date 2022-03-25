<template>
  <div>
    <fire-dialog ref="fireDialog"></fire-dialog>

    <p class="font-weight-bold mb-5 text-h5">
      <v-icon color="primary">mdi-format-list-checks</v-icon>
      {{ titlePage }}
    </p>

    <v-row class="mb-2">
      <v-col cols="6" md="10">
        <v-btn
          color="btn-primary"
          small
          text
          @click="$router.go(-1)"
        >
          Voltar <v-icon>mdi-chevron-double-left</v-icon>
        </v-btn>
      </v-col>

      <v-col cols="6" md="2">
        <v-btn
          color="btn-primary"
          class="rounded-lg"
          block
          small
          dark
          @click="_store(true)"
          v-if="canSave"
        >
          Salvar <v-icon small class="ml-2">mdi-content-save</v-icon>
        </v-btn>

          <!-- :loading="loading" -->

      </v-col>
    </v-row>

    <v-row class="mb-2">
      <v-col cols="2" offset="2">
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              @click="budgetDownload"
              v-bind="attrs"
              v-on="on"
              color="btn-primary"
              class="rounded-lg"
              block
              small
              dark
            >
              <v-icon small>mdi-file-document</v-icon>
            </v-btn>
          </template>
          <span>Gerar Orçamento</span>
        </v-tooltip>
      </v-col>

      <v-col cols="2">
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              @click="serviceOrderDownload"
              v-bind="attrs"
              v-on="on"
              color="btn-primary"
              class="rounded-lg"
              block
              small
              dark
            >
              <v-icon small>mdi-file-export</v-icon>
            </v-btn>
          </template>
          <span>Gerar Ordem de Serviço</span>
        </v-tooltip>
      </v-col>

      <v-col cols="2">
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              @click="receiptDownload"
              v-bind="attrs"
              v-on="on"
              color="btn-primary"
              class="rounded-lg"
              block
              small
              dark
            >
              <v-icon small>mdi-file-check</v-icon>
            </v-btn>
          </template>
          <span>Gerar Recibo</span>
        </v-tooltip>
      </v-col>

      <!-- <v-col cols="2">
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              @click="warrantyOrderDownload"
              v-bind="attrs"
              v-on="on"
              color="btn-primary"
              class="rounded-lg"
              block
              small
              dark
            >
              <v-icon>mdi-format-align-center</v-icon>
            </v-btn>
          </template>
          <span>Gerar Order de Garantia</span>
        </v-tooltip>
      </v-col> -->

      <v-col cols="2">
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              @click="_generateAppointment"
              v-bind="attrs"
              v-on="on"
              color="btn-primary"
              class="rounded-lg"
              block
              small
              dark
            >
              <v-icon small>mdi-calendar-today</v-icon>
            </v-btn>
          </template>
          <span>Agendar Compromisso</span>
        </v-tooltip>

      </v-col>
    </v-row>

    <v-card>
      <v-tabs v-model="tab">
        <v-tabs-slider color="primary"></v-tabs-slider>
        <v-tab>Informações <v-icon small class="ml-2">mdi-information</v-icon></v-tab>
        <v-tab>Produtos e Serviços <v-icon small class="ml-2">mdi-wrench</v-icon></v-tab>
        <v-tab>Pagamento <v-icon small class="ml-2">mdi-cash</v-icon></v-tab>
        <v-tab>Financeiro <v-icon small class="ml-2">mdi-cash</v-icon></v-tab>
        <v-tab>Garantia <v-icon small class="ml-2">mdi-format-align-center</v-icon></v-tab>
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
                filled
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
                filled
                dense
              ></v-select>
            </v-col>

            <v-col cols="12" class="pt-0">
              <v-hover v-slot="{ hover }">
                <v-card
                  @click="modalAddress = true"
                  :color="hover ? 'primary' : null"
                  :elevation="hover ? 12 : 0"
                  outlined
                >
                  <v-card-text class="d-flex flex-row">
                    <v-icon :color="hover ? 'white' :'primary'">mdi-map-marker-radius</v-icon>
                    <div class="ml-3" :class="hover ? 'white--text' :'primary--text'">
                      <div>{{ order.address | addressStringParteOne }}</div>
                      <div>{{ order.address | addressStringParteTwo }}</div>
                      <div>{{ order.address | addressStringParteThree }}</div>
                    </div>
                  </v-card-text>
                </v-card>
              </v-hover>
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
                    :value="order.technical_visit_date | formatDMY"
                    label="DATA VISITA TÉCNICA"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    filled
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
                  color="primary"
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
                    filled
                  ></v-text-field>
                </template>
                <v-time-picker
                  v-if="menu_time"
                  v-model="order.technical_visit_time"
                  @click:minute="$refs.menu_time.save(order.technical_visit_time)"
                  format="24hr"
                  color="primary"
                ></v-time-picker>
              </v-menu>
            </v-col>

            <v-col cols="12">
              <v-textarea
                label="PROBLEMA RECLAMADO"
                filled
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
                filled
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
                filled
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
                filled
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
              <div class="text-h6 font-weight-bold"> Produtos </div>
              <v-divider class="mx-4 my-3"></v-divider>
            </v-col>

            <v-col cols="12" v-for="(product, index) in order.products" :key="product.id">
              <v-row>
                <v-col cols="12" class="d-flex flex-row justify-end">
                  <v-btn
                    color="btn-delete"
                    @click="order.products.splice(index, 1);"
                    :loading="loading"
                    x-small
                    class="rounded-lg"
                    dark
                  >
                    <v-icon small>mdi-close</v-icon>
                  </v-btn>
                </v-col>

                <v-col cols="12" md="4">
                  <v-autocomplete
                    v-model="product.id"
                    :items="products"
                    item-value="id"
                    item-text="name"
                    label="PRODUTO"
                    filled
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
                        filled
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
                    filled
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
                        filled
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
                    filled
                    dense
                    :value="product.total_value.toFixed(2)"
                    :loading="loading"
                    color="black"
                  ></v-text-field>
                </v-col>
              </v-row>

              <v-divider class="mx-4 my-3" v-if="(index + 1) < order.products.length"/>
            </v-col>

            <v-col cols="12" class="d-flex flex-row justify-end">
              <v-btn
                color="btn-primary"
                :loading="loading"
                small
                dark
                class="rounded-lg"
                @click="order.products.push({
                  quantity: 1,
                  value: 0,
                  default_value: 0,
                  total_value: 0
                })"
              >
                Adicionar produto <v-icon small>mdi-plus</v-icon>
              </v-btn>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12">
              <div class="text-h6 font-weight-bold"> Serviços </div>
              <v-divider class="mx-4 my-3"></v-divider>
            </v-col>

            <v-col cols="12" v-for="(service, index) in order.services" :key="service.id">
              <v-row>
                <v-col cols="12" class="d-flex flex-row justify-end">
                  <v-btn
                    color="btn-delete"
                    @click="order.services.splice(index, 1);"
                    :loading="loading"
                    x-small
                    class="rounded-lg"
                    dark
                  >
                    <v-icon small>mdi-close</v-icon>
                  </v-btn>
                </v-col>

                <v-col cols="12" md="4">
                  <v-autocomplete
                    v-model="service.id"
                    :items="services"
                    item-value="id"
                    item-text="name"
                    label="SERVIÇO"
                    filled
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
                        filled
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
                    filled
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
                        filled
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
                    filled
                    dense
                    :value="service.total_value.toFixed(2)"
                    :loading="loading"
                    color="black"
                  ></v-text-field>
                </v-col>
              </v-row>

              <v-divider class="mx-4 my-3" v-if="(index + 1) < order.services.length"/>
            </v-col>

            <v-col cols="12" class="d-flex flex-row justify-end">
              <v-btn
                color="btn-primary"
                :loading="loading"
                small
                dark
                class="rounded-lg"
                @click="order.services.push({
                  quantity: 1,
                  value: 0,
                  default_value: 0,
                  total_value: 0
                })"
              >
                Adicionar serviço <v-icon small>mdi-plus</v-icon>
              </v-btn>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6" offset-md="6">
              <v-text-field
                filled
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
          <v-card-title class="font-weight-bold">
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
              <div class="text-h6 font-weight-bold"> Valor </div>
              <v-divider class="mx-4 my-3"></v-divider>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                filled
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
                filled
                dense
                v-model="order.discount_amount"
                :loading="loading"
                hint="Valor do desconto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                filled
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
                filled
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
                filled
                prefix="R$"
                :value="(valueTotalWithDiscont - order.amount_paid).toFixed(2)"
                dense
                label="VALOR DEVEDOR"
                readonly
                color="black"
              ></v-text-field>
            </v-col>
          </v-row>

          <v-divider class="mx-4 my-3"></v-divider>

          <v-row>
            <v-col cols="12">
              <div class="text-h6 font-weight-bold"> Pagamento(s) </div>
              <v-divider class="mx-4 my-3"></v-divider>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="PAGAMENTO"
                filled
                dense
                v-model="order.type_payment"
                readonly
              >
              </v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="QUANTIDADE PARCELAS"
                filled
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
                filled
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
                filled
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
                    filled
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
                  color="primary"
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
                    filled
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
                  color="primary"
                ></v-date-picker>
              </v-dialog>
            </v-col>

            <v-col cols="12" md="3">
               <v-select
                :items="['PAGO', 'EM ABERTO', 'CANCELADO', 'INADIMPLENTE']"
                label="STATUS"
                filled
                dense
                v-model="installment.status"
              ></v-select>
            </v-col>

            <v-divider class="mx-4 my-3" v-if="(index + 1) < order.installments.length"/>
          </v-row>

          <v-row>
             <v-col cols="12" class="d-flex flex-row justify-end">
              <v-btn
                color="btn-delete"
                @click="deleteLastInstallment"
                :loading="loading"
                small
                class="rounded-lg"
                dark
                v-if="order.installments.length > 0"
              >
                Apagar ultima parcela <v-icon small>mdi-close</v-icon>
              </v-btn>

              <v-btn
                color="btn-primary"
                small
                dark
                class="rounded-lg ml-3"
                :loading="loading"
                @click="order.installments.push({
                  number: order.installments.length + 1,
                  payment_method: null,
                  status: 'EM ABERTO',
                  due_date: dateInstallment(),
                  pay_day: null,
                  value: null
                })"
              >
                Adicionar parcela <v-icon small>mdi-plus</v-icon>
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
                filled
                dense
                v-model="order.warranty_days"
                :loading="loading"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="10">
              <v-textarea
                label="CONDIÇÃO DE GARANTIA"
                filled
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
        <v-btn
          color="btn-primary"
          class="rounded-lg"
          small
          dark
          @click="_store(true)"
          v-if="canSave"
          :loading="loading"
        >
          Salvar <v-icon small class="ml-2">mdi-content-save</v-icon>
        </v-btn>
      </v-card-actions>
    </v-card>

    <v-dialog v-model="modalAddress" max-width="800px">
      <v-card>
        <v-card-title>
          <v-icon color="primary" small>mdi-map-marker-radius</v-icon>
          <span class="font-weight-bold text-h5 ml-3">Localização</span>
        </v-card-title>
        <v-card-text>
          <v-hover
            v-slot="{ hover }"
            v-for="address, index in addresses"
            :key="`address-${index}`"
            class="mb-2"
          >
            <v-card
              @click="chooseAddress(address)"
              :color="hover ? 'primary' : null"
              :elevation="hover ? 12 : 0"
              outlined
            >
              <v-card-text class="d-flex flex-row">
                <v-icon :color="hover ? 'white' :'primary'" class="mr-2">mdi-map-marker-radius</v-icon>
                <div class="ml-3" :class="hover ? 'white--text' :'primary--text'">
                  <div>{{ address | addressStringParteOne }}</div>
                  <div>{{ address | addressStringParteTwo }}</div>
                  <div>{{ address | addressStringParteThree }}</div>
                </div>
              </v-card-text>
            </v-card>
          </v-hover>

          <v-divider class="my-4 mx-3"></v-divider>

          <v-row>
            <v-col cols="12" md="3">
              <v-text-field
                label="CEP"
                v-mask="'#####-###'"
                filled
                dense
                v-model="order.address.cep"
                :loading="loading"
                v-on:keyup.enter="searchCep()"
                v-on:keyup="searchCep()"
                clearable
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="RUA"
                filled
                dense
                v-model="order.address.street"
                :loading="loading"
                @input="order.address.street = order.address.street.toUpperCase()"
                clearable
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3">
              <v-text-field
                label="NÚMERO"
                filled
                dense
                v-model="order.address.number"
                :loading="loading"
                clearable
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="BAIRRO"
                filled
                dense
                v-model="order.address.district"
                :loading="loading"
                @input="order.address.district = order.address.district.toUpperCase()"
                clearable
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                label="CIDADE"
                filled
                dense
                v-model="order.address.city"
                :loading="loading"
                @input="order.address.city = order.address.city.toUpperCase()"
                clearable
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                label="ESTADO"
                filled
                dense
                v-model="order.address.state"
                :loading="loading"
                @input="order.address.state = order.address.state.toUpperCase()"
                clearable
              ></v-text-field>
            </v-col>

            <v-col cols="6" md="4">
              <v-text-field
                label="APARTAMENTO"
                filled
                dense
                v-model="order.address.apartment"
                :loading="loading"
                @input="order.address.apartment = order.address.apartment.toUpperCase()"
                clearable
              ></v-text-field>
            </v-col>

            <v-col cols="6" md="2">
              <v-text-field
                label="BLOCO"
                filled
                dense
                v-model="order.address.block"
                :loading="loading"
                @input="order.address.block = order.address.block.toUpperCase()"
                clearable
              ></v-text-field>
            </v-col>

            <v-col cols="6" md="2">
              <v-text-field
                label="ANDAR"
                filled
                dense
                v-model="order.address.floor"
                :loading="loading"
                @input="order.address.floor = order.address.floor.toUpperCase()"
                clearable
              ></v-text-field>
            </v-col>

            <v-col cols="6" md="2">
              <v-text-field
                label="TORRE"
                filled
                dense
                v-model="order.address.tower"
                :loading="loading"
                @input="order.address.tower = order.address.tower.toUpperCase()"
                clearable
              ></v-text-field>
            </v-col>

            <v-col cols="6" md="2">
              <v-text-field
                label="CASA"
                filled
                dense
                v-model="order.address.house"
                :loading="loading"
                @input="order.address.house = order.address.house.toUpperCase()"
                clearable
              ></v-text-field>
            </v-col>

            <v-col cols="12">
              <v-text-field
                label="COMPLEMENTO"
                filled
                dense
                v-model="order.address.complement"
                :loading="loading"
                @input="order.address.complement = order.address.complement.toUpperCase()"
                clearable
              ></v-text-field>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="btn-primary"
            class="rounded-lg"
            small
            text
            @click="order.address = {}"
          >
            Limpar
          </v-btn>
          <v-btn
            color="btn-primary"
            class="rounded-lg"
            small
            dark
            @click="modalAddress = false"
          >
            Ok
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
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
    modalAddress: false,
    addressDetails: false,
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
    canSave(){
      return this.$can('order_add') && !this.idByRoute || this.$can('order_update') && this.idByRoute;
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
    }

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
          colorConfirButton: 'btn-delete',
          colorCancelButton: 'btn-primary'
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

          if(!response.data.data.address) {
             this.order.address = {};
          }

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
    async searchCep() {
      let cep = this.order.address.cep.replace('-', '');

      if(cep.length != 8)
        return;

      this.$refs.fireDialog.loading({ title: 'Buscando endereço' })

      let params = { cep };
      await axios.get(`/api/address/searchCep`, { params }).then(response => {
        if(response.data.data.erro){
          return this.$refs.fireDialog.error({ title: 'Error ao carregar endereço' })
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
    async serviceOrderDownload(){
      await this._store();

      window.open(`/api/docs/service-order/${this.order.id}/download`);
    },
    async warrantyOrderDownload(){
      await this._store();

      window.open(`/api/docs/warranty-order/${this.order.id}/download`);
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
    chooseAddress(address) {
      this.order.address = { ...address };
      // this.addressDetails = true;
    }
  }

}
</script>
