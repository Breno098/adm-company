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
          @click="showModalStatus"
          v-if="canSave"
          :loading="loading"
        >
          Salvar <v-icon small class="ml-2">mdi-content-save</v-icon>
        </v-btn>
      </v-col>
    </v-row>

     <v-card>
      <v-card-text>
        <v-row>
          <v-col cols="12" md="11">
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
              :error="errors.client_id && !order.client_id"
              :no-data-text="loadingClients ? 'Carregando clientes...' : 'Nenhum cliente encontrado'"
            ></v-autocomplete>
          </v-col>

          <v-col cols="12" md="1">
            <v-btn
              color="btn-primary"
              class="rounded-lg"
              block
              text
              @click="modalAddress = true"
            >
              <v-icon>mdi-map-marker-radius</v-icon>
            </v-btn>
          </v-col>
        </v-row>

        <v-expansion-panels
          flat
          multiple
          v-model="panels"
        >
          <!-- Basic Informations -->
          <v-expansion-panel>
            <v-divider class="mx-7 mt-2"></v-divider>

            <v-expansion-panel-header class="px-2">
              <div class="text-h6 font-weight-bold primary--text">
                <v-icon small color="primary">mdi-information</v-icon>
                Informações básicas
              </div>
            </v-expansion-panel-header>

            <v-divider class="mx-7"></v-divider>

            <v-expansion-panel-content class="pt-2">
              <v-row>
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
                          prepend-inner-icon="mdi-calendar"
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
                        prepend-inner-icon="mdi-clock-time-four-outline"
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
                  <v-autocomplete
                    v-model="order.technician_id"
                    :items="employees"
                    item-value="id"
                    item-text="name"
                    label="RESPONSÁVEL TÉCNICO"
                    :loading="loadingEmployees"
                    filled
                    dense
                    no-data-text="Nenhum técnico encontrado"
                  >
                    <template v-slot:selection="data">
                      {{ data.item.name }}
                      <small v-if="data.item.position_id" class="ml-3">
                        ({{ data.item.position.name }})
                      </small>
                    </template>
                    <template v-slot:item="data">
                        <v-list-item-content>
                          <v-list-item-title v-html="data.item.name"></v-list-item-title>
                          <v-list-item-subtitle
                            v-if="data.item.position_id"
                            v-html="data.item.position.name"
                          ></v-list-item-subtitle>
                        </v-list-item-content>
                    </template>
                  </v-autocomplete>
                </v-col>
              </v-row>
            </v-expansion-panel-content>
          </v-expansion-panel>

          <!-- Notes and comments -->
          <v-expansion-panel>
            <v-divider class="mx-7 mt-2"></v-divider>

            <v-expansion-panel-header class="px-2">
              <div class="text-h6 font-weight-bold primary--text">
                <v-icon small color="primary">mdi-comment-text-outline</v-icon>
                Anotações
              </div>
            </v-expansion-panel-header>

            <v-divider class="mx-7"></v-divider>

            <v-expansion-panel-content class="pt-2">
              <v-textarea
                label="COMENTÁRIOS E ANOTAÇÔES"
                filled
                dense
                v-model="order.comments"
                :loading="loading"
                hint="COMENTÁRIOS E ANOTAÇÔES INTERNOS"
                @input="order.comments = order.comments.toUpperCase()"
              ></v-textarea>
            </v-expansion-panel-content>
          </v-expansion-panel>

          <!-- Services -->
          <v-expansion-panel>
            <v-divider class="mx-7 mt-2"></v-divider>

            <v-expansion-panel-header class="px-2">
              <div class="text-h6 font-weight-bold primary--text">
                <v-icon small color="primary">mdi-wrench</v-icon>

                <v-badge
                  color="primary"
                  :content="order.services.length"
                  :value="order.services.length"
                >
                  Serviços
                </v-badge>
              </div>
            </v-expansion-panel-header>

            <v-divider class="mx-7"></v-divider>

            <v-expansion-panel-content class="pt-2">
              <v-row v-for="(service, index) in order.services" :key="`service-${index}`">
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

                <v-col cols="12" md="6">
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
                    <span> Para centavos, utilize ponto (.) ao invés de virgula (,) </span>
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

              <v-row>
                <v-col cols="12" class="d-flex flex-row justify-end">
                  <v-btn
                    color="btn-primary"
                    :loading="loading"
                    small
                    dark
                    class="rounded-lg"
                    @click="order.services.push({ quantity: 1, value: 0, default_value: 0, total_value: 0 })"
                  >
                    Adicionar serviço <v-icon small>mdi-plus</v-icon>
                  </v-btn>
                </v-col>
              </v-row>
            </v-expansion-panel-content>
          </v-expansion-panel>

          <!-- Products -->
          <v-expansion-panel>
            <v-divider class="mx-7 mt-2"></v-divider>

            <v-expansion-panel-header class="px-2">
              <div class="text-h6 font-weight-bold primary--text">
                <v-icon small color="primary">mdi-barcode</v-icon>

                <v-badge
                  color="primary"
                  :content="order.products.length"
                  :value="order.products.length"
                >
                  Produtos
                </v-badge>

              </div>
            </v-expansion-panel-header>

            <v-divider class="mx-7"></v-divider>

            <v-expansion-panel-content class="pt-2">
              <v-row v-for="(product, index) in order.products" :key="`product-${index}`">
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

                <v-col cols="12" md="6">
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
                    <span> Para centavos, utilize ponto (.) ao invés de virgula (,) </span>
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

              <v-row>
                <v-col cols="12" class="d-flex flex-row justify-end">
                  <v-btn
                    color="btn-primary"
                    :loading="loading"
                    small
                    dark
                    class="rounded-lg"
                    @click="order.products.push({ quantity: 1, value: 0, default_value: 0, total_value: 0 })"
                  >
                    Adicionar produto <v-icon small>mdi-plus</v-icon>
                  </v-btn>
                </v-col>
              </v-row>
            </v-expansion-panel-content>
          </v-expansion-panel>

          <!-- Values and discont -->
          <v-expansion-panel>
            <v-divider class="mx-7 mt-2"></v-divider>

            <v-expansion-panel-header class="px-2">
              <div class="text-h6 font-weight-bold primary--text">
                <v-icon small color="primary">mdi-calculator</v-icon>
                Valores e desconto
              </div>
            </v-expansion-panel-header>

            <v-divider class="mx-7"></v-divider>

            <v-expansion-panel-content class="pt-2">
              <v-row>
                  <v-col cols="12" md="6">
                  <v-text-field
                    prefix="%"
                    label="PORCENTAGEM DESCONTO"
                    type="number"
                    filled
                    dense
                    v-model="order.discount_percentage"
                    :loading="loading"
                    min="0"
                    max="100"
                    @keyup="calcDiscontAmount"
                    @click="calcDiscontAmount"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    prefix="R$"
                    label="VALOR DESCONTO"
                    type="number"
                    filled
                    dense
                    v-model="order.discount_amount"
                    :loading="loading"
                    min="0"
                    :max="valueTotal"
                    @keyup="calcPercentageAmount"
                    @click="calcPercentageAmount"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    filled
                    prefix="R$"
                    :value="valueTotal"
                    dense
                    label="VALOR BRUTO"
                    :loading="loading"
                    readonly
                    color="black"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    filled
                    prefix="R$"
                    :value="valueTotalWithDiscont"
                    dense
                    label="VALOR LIQUIDO"
                    :loading="loading"
                    readonly
                    color="black"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-expansion-panel-content>
          </v-expansion-panel>

          <!-- Payment Methods -->
          <v-expansion-panel>
            <v-divider class="mx-7 mt-2"></v-divider>

            <v-expansion-panel-header class="px-2">
              <div class="text-h6 font-weight-bold primary--text">
                <v-icon small color="primary">mdi-credit-card-multiple</v-icon>
                Meios de pagamento aceitas
              </div>
            </v-expansion-panel-header>

            <v-divider class="mx-7"></v-divider>

            <v-expansion-panel-content class="pt-2">
              <v-row>
                <v-col
                  cols="6"
                  md="3"
                  v-for="(payment, index) in payments"
                  :key="`payment-method-${index}`"
                >
                  <v-switch
                    :label="payment"
                    :value="payment"
                    v-model="paymentMethods"
                  ></v-switch>
                </v-col>
              </v-row>
            </v-expansion-panel-content>
          </v-expansion-panel>

          <!-- Reports -->
          <v-expansion-panel>
            <v-divider class="mx-7 mt-2"></v-divider>

            <v-expansion-panel-header class="px-2">
              <div class="text-h6 font-weight-bold primary--text">
                <v-icon small color="primary">mdi-book-open</v-icon>
                Relatórios
              </div>
            </v-expansion-panel-header>

            <v-divider class="mx-7"></v-divider>

            <v-expansion-panel-content class="pt-2">
              <v-textarea
                label="PROBLEMA RECLAMADO"
                filled
                dense
                v-model="order.complaint"
                :loading="loading"
                hint="ORDEM DE SERVIÇO"
                @input="order.complaint = order.complaint.toUpperCase()"
              ></v-textarea>

              <v-textarea
                label="SERVIÇO NECESSÁRIO"
                filled
                dense
                v-model="order.work_found"
                :loading="loading"
                hint="SERVIÇO NECESSÁRIO A SER FEITO"
                @input="order.work_found = order.work_found.toUpperCase()"
              ></v-textarea>

              <v-textarea
                label="SERVIÇO REALIZADO"
                filled
                dense
                v-model="order.work_done"
                :loading="loading"
                hint="SERVIÇO REALIZADO"
                @input="order.work_done = order.work_done.toUpperCase()"
              ></v-textarea>
            </v-expansion-panel-content>
          </v-expansion-panel>

          <!-- Warranty -->
          <v-expansion-panel>
            <v-divider class="mx-7 mt-2"></v-divider>

            <v-expansion-panel-header class="px-2">
              <div class="text-h6 font-weight-bold primary--text">
                <v-icon small color="primary">mdi-format-align-center</v-icon>
                Garantia
              </div>
            </v-expansion-panel-header>

            <v-divider class="mx-7"></v-divider>

            <v-expansion-panel-content class="pt-2">
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
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-card-text>

      <v-card-actions>
        <v-btn
          color="btn-primary"
          class="rounded-lg"
          small
          dark
          @click="showModalStatus"
          v-if="canSave"
          :loading="loading"
          block
        >
          Salvar <v-icon small class="ml-2">mdi-content-save</v-icon>
        </v-btn>
      </v-card-actions>
    </v-card>

    <!-- Modal Address -->
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
    loadingEmployees: false,
    addNotes: false,
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
    employees: [],
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
      await this._loadEmployees();
      await this._loadProducts();
      await this._loadServices();
    },
    calcDiscontAmount() {
      console.log(this.order.discount_percentage);
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
    async _loadEmployees(){
      this.loadingEmployees = true;

      let params = { relations: ['position'] };

      await axios
              .get(`/api/employee`, { params })
              .then(response => {
                if(response.data.success){
                  this.employees = response.data.data;
                } else {
                  this.$refs.fireDialog.error({ title: 'Error ao carregar técnicos' })
                }
              })
              .catch(error => {
                this.$refs.fireDialog.error({ title: 'Error ao carregar técnicos' })
              })
              .finally(() => {
                this.loadingEmployees = false;
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
    chooseAddress(address) {
      this.order.address = { ...address };
    }
  }

}
</script>
