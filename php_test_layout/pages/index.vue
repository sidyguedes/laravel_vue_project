<template>
  <v-row justify="center" align="center">
    <v-col cols="12" sm="8" md="12" class="mt-16">
      <v-btn @click="modalCliente = !modalCliente" color="rgb(9 81 124)" class="ma-3 white--text d-flex ml-auto">
        Inserir Cliente
        <v-icon right dark>
          mdi-plus
        </v-icon>
      </v-btn>
      <v-card>
        <v-card-title>
          <span class="font-weight-bold grey--text headline">{{title}}</span>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line hide-details>
          </v-text-field>
        </v-card-title>
        <v-data-table class="mt-10"
        :hide-default-footer="true"
          sort-by="nomeCliente"
          must-sort
          :footer-props="{'items-per-page-text':'Clientes por pagina'}"
          :loading="!clientes.length" loading-text="Carregando... Por favor aguarde" item-key="name" :headers="headers"
          :items="clientes" :search="search">
          <template v-slot:item="{ item }">
            <tr>
              <td class="pa-4"> {{ item.nomeCliente }} {{item.sobrenomeCliente }} </td>
              <td class="pa-0"> {{ item.cpf }} </td>
              <td class="pa-0"> {{ item.email }} </td>
              <td class="pa-0">
                <v-btn class="mx-2" icon dark small color="primary" @click.prevent="getCliente(item.id)">
                  <v-icon dark>mdi-pencil</v-icon>
                </v-btn>
                <v-btn class="mx-2" icon dark small color="red" @click.prevent="deletarCliente(item.id)">
                  <v-icon dark>mdi-delete</v-icon>
                </v-btn>
              </td>
            </tr>
          </template>
        </v-data-table>
        <v-pagination
        class="mt-5"
           v-model="pagination.current"
           :length="pagination.total"
           @input="onPageChange"
        ></v-pagination>
      </v-card>
      <v-dialog :scrollable="false" transition="dialog-top-transition" v-model="modalCliente" max-width="600px">
        <v-card>
          <v-card-title>
            <span class="text-h5 pa-5">{{modalTitle}}</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-progress-circular class="d-flex mx-auto" v-if="dadosEnviados" :size="50" color="primary" indeterminate></v-progress-circular>
              <v-row v-if="!dadosEnviados">
                <v-col cols="12" sm="6" md="6">
                  <v-text-field v-model="nomeCliente" label="Nome*" required></v-text-field>
                </v-col>
                <v-col cols="12" sm="6" md="6">
                  <v-text-field v-model="sobrenomeCliente" label="Sobrenome*" hint="ex: Da Silva" persistent-hint
                    required>{{this.nomeCliente}}</v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field v-model="cpfCliente" label="CPF*" required></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field v-model="emailCliente" label="email*" type="email" required></v-text-field>
                </v-col>
              </v-row>
            </v-container>
            <small class="pa-6">*Campos obrigatórios</small>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="clearVariaveis()">
              Fechar
            </v-btn>
            <v-btn color="blue darken-1" text @click="inserirCliente(btnModalTitle)">
              {{btnModalTitle}}
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog :scrollable="false" transition="dialog-top-transition" v-model="modalConfirmaExclusao"
        max-width="400px">
        <v-card>
          <v-card-title class="text-h5">
          </v-card-title>
          <v-card-text class="d-flex">
            <span v-if="!dadosExcluidos" class="text-h5 d-flex mt-6">Tem certeza que deseja excluir? </span>
            <v-progress-circular class="d-flex mx-auto mt-6" v-if="dadosExcluidos" :size="50" color="primary"
              indeterminate></v-progress-circular>
          </v-card-text>
          <v-card-text>
          </v-card-text>
          <v-card-actions v-if="!dadosExcluidos">
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="modalConfirmaExclusao = !modalConfirmaExclusao" text>
              Cancelar
            </v-btn>
            <v-btn @click.prevent="deletarCliente('excluir')" color="error" text>
              Sim
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-col>
  </v-row>
</template>

<script>
  const axios = require('axios').default;
  export default {
    data() {
      return {
        pagination: {
          current: 1,
          total: 0
        },
        modalTitle: 'Inserir Novo Cliente',
        btnModalTitle: 'Cadastrar',
        dadosExcluidos: false,
        idCliente: null,
        dadosEnviados: false,
        search: '',
        title: 'Lista de Clientes',
        clientes: [],
        nomeCliente: null,
        sobrenomeCliente: null,
        cpfCliente: null,
        emailCliente: null,
        modalCliente: false,
        modalConfirmaExclusao: false,
        itensPorPagina: null,
        cliente: {},
        headers: [{
            text: 'Nome',
            align: 'start',
            sortable: true,
            value: 'nomeCliente',
          },
          {
            text: 'CPF',
            value: 'cpf',
            sortable: true,
          },
          {
            text: 'Email',
            value: 'email',
            sortable: true,
          },
          {
            text: 'Ações',
            sortable: false,
          },
        ],
      }
    },
    created() {
      this.getClientes();
    },

    methods: {
      getClientes() {
        var context = this;
        axios.get('http://localhost/api/clientes?page='+ context.pagination.current)
          .then(function (response) {
          context.clientes = response.data.data;
          console.log(response.data);
          context.pagination.current = response.data.current_page;
          context.pagination.total = response.data.last_page;

        })
        .catch(function (error) {
          // handle error
          console.log(error);
        })
        .then(function () {
          // always executed
        });
      },

      getCliente(val) {
        this.idCliente = val;
        this.btnModalTitle = 'Atualizar';
        this.modalTitle = 'Atualizar Cliente';
        var context = this;
        this.modalCliente = true;
        this.dadosEnviados = true;

        axios.get('http://localhost/api/clientes/'+val)
        .then(function (response) {

          context.dadosEnviados = false;

          context.cliente = response.data;

          context.nomeCliente = context.cliente.nomeCliente;
          context.sobrenomeCliente = context.cliente.sobrenomeCliente;
          context.cpfCliente = context.cliente.cpf;
          context.emailCliente = context.cliente.email;
        })
        .catch(function (error) {
          // handle error
          console.log(error);
        })
        .then(function () {

        });
      },

      deletarCliente(param) {
        if (param != 'excluir') {
          this.idCliente = param;
        }
        this.modalConfirmaExclusao = true;

        if (param == 'excluir') {
          this.dadosExcluidos = true;
          axios.delete('http://localhost/api/clientes/'+this.idCliente)
            .then(response => {
            this.getClientes();

            console.log(response);
            this.dadosExcluidos = false;
            this.modalConfirmaExclusao = false;
          });
        }

      },

      inserirCliente(param) {
        console.log('aqui', param);
        this.btnModalTitle = 'Cadastrar';
        this.dadosEnviados = true;

        if (param == 'Cadastrar') {
          const dados = {
            nomeCliente: this.nomeCliente,
            sobrenomeCliente: this.sobrenomeCliente,
            cpf: this.cpfCliente,
            email: this.emailCliente,
          };
          axios.post("http://localhost/api/clientes/", dados)
          .then(response => {
            alert('Dados inseridos com sucesso!');
            this.nomeCliente = '',
            this.sobrenomeCliente = '',
            this.cpfCliente = '',
            this.emailCliente = '',
            this.modalCliente = !this.modalCliente;
            this.articleId = response.data.id;

            this.dadosEnviados = false;
            this.getClientes();
          })
          .catch(error => {
            this.errorMessage = error.message;
            console.error("Verifique os dados e tente novamente", error);
          });
        }

        if (param == 'Atualizar') {
          const dados = {
            nomeCliente: this.nomeCliente,
            sobrenomeCliente: this.sobrenomeCliente,
            cpf: this.cpfCliente,
            email: this.emailCliente,
          };
          axios.put("http://localhost/api/clientes/"+this.idCliente, dados)
          .then(response => {
            this.modalCliente = !this.modalCliente;
            alert('Dados atualizados com sucesso!');
            console.log(response);
            this.getClientes();
          })
          .catch(error => {
            this.errorMessage = error.message;
            console.error("Verifique os dados e tente novamente", error);

          });
        }
      },
      onPageChange() {
        this.getClientes();
      },

      clearVariaveis(){
        this.modalTitle = 'Inserir Cliente';
        this.btnModalTitle = 'Cadastrar';
        this.modalCliente = !this.modalCliente;
        this.nomeCliente = null;
        this.sobrenomeCliente = null;
        this.cpfCliente = null;
        this.emailCliente = null;
      }
    },
  }

</script>
<style scoped lang="scss">
  .v-data-table::v-deep th {
    font-size: 15px !important;
    background-color: rgb(9 81 124) !important;
    color: #ffffff !important;
  }

</style>
