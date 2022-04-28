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
          color="btn-primary"
          class="rounded-lg"
          block
          small
          dark
          v-if="canSave"
          :loading="loading"
          @click="showModalStatus"
        >
          Salvar <v-icon small class="ml-2">mdi-pencil</v-icon>
        </v-btn>
      </v-col>

      <v-col cols="2" md="1">
        <v-btn
          color="btn-delete"
          class="rounded-lg"
          block
          small
          dark
          v-if="canSave"
          :loading="loading"
        >
          <v-icon small>mdi-delete</v-icon>
        </v-btn>
      </v-col>
    </v-row>

    <v-card class="mx-1">
      <v-tabs v-model="tab">
        <v-tabs-slider color="primary"></v-tabs-slider>
        <v-tab>Pedido <v-icon small class="ml-2">mdi-file-document</v-icon> </v-tab>
        <v-tab>Relatórios e garantias <v-icon small class="ml-2">mdi-format-align-center</v-icon></v-tab>
        <v-tab v-if="showFinanceTab">Financeiro <v-icon small class="ml-2">mdi-cash</v-icon></v-tab>
      </v-tabs>
    </v-card>

    <v-tabs-items v-model="tab" class="pt-5 px-1" style="background-color: transparent !important;">
      <v-tab-item>
        <v-row class="mb-2">
          <v-col cols="12">
            <v-card>
              <v-card-text>
                <!-- <div class="d-flex align-center"> -->
                  <v-icon color="btn-delete">mdi-comment-alert-outline</v-icon>
                  <strong class="text-h6 font-weight-black ml-2 primary--text">
                    Problema reclamado
                  </strong>
                <!-- </div> -->
              </v-card-text>

              <v-card-actions class="pb-4 px-3">
                {{ order.complaint | textLimitChar(50) }}
                <v-spacer></v-spacer>
                <v-btn
                  color="btn-primary"
                  class="rounded-lg mr-2"
                  small
                  dark
                  @click="modalComplaint.show = true"
                >
                  <v-icon small>mdi-comment-processing</v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>

          <v-col cols="12" md="8">
            <v-card>
              <v-card-text class="py-7">
                <v-row>
                  <v-col cols="12">
                    <div class="mb-3 d-flex align-center">
                      <v-icon color="primary">mdi-account</v-icon>
                      <strong class="text-h6 font-weight-black ml-2 primary--text">Cliente</strong>
                    </div>
                    {{ order.client ? order.client.name : '' }}
                  </v-col>
                </v-row>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="btn-primary"
                  class="rounded-lg mr-2"
                  small
                  dark
                  @click="showModalClient"
                  :disabled="!canSave"
                >
                  <v-icon small>
                    {{ idByRoute ? 'mdi-pencil' : 'mdi-magnify'}}
                  </v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>

          <v-col cols="12" md="4">
            <v-card class="fill-height d-flex flex-column align-center justify-center">
              <v-card-text class="py-4">
                <v-row>
                  <v-col cols="12">
                    <div>
                      <v-icon small color="primary">mdi-file-document</v-icon>
                      <b>Situação do pedido</b>
                    </div>

                    <v-chip
                      v-if="order.status"
                      class="d-flex justify-center mt-2"
                      label
                      :color="order.status | statusColor"
                      style="width: 100%;"
                      small
                    >
                      {{ order.status }}
                      <v-icon class="ml-2">{{ order.status | statusIcon }}</v-icon>
                    </v-chip>
                  </v-col>

                  <v-col cols="12">
                    <div>
                      <v-icon small color="primary">mdi-cash-multiple</v-icon>
                      <b>Status Finaceiro</b>
                    </div>

                    <v-chip
                      v-if="order.payment_status"
                      class="d-flex justify-center mt-2"
                      label
                      :color="order.payment_status | paymentStatusColor"
                      style="width: 100%;"
                      small
                    >
                      {{ order.payment_status }}
                      <v-icon class="ml-2">{{ order.payment_status | paymentStatusIcon }}</v-icon>
                    </v-chip>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="6">
            <v-card>
              <v-card-text class="py-7">
                <div class="mb-3 d-flex align-center">
                  <v-icon color="primary">mdi-comment-text-outline</v-icon>
                  <strong class="text-h6 font-weight-black ml-2 primary--text">
                    Anotações
                  </strong>
                </div>

                {{ order.comments | textLimitChar(50) }}
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="btn-primary"
                  class="rounded-lg mr-2"
                  small
                  dark
                  @click="modalComments.show = true"
                >
                  <v-icon small>mdi-comment-text-outline</v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>

          <v-col cols="12" md="9">
            <v-card>
              <v-card-text class="py-7">
                <v-row>
                  <v-col cols="12">
                    <div class="mb-3 d-flex align-center">
                      <v-icon color="primary">mdi-map-marker-radius</v-icon>
                      <strong class="text-h6 font-weight-black ml-2 primary--text">Local</strong>
                    </div>
                    {{ order.address && order.address.street ? order.address.street : '' }}
                    {{ order.address && order.address.number ? `n° ${order.address.number}` : '' }}
                    {{ order.address && order.address.district ? ` - ${order.address.district}` : '' }}
                    {{ order.address && order.address.city ? `- ${order.address.city}` : '' }}
                  </v-col>
                </v-row>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="btn-primary"
                  class="rounded-lg mr-2"
                  small
                  dark
                  :loading="loading"
                  @click="showModalAddress"
                >
                  <v-icon small>
                    {{ idByRoute ? 'mdi-pencil' : 'mdi-magnify'}}
                  </v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>

          <v-col cols="12" md="3">
            <v-card class="fill-height d-flex flex-column align-center justify-center">
              <v-card-text>
                <div class="mb-3 d-flex align-center">
                  <v-icon color="primary">mdi-cash-multiple</v-icon>
                  <strong class="text-h6 font-weight-black ml-2 primary--text">Valor total</strong>
                </div>

                <div class="text-h6 font-weight-black">
                  {{ valueTotal | formatMoney }}
                </div>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>

        <v-card class="mb-4">
          <v-card-text class="py-7">
            <div class="mb-3 d-flex align-center">
                <v-icon color="primary">mdi-wrench</v-icon>
                <strong class="text-h6 font-weight-black ml-2 primary--text">Serviços</strong>

                <v-spacer></v-spacer>
                <v-btn
                  color="btn-primary"
                  class="rounded-lg"
                  small
                  dark
                  :loading="loading"
                  @click="showModalService()"
                >
                  <v-icon small>mdi-plus</v-icon>
                </v-btn>
              </div>

              <v-data-table
                v-if="order.services.length > 0"
                :headers="headersTablesItems"
                :items="order.services"
                class="elevation-0"
                hide-default-footer
                no-data-text="Não há serviços para o pedido."
              >
                <template v-slot:item.value="{ item }">
                  {{ item.value | formatMoney }}
                </template>
                <template v-slot:item.total_value="{ item }">
                  {{ (item.quantity * item.value) | formatMoney }}
                </template>

                <template v-slot:item.actions="{ item }">
                  <v-icon small @click="showModalService(item)" color="primary">
                    mdi-pencil
                  </v-icon>
                  <v-icon small @click="deleteService(item)" color="btn-delete">
                    mdi-delete
                  </v-icon>
                </template>
              </v-data-table>
          </v-card-text>
        </v-card>

        <v-card class="mb-4">
          <v-card-text class="py-7">
            <div class="mb-3 d-flex align-center">
                <v-icon color="primary">mdi-barcode</v-icon>
                <strong class="text-h6 font-weight-black ml-2 primary--text">Produtos</strong>

                <v-spacer></v-spacer>
                <v-btn
                  color="btn-primary"
                  class="rounded-lg"
                  small
                  dark
                  :loading="loading"
                  @click="showModalProduct()"
                >
                  <v-icon small>mdi-plus</v-icon>
                </v-btn>
              </div>

              <v-data-table
               v-if="order.products.length > 0"
                :headers="headersTablesItems"
                :items="order.products"
                class="elevation-0"
                hide-default-footer
                no-data-text="Não há produtos para o pedido."
              >
                <template v-slot:item.value="{ item }">
                  {{ item.value | formatMoney }}
                </template>
                <template v-slot:item.total_value="{ item }">
                  {{ (item.quantity * item.value) | formatMoney }}
                </template>

                <template v-slot:item.actions="{ item }">
                  <v-icon small @click="showModalProduct(item)" color="primary">
                    mdi-pencil
                  </v-icon>
                  <v-icon small @click="deleteProduct(item)" color="btn-delete">
                    mdi-delete
                  </v-icon>
                </template>
              </v-data-table>
          </v-card-text>
        </v-card>

        <v-card class="mb-4">
          <v-card-text class="py-7">
            <div class="mb-3 d-flex align-center">
              <v-icon color="primary">mdi-credit-card-multiple</v-icon>
              <strong class="text-h6 font-weight-black ml-2 primary--text">Metodos pagamento aceitas</strong>
            </div>

            <v-row>
              <v-col cols="6" md="3" v-for="(payment, index) in payments" :key="index">
                <v-switch
                  inset
                  :label="payment"
                  :value="payment"
                  v-model="paymentMethods"
                ></v-switch>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-tab-item>

      <v-tab-item>
        <v-row class="mb-2">
          <v-col cols="12" md="6">
            <v-card>
              <v-card-text class="py-7">
                <div class="mb-3 d-flex align-center">
                  <v-icon color="btn-delete">mdi-comment-alert-outline</v-icon>
                  <strong class="text-h6 font-weight-black ml-2 primary--text">
                    Problema reclamado
                  </strong>
                </div>

                {{ order.complaint | textLimitChar(50) }}
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="btn-primary"
                  class="rounded-lg mr-2"
                  small
                  dark
                  @click="modalComplaint.show = true"
                >
                  <v-icon small>mdi-comment-processing</v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>

          <v-col cols="12" md="6">
            <v-card>
              <v-card-text class="py-7">
                <div class="mb-3 d-flex align-center">
                  <v-icon color="primary">mdi-comment-text-outline</v-icon>
                  <strong class="text-h6 font-weight-black ml-2 primary--text">
                    Anotações
                  </strong>
                </div>

                {{ order.comments | textLimitChar(50) }}
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="btn-primary"
                  class="rounded-lg mr-2"
                  small
                  dark
                  @click="modalComments.show = true"
                >
                  <v-icon small>mdi-comment-text-outline</v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>

          <v-col cols="12">
            <v-card>
              <v-card-text class="py-7">
                <div class="mb-3 d-flex align-center">
                  <v-icon color="primary">mdi-comment-processing-outline</v-icon>
                  <strong class="text-h6 font-weight-black ml-2 primary--text">
                    Problema constatado
                  </strong>
                </div>

                {{ order.work_found }}
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="btn-primary"
                  class="rounded-lg mr-2"
                  small
                  dark
                  @click="modalWorkFound.show = true"
                >
                  <v-icon small>mdi-pencil</v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>

          <v-col cols="12">
            <v-card class="mb-4">
              <v-card-text class="py-7">
                <div class="mb-3 d-flex align-center">
                  <v-icon color="green">mdi-comment-check-outline</v-icon>
                  <strong class="text-h6 font-weight-black ml-2 primary--text">
                    Trabalho realizado
                  </strong>
                </div>

                {{ order.work_done }}
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="btn-primary"
                  class="rounded-lg mr-2"
                  small
                  dark
                  @click="modalWorkDone.show = true"
                >
                  <v-icon small>mdi-pencil</v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-tab-item>

      <v-tab-item>
        <v-row class="mb-2">
          <v-col cols="12" md="3">
            <v-card class="fill-height d-flex flex-column align-center justify-center">
              <v-card-text>
                <div>
                  <v-icon small color="primary">mdi-cash-multiple</v-icon>
                  <b>Status Finaceiro</b>
                </div>

                <v-chip
                  v-if="order.payment_status"
                  class="d-flex justify-center mt-2"
                  label
                  :color="order.payment_status | paymentStatusColor"
                  style="width: 100%;"
                  small
                >
                  {{ order.payment_status }}
                  <v-icon class="ml-2">{{ order.payment_status | paymentStatusIcon }}</v-icon>
                </v-chip>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="9">
            <v-card>
              <v-card-text class="py-7">
                <div class="mb-3 d-flex align-center">
                  <v-icon color="primary">mdi-calculator</v-icon>
                  <strong class="text-h6 font-weight-black ml-2 primary--text">
                    Descontos
                  </strong>
                </div>

                <v-row>
                  <v-col cols="6">
                    <v-text-field
                      prefix="%"
                      label="PORCENTAGEM DESCONTO"
                      type="number"
                      filled
                      dense
                      v-model="order.discount_percentage"
                      min="0"
                      max="100"
                      @keyup="calcDiscontAmount"
                      @click="calcDiscontAmount"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="6">
                    <v-text-field
                      prefix="R$"
                      label="VALOR DESCONTO"
                      type="number"
                      filled
                      dense
                      v-model="order.discount_amount"
                      min="0"
                      :max="valueTotal"
                      @keyup="calcPercentageAmount"
                      @click="calcPercentageAmount"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="6">
            <v-card class="fill-height d-flex flex-column align-center justify-center">
              <v-card-text>
                <div class="text-subtitle-1">Valor bruto</div>
                <h1 class="primary--text">{{ valueTotal | formatMoney }}</h1>

                <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                <div class="text-subtitle-1">Desconto</div>
                <h3 class="orange--text">
                  - {{ order.discount_amount | formatMoney }}
                  ({{ order.discount_percentage ? order.discount_percentage : 0 }}%)
                </h3>

                <v-divider color="grey" class="mx-5 mt-3 mb-2"></v-divider>

                <div class="text-subtitle-1">Valor liquido</div>
                <h1 class="green--text">{{ valueTotalWithDiscont | formatMoney }}</h1>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="6">
            <v-card class="fill-height d-flex flex-column align-center justify-center">
              <v-card-text>
                <div class="text-subtitle-1">Valor pago</div>
                <h1 class="primary--text">{{ order.amount_paid | formatMoney }}</h1>

                <v-divider color="grey" class="mx-5 mt-5 mb-2"></v-divider>

                <div class="text-subtitle-1">Valor não pago</div>
                <h1 class="orange--text">
                  {{ (valueTotalWithDiscont - order.amount_paid) | formatMoney }}
                </h1>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>

        <v-card>
          <v-card-text class="py-7">
            <div class="mb-3 d-flex align-center">
                <v-icon color="primary">mdi-cash-multiple</v-icon>
                <strong class="text-h6 font-weight-black ml-2 primary--text">Pagamentos</strong>

                <v-spacer></v-spacer>
                <v-btn
                  color="btn-primary"
                  class="rounded-lg"
                  small
                  dark
                  @click="showModalInstallment()"
                >
                  <v-icon small>mdi-plus</v-icon>
                </v-btn>
              </div>

              <v-data-table
                v-if="order.installments.length > 0"
                :headers="headersTablePayments"
                :items="order.installments"
                class="elevation-0"
                hide-default-footer
                no-data-text="Não há pagamentos para o pedido."
                :items-per-page="order.installments.length"
              >
                <template v-slot:item.value="{ item }">
                  {{ item.value | formatMoney }}
                </template>
                <template v-slot:item.pay_day="{ item }">
                  {{ item.pay_day | formatDMY }}
                </template>
                <template v-slot:item.due_date="{ item }">
                  {{ item.due_date | formatDMY }}
                </template>
                <template v-slot:item.status="{ item }">
                  <v-chip
                    v-if="item.status"
                    class="d-flex justify-center mt-2"
                    label
                    :color="item.status | paymentInstallmentStatusColor"
                    small
                  >
                    {{ item.status }}
                    <v-icon class="ml-2">{{ item.status | paymentInstallmentStatusIcon }}</v-icon>
                  </v-chip>
                </template>

                <template v-slot:item.actions="{ item }">
                  <v-icon small @click="showModalInstallment(item)" color="primary">
                    mdi-pencil
                  </v-icon>
                  <v-icon small @click="deleteInstallment(item)" color="btn-delete">
                    mdi-delete
                  </v-icon>
                </template>
              </v-data-table>
          </v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs-items>

    <!-- Modals -->
    <!-- Modal Service -->
    <v-dialog v-model="modalService.show" max-width="800">
      <v-card>
        <v-card-title>
          <v-icon color="primary" small>mdi-wrench</v-icon>
          <span class="font-weight-bold text-h5 ml-3">Serviço</span>
        </v-card-title>

        <v-card-text class="py-5">
          <v-row>
            <v-col cols="12" md="6">
              <v-autocomplete
                v-model="modalService.editedItem.id"
                :items="services"
                item-value="id"
                item-text="name"
                label="SERVIÇO"
                filled
                dense
                no-data-text="Nenhum serviço encontrado"
                v-on:change="addDefaultValueService"
              >
              </v-autocomplete>
            </v-col>

            <v-col cols="12" md="3">
              <v-text-field
                label="QUANTIDADE"
                filled
                type="number"
                dense
                v-model="modalService.editedItem.quantity"
                :loading="loading"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3">
              <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    prefix="R$"
                    type="number"
                    label="VALOR"
                    filled
                    dense
                    v-model="modalService.editedItem.value"
                    :loading="loading"
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <span> Para centavos, utilize ponto (.) ao invés de virgula (,) </span>
              </v-tooltip>
            </v-col>
          </v-row>

          <v-divider class="mx-7 my-2"></v-divider>

          <div class="text-h6 font-weight-bold text-center">
            {{ (modalService.editedItem.quantity * modalService.editedItem.value) | formatMoney }}
          </div>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="btn-primary" class="rounded-lg" small text @click="modalService.show = false">
            Cancelar
          </v-btn>
          <v-btn color="btn-primary" class="rounded-lg" small dark @click="handleService">
            Adicionar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Modal Product -->
    <v-dialog v-model="modalProduct.show" max-width="800">
      <v-card>
        <v-card-title>
          <v-icon color="primary" small>mdi-barcode</v-icon>
          <span class="font-weight-bold text-h5 ml-3">Produto</span>
        </v-card-title>

        <v-card-text class="py-5">
          <v-row>
            <v-col cols="12" md="6">
              <v-autocomplete
                v-model="modalProduct.editedItem.id"
                :items="products"
                item-value="id"
                item-text="name"
                label="PRODUTO"
                filled
                dense
                no-data-text="Nenhum produto encontrado"
                v-on:change="addDefaultValueProduct"
              >
              </v-autocomplete>
            </v-col>

            <v-col cols="12" md="3">
              <v-text-field
                label="QUANTIDADE"
                filled
                type="number"
                dense
                v-model="modalProduct.editedItem.quantity"
                :loading="loading"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3">
              <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    prefix="R$"
                    type="number"
                    label="VALOR"
                    filled
                    dense
                    v-model="modalProduct.editedItem.value"
                    :loading="loading"
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <span> Para centavos, utilize ponto (.) ao invés de virgula (,) </span>
              </v-tooltip>
            </v-col>
          </v-row>

          <v-divider class="mx-7 my-2"></v-divider>

          <div class="text-h6 font-weight-bold text-center">
            {{ (modalProduct.editedItem.quantity * modalProduct.editedItem.value) | formatMoney }}
          </div>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="btn-primary" class="rounded-lg" small text @click="modalProduct.show = false">
            Cancelar
          </v-btn>
          <v-btn color="btn-primary" class="rounded-lg" small dark @click="handleProduct">
            Adicionar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Modal Client -->
    <v-dialog v-model="modalClient.show" max-width="600px">
      <v-card>
        <v-card-title>
          <v-icon color="primary">mdi-account</v-icon>
          <strong class="text-h6 font-weight-black ml-2 primary--text">Cliente</strong>
        </v-card-title>
        <v-card-text>
          <v-text-field
            v-model="modalClient.search"
            filled
            prepend-inner-icon="mdi-magnify"
            @input="modalClient.search = modalClient.search.toUpperCase()"
            placeholder="Busca"
            clearable
          ></v-text-field>

          <v-expand-transition>
            <v-virtual-scroll
              v-show="modalClient.search"
              height="250"
              item-height="60"
              :items="clientsSearch"
            >
              <template v-slot:default="{ item }">
                <v-list-item :key="item.id" @click="chooseClient(item)">
                  <v-list-item-content>
                    <v-list-item-title>
                    <strong>{{ item.name }}</strong>
                    </v-list-item-title>
                  </v-list-item-content>

                  <v-list-item-action>
                    <v-icon color="primary">
                      mdi-open-in-new
                    </v-icon>
                  </v-list-item-action>
                </v-list-item>

                <v-divider></v-divider>
              </template>
            </v-virtual-scroll>
          </v-expand-transition>

        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- Modal Address -->
    <v-dialog v-model="modalAddress.show" max-width="800px">
      <v-card>
        <v-card-title>
          <v-icon color="primary" small>mdi-map-marker-radius</v-icon>
           <strong class="text-h6 font-weight-black ml-2 primary--text">Localização</strong>
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
            @click="modalAddress.show = false"
          >
            Ok
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Modal Complaint -->
    <v-dialog v-model="modalComplaint.show" max-width="800px">
      <v-card>
        <v-card-title>
          <v-icon color="btn-delete">mdi-comment-alert-outline</v-icon>
          <strong class="text-h6 font-weight-black ml-2 primary--text">Problema reclamado</strong>
        </v-card-title>
        <v-card-text>
          <v-textarea
            :readonly="!canSave"
            filled
            dense
            v-model="order.complaint"
            @input="order.complaint = order.complaint.toUpperCase()"
          ></v-textarea>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- Modal Comments -->
    <v-dialog v-model="modalComments.show" max-width="800px">
      <v-card>
        <v-card-title>
          <v-icon color="primary">mdi-comment-text-outline</v-icon>
          <strong class="text-h6 font-weight-black ml-2 primary--text">Anotações</strong>
        </v-card-title>
        <v-card-text>
          <v-textarea
            :readonly="!canSave"
            filled
            dense
            v-model="order.comments"
            @input="order.comments = order.comments.toUpperCase()"
          ></v-textarea>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- Modal Work Found -->
    <v-dialog v-model="modalWorkFound.show" max-width="800px">
      <v-card>
        <v-card-title>
          <v-icon color="primary">mdi-comment-processing-outline</v-icon>
          <strong class="text-h6 font-weight-black ml-2 primary--text">Problema constatado</strong>
        </v-card-title>
        <v-card-text>
          <v-textarea
            :readonly="!canSave"
            filled
            dense
            v-model="order.work_found"
            @input="order.work_found = order.work_found.toUpperCase()"
          ></v-textarea>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- Modal Work Done -->
    <v-dialog v-model="modalWorkDone.show" max-width="800px">
      <v-card>
        <v-card-title>
          <v-icon color="green">mdi-comment-check-outline</v-icon>
          <strong class="text-h6 font-weight-black ml-2 primary--text">Trabalho realizado</strong>
        </v-card-title>
        <v-card-text>
          <v-textarea
            :readonly="!canSave"
            filled
            dense
            v-model="order.work_done"
            @input="order.work_done = order.work_done.toUpperCase()"
          ></v-textarea>
        </v-card-text>
      </v-card>
    </v-dialog>

   <!-- Modal Installment -->
    <v-dialog v-model="modalInstallment.show" max-width="800">
      <v-card>
        <v-card-title>
          <v-icon color="primary" small>mdi-cash-multiple</v-icon>
          <span class="font-weight-bold text-h5 ml-3">Pagamento</span>

          <v-spacer></v-spacer>

          <v-chip
            v-if="modalInstallment.editedItem.status"
            class="d-flex justify-center mt-2"
            label
            :color="modalInstallment.editedItem.status | paymentInstallmentStatusColor"
            small
          >
            {{ modalInstallment.editedItem.status }}
            <v-icon class="ml-2">{{ modalInstallment.editedItem.status | paymentInstallmentStatusIcon }}</v-icon>
          </v-chip>
        </v-card-title>

        <v-card-text class="py-5">
          <v-row>
            <v-col cols="12" md="6">
               <v-text-field
                filled
                prefix="R$"
                type="number"
                dense
                label="VALOR"
                v-model="modalInstallment.editedItem.value"
                clearable
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                :items="payments"
                label="FORMA DE PAGAMENTO"
                filled
                dense
                v-model="modalInstallment.editedItem.payment_method"
                clearable
              ></v-select>
            </v-col>

            <v-col cols="12" md="6">
              <v-dialog
                v-model="menu_date_installments_pay_day"
                :close-on-content-click="false"
                max-width="290"
                transition="scale-transition"
                offset-y
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    :value="modalInstallment.editedItem.pay_day | formatDMY"
                    label="DATA PAGAMENTO"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    filled
                    dense
                    clearable
                    @click:clear="modalInstallment.editedItem.pay_day = null"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="modalInstallment.editedItem.pay_day"
                  @change="menu_date_installments_pay_day = false"
                  scrollable
                  no-title
                  crollable
                  locale="pt-Br"
                  color="primary"
                ></v-date-picker>
              </v-dialog>
            </v-col>

            <v-col cols="12" md="6">
              <v-dialog
                v-model="menu_date_installments_due_date"
                :close-on-content-click="false"
                max-width="290"
                transition="scale-transition"
                offset-y
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    :value="modalInstallment.editedItem.due_date | formatDMY"
                    label="DATA VENCIMENTO"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    filled
                    dense
                    clearable
                    @click:clear="modalInstallment.editedItem.due_date = null"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="modalInstallment.editedItem.due_date"
                  @change="menu_date_installments_due_date = false"
                  scrollable
                  no-title
                  crollable
                  locale="pt-Br"
                  color="primary"
                ></v-date-picker>
              </v-dialog>
            </v-col>
          </v-row>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="btn-primary" class="rounded-lg" small text @click="modalInstallment.show = false">
            Cancelar
          </v-btn>
          <v-btn color="btn-primary" class="rounded-lg" small dark @click="handleInstallment">
            Salvar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!--Modal Status -->
    <v-dialog v-model="modalStatus" max-width="800px">
      <v-card>
        <v-card-title>
          <v-icon color="primary" small>mdi-map-marker-radius</v-icon>
          <span class="font-weight-bold text-h5 ml-3">Qual a situação do pedido?</span>
        </v-card-title>

        <v-card-text>
          <v-hover v-slot="{ hover }" v-for="status in statuses" :key="`status-${status}`" style="cursor: pointer;">
            <v-card
              @click="order.status = status"
              :elevation="hover ? 12 : 0"
              class="my-2"
              :color="status | statusColorHover(hover || order.status === status)"
            >
              <v-card-text class="d-flex justify-space-between">
                <strong :class="hover || order.status === status ? 'black--text' :'primary--text'">{{ status }}</strong>
                <v-icon :color="status | statusColorHover(!hover && order.status !== status, 'black')">{{ status | statusIcon }}</v-icon>
              </v-card-text>
            </v-card>
          </v-hover>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="btn-primary" class="rounded-lg" small text @click="modalStatus = false">
            Cancelar
          </v-btn>
          <v-btn color="btn-primary" class="rounded-lg" small dark @click="_store">
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
  data: () => ({
    modalClient: {
      show: false,
      search: '',
      client: {
        id: null,
        quantity: 1,
        value: 0,
      }
    },
    modalAddress: {
      show: false,
    },
    modalService: {
      show: false,
      editedIndex: false,
      editedItem: {
        id: null,
        quantity: 1,
        value: 0,
        total_value: 0
      }
    },
    modalProduct: {
      show: false,
      editedIndex: false,
      editedItem: {
        id: null,
        quantity: 1,
        value: 0,
        total_value: 0
      }
    },
    modalInstallment: {
      show: false,
      editedIndex: false,
      editedItem: {
          value: 0,
          payment_method: '',
          pay_day: '',
          due_date: '',
          status: '',
      }
    },
    modalComplaint: {
      show: false
    },
    modalComments: {
      show: false
    },
    modalWorkFound: {
      show: false
    },
    modalWorkDone: {
      show: false
    },

    tab: 2,

    modalStatus: false,
    loading: false,
    loadingClients: false,
    loadingProducts: false,
    loadingServices: false,
    loadingAddresses: false,
    menu_technical_visit_date: false,
    menu_time: false,
    menu_date_installments_pay_day: false,
    menu_date_installments_due_date: false,
    errors: {
      client_id: false,
    },
    rules: {
      required: value => !!value || 'Campo obrigatório.',
    },
    order: {
      id: null,
      description : null,
      comments: '',
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
      status: '',
      address_id: null,
      technician_id: null,
      accepted_payment_methods: 'PIX,DINHEIRO,CARTÃO DÉBITO,CARTÃO CRÉDITO',
      complaint: '',
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
    headersTablesItems: [
      { text: 'Descrição', value: 'name' },
      { text: 'Quantidade', value: 'quantity' },
      { text: 'Valor unit.', value: 'value' },
      { text: 'Valor total', value: 'total_value' },
      { text: 'Actions', value: 'actions', sortable: false }
    ],
    headersTablePayments: [
      { text: 'Valor', value: 'value' },
      { text: 'Forma de pagamento', value: 'payment_method' },
      { text: 'Data de pagamento', value: 'pay_day' },
      { text: 'Date de vencimento', value: 'due_date' },
      { text: 'status', value: 'status' },
      { text: 'Actions', value: 'actions', sortable: false }
    ],
    statuses: [
      'AGUARDANDO APROVAÇÃO',
      'EM ANDAMENTO',
      'CONCLUÍDO',
      'CANCELADO',
    ],
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
  }),
  computed: {
    titlePage(){
      return this.$route.params.id ? `Editar Pedido | nº ${this.$route.params.id}` : 'Adicionar Pedido';
    },
    canSave(){
      return this.$can('order_add') && !this.idByRoute || this.$can('order_update') && Boolean(this.idByRoute);
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
    clientsSearch() {
      return this.modalClient.search ? this.clients.filter(client => client.name.includes(this.modalClient.search.toUpperCase())) : [];
    },
    showFinanceTab() {
      return this.order.status.includes('EM ANDAMENTO') || this.order.status.includes('CONCLUÍDO');
    }
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
  watch: {
    'modalInstallment.editedItem': {
      deep: true,
      handler() {
        this.setStatusPayment();
      }
    },
    'order.installments': {
      deep: true,
      handler() {
        this.calculeAmoutPaid();

        let filterUnpaid = this.order.installments.filter(installment => installment.status === 'INADIMPLENTE');

        if(filterUnpaid.length > 0) {
          return this.order.payment_status = 'INADIMPLENTE';
        }

        let filterOpen = this.order.installments.filter(installment => installment.status === 'EM ABERTO');

        if(filterOpen.length > 0) {
          return this.order.payment_status = 'EM ABERTO';
        }

        return this.order.payment_status = 'PAGO TOTAL';
      }
    }
  },
  mounted(){
    this._start();
  },
  methods: {
    async _start(){
      if(this.idByRoute){
        await this._load();
      }
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
      if(!this.order.client_id) {
        return this.addresses = [];
      }

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
    async _loadServices(){
      this.loadingServices = true;

      await axios
              .get(`/api/service`)
              .then(response => {
                  if(response.data.success){
                    this.services = response.data.data;
                  } else {
                    this.$refs.fireDialog.error({ title: 'Error ao carregar serviços' })
                  }
              })
              .catch(error => {
                this.$refs.fireDialog.error({ title: 'Error ao carregar serviços' })
              })
              .finally(() => {
                this.loadingServices = false;
              });
    },
    async _loadProducts(){
      this.loadingProducts = true;

      await axios
              .get(`/api/product`)
              .then(response => {
                if(response.data.success){
                  this.products = response.data.data;
                } else {
                  this.$refs.fireDialog.error({ title: 'Error ao carregar produtos' })
                }
              })
              .catch(error => {
                this.$refs.fireDialog.error({ title: 'Error ao carregar produtos' })
              })
              .finally(() => {
                this.loadingProducts = false;
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
    setStatusPayment() {
      if(this.modalInstallment.editedItem.pay_day) {
        return this.modalInstallment.editedItem.status = 'PAGO';
      }

      let dueDate = moment(this.modalInstallment.editedItem.due_date);

      if(dueDate.diff(moment(), 'days') < 0) {
        return this.modalInstallment.editedItem.status = 'INADIMPLENTE';
      }

      return this.modalInstallment.editedItem.status = 'EM ABERTO';
    },
    calculeAmoutPaid() {
      let amountPaid = 0;
      this.order.installments.forEach(installment => amountPaid += installment.status === 'PAGO' ? parseFloat(installment.value) : 0);
      this.order.amount_paid = isNaN(amountPaid) ? (0).toFixed(2) : amountPaid.toFixed(2);
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
      this.modalStatus = false;

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
    calcDiscontAmount() {
      if(this.order.discount_percentage > 100) {
        this.order.discount_percentage = 100;
      }

      let value = this.valueTotal * (this.order.discount_percentage / 100);
      this.order.discount_amount = value.toFixed(2);
    },
    calcPercentageAmount() {
      if(parseFloat(this.order.discount_amount) > this.valueTotal) {
        this.order.discount_amount = this.valueTotal;
      }

      let value = (this.order.discount_amount * 100) / this.valueTotal;
      this.order.discount_percentage = value.toFixed(2);
    },

    // Modal Client
    showModalClient(client = null) {
      this._loadClients();
      if(client) {
        this.modalClient.client = client;
      }
      this.modalClient.show = true;
    },
    chooseClient(client) {
      this.order.client_id = client.id;
      this.order.client = client;
      this.modalClient.show = false;
      this.modalClient.search = '';
    },

    // Modal Address
    showModalAddress() {
      this._loadAddresses();
      this.modalAddress.show = true;
    },
    chooseAddress(address) {
      this.order.address = { ...address };
    },

    // Modal Service
    showModalService(service = {quantity: 1, value: 0, id: null}) {
      this._loadServices();
      this.modalService.editedIndex = this.order.services.indexOf(service);
      this.modalService.editedItem = Object.assign({}, service);
      this.modalService.show = true;
    },
    handleService() {
      if (this.modalService.editedIndex > -1) {
        Object.assign(this.order.services[this.modalService.editedIndex], this.modalService.editedItem)
      } else {
        let filterService = this.services.filter(service => service.id === this.modalService.editedItem.id);

        this.order.services.push({
          name: filterService[0].name,
          ...this.modalService.editedItem
        });
      }
      this.modalService.show = false;
    },
    async deleteService(service) {
      let deleteIndex = this.order.services.indexOf(service);

      const ok = await this.$refs.fireDialog.confirm({
          title: `Deletar serviço`,
          message: `Se aceitar, você irá retirar ${service.name} do pedido. Deseja continuar?`,
          textConfirmButton: 'Confirmar',
          colorConfirButton: 'btn-delete',
          colorCancelButton: 'btn-primary'
      })

      if (ok) {
        this.order.services.splice(deleteIndex, 1);
      }
    },
    addDefaultValueService(){
      let filterService = this.services.filter(service => service.id === this.modalService.editedItem.id);

      if(filterService[0] && filterService[0].default_value){
        this.modalService.editedItem.value = filterService[0].default_value.toFixed(2);
      } else {
        this.modalService.editedItem.value = 0;
      }
    },

    // Modal Product
    showModalProduct(product = {quantity: 1, value: 0, id: null}) {
      this._loadProducts();
      this.modalProduct.editedIndex = this.order.products.indexOf(product);
      this.modalProduct.editedItem = Object.assign({}, product);
      this.modalProduct.show = true;
    },
    handleProduct() {
      if (this.modalProduct.editedIndex > -1) {
        Object.assign(this.order.products[this.modalProduct.editedIndex], this.modalProduct.editedItem)
      } else {
        let filterProduct = this.products.filter(product => product.id === this.modalProduct.editedItem.id);

        this.order.products.push({
          name: filterProduct[0].name,
          ...this.modalProduct.editedItem
        });
      }
      this.modalProduct.show = false;
    },
    async deleteProduct(product) {
      let deleteIndex = this.order.products.indexOf(product);

      const ok = await this.$refs.fireDialog.confirm({
          title: `Deletar produto`,
          message: `Se aceitar, você irá retirar ${product.name} do pedido. Deseja continuar?`,
          textConfirmButton: 'Confirmar',
          colorConfirButton: 'btn-delete',
          colorCancelButton: 'btn-primary'
      })

      if (ok) {
        this.order.products.splice(deleteIndex, 1);
      }
    },
    addDefaultValueProduct(){
      let filterProduct = this.products.filter(product => product.id === this.modalProduct.editedItem.id);

      if(filterProduct[0] && filterProduct[0].default_value){
        this.modalProduct.editedItem.value = filterProduct[0].default_value.toFixed(2);
      } else {
        this.modalProduct.editedItem.value = 0;
      }
    },

    // Modal Payments/Installments
    showModalInstallment(installment = {value: 0, id: null}) {
      this.modalInstallment.editedIndex = this.order.installments.indexOf(installment);
      this.modalInstallment.editedItem = Object.assign({}, installment);
      this.modalInstallment.show = true;
    },
    handleInstallment() {
      if (this.modalInstallment.editedIndex > -1) {
        Object.assign(this.order.installments[this.modalInstallment.editedIndex], this.modalInstallment.editedItem)
      } else {
        this.order.installments.push(this.modalInstallment.editedItem);
      }
      this.modalInstallment.show = false;
    },
    async deleteInstallment(installment) {
      let deleteIndex = this.order.installments.indexOf(installment);

      const ok = await this.$refs.fireDialog.confirm({
          title: `Deletar pagamento`,
          message: `Se aceitar, você irá retirar este pagamento do pedido. Deseja continuar?`,
          textConfirmButton: 'Confirmar',
          colorConfirButton: 'btn-delete',
          colorCancelButton: 'btn-primary'
      })

      if (ok) {
        this.order.installments.splice(deleteIndex, 1);
      }
    },
  }

}
</script>
