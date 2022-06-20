<template>
  <v-row justify="center" align="center">
    <v-col cols="12" sm="8" md="12" class="mt-16">
      <v-btn @click="modalProduto = !modalProduto" color="rgb(9 81 124)" class="ma-3 white--text d-flex ml-auto">
        Inserir Produto
        <v-icon right dark>
          mdi-plus
        </v-icon>
      </v-btn>
      <v-card light color="white">
        <v-card-title>
          <span class="font-weight-bold grey--text headline">{{title}}</span>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line hide-details>
          </v-text-field>
        </v-card-title>
        <v-data-table class="mt-10"
          sort-by="nomeProduto"
          must-sort
          colo
          disable-pagination
          hide-default-footer
          :footer-props="{'items-per-page-text':'Produtos por pagina'}"
          :loading="!produtos.length" loading-text="Carregando... Por favor aguarde" item-key="name" :headers="headers"
          :items="produtos" :search="search">
          <template v-slot:item="{ item }">
            <tr>
              <td class="pa-4"> {{ item.nomeProduto }} </td>
              <td class="pa-0"> R$ {{ item.valorUnitario.toLocaleString('pt-BR', {style: 'currency',currency: 'BRL', minimumFractionDigits: 2})}} </td>
              <td class="pa-0"> {{ item.codBarras }} </td>
              <td class="pa-0">
                <v-btn class="mx-2" icon dark small color="primary" @click.prevent="getProduto(item.id)">
                  <v-icon dark>mdi-pencil</v-icon>
                </v-btn>
                <v-btn class="mx-2" icon dark small color="error" @click.prevent="deletarProduto(item.id)">
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
      <v-dialog :scrollable="false" transition="dialog-top-transition" v-model="modalProduto" max-width="600px">
        <v-card>
          <v-card-title>
            <span class="text-h5 pa-5">{{modalTitle}}</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-progress-circular class="d-flex mx-auto" v-if="dadosEnviados" :size="50" color="primary" indeterminate></v-progress-circular>
              <v-row v-if="!dadosEnviados">
                <v-col cols="12" sm="6" md="6">
                  <v-text-field v-model="nomeProduto" label="Nome do Produto*" required></v-text-field>
                </v-col>
                <v-col cols="12" sm="6" md="6">
                  <v-text-field v-model="valorUnitario" label="Preço*" hint="ex: 1.500,00" persistent-hint
                    required>{{this.nomeProduto}}</v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field v-model="codBarras" label="Código de Barras*" required></v-text-field>
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
            <v-btn color="blue darken-1" text @click="inserirProduto(btnModalTitle)">
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
            <v-btn @click.prevent="deletarProduto('excluir')" color="error" text>
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
        modalTitle: 'Inserir Novo Produto',
        btnModalTitle: 'Cadastrar',
        dadosExcluidos: false,
        idProduto: null,
        dadosEnviados: false,
        search: '',
        title: 'Lista de Produtos',
        produtos: [],
        nomeProduto: null,
        valorUnitario: null,
        codBarras: null,
        modalProduto: false,
        modalConfirmaExclusao: false,
        itensPorPagina: null,
        produto: {},
        headers: [{
            text: 'Produto',
            align: 'start',
            sortable: true,
            value: 'nomeProduto',
            class: 'headerclass',
            divider: true,
          },
          {
            text: 'Preço',
            align: 'start',
            value: 'valorUnitario',
            sortable: true,
            divider: true,
          },
          {
            text: 'Código de Barras',
            align: 'start',
            value: 'codBarras',
            sortable: true,
            divider: true,
          },
          {
            text: 'Ações',
            align: 'start',
            sortable: false,
            divider: true,
          },
        ],
      }
    },
    created() {
      this.getProdutos();
    },

    methods: {
      getProdutos() {
        var context = this;
        axios.get('http://localhost/api/produtos?page='+ context.pagination.current)
          .then(function (response) {
          context.produtos = response.data.data;
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

      getProduto(val) {
        this.idProduto = val;
        this.btnModalTitle = 'Atualizar';
        this.modalTitle = 'Atualizar Produto';
        var context = this;
        this.modalProduto = true;
        this.dadosEnviados = true;

        axios.get('http://localhost/api/produtos/'+val)
        .then(function (response) {

          context.dadosEnviados = false;

          context.produto = response.data;

          context.nomeProduto = context.produto.nomeProduto;
          context.valorUnitario = context.produto.valorUnitario;
          context.codBarras = context.produto.codBarras;
        })
        .catch(function (error) {
          // handle error
          console.log(error);
        })
        .then(function () {

        });
      },

      deletarProduto(param) {
        if (param != 'excluir') {
          this.idProduto = param;
        }
        this.modalConfirmaExclusao = true;

        if (param == 'excluir') {
          this.dadosExcluidos = true;
          axios.delete('http://localhost/api/produtos/'+this.idProduto)
            .then(response => {
            this.getProdutos();

            console.log(response);
            this.dadosExcluidos = false;
            this.modalConfirmaExclusao = false;
          });
        }

      },

      inserirProduto(param) {
        console.log('parametro', param);
        this.btnModalTitle = 'Cadastrar';
        this.dadosEnviados = true;

        if (param == 'Cadastrar') {
          const dados = {
            nomeProduto: this.nomeProduto,
            valorUnitario: this.valorUnitario,
            codBarras: this.codBarras,
          };
          axios.post("http://localhost/api/produtos/", dados)
          .then(response => {
            alert('Dados inseridos com sucesso!');

            this.nomeProduto = '',
            this.valorUnitario = '',
            this.codBarras = '',

            this.modalProduto = !this.modalProduto;
            this.articleId = response.data.id;

            this.dadosEnviados = false;
            this.getProdutos();
          })
          .catch(error => {
            this.errorMessage = error.message;
            console.error("Verifique os dados e tente novamente", error);
          });
        }

        if (param == 'Atualizar') {
          const dados = {
            nomeProduto: this.nomeProduto,
            valorUnitario: this.valorUnitario,
            codBarras: this.codBarras,

          };
          axios.put("http://localhost/api/produtos/"+this.idProduto, dados)
          .then(response => {
            this.modalProduto = !this.modalProduto;
            alert('Dados atualizados com sucesso!');
            console.log(response);
            this.getProdutos();
          })
          .catch(error => {
            this.errorMessage = error.message;
            console.error("Verifique os dados e tente novamente", error);

          });
        }
      },
      onPageChange() {
        this.getProdutos();
      },

      clearVariaveis(){
        this.modalTitle = 'Inserir Produto';
        this.btnModalTitle = 'Cadastrar';
        this.modalProduto = !this.modalProduto;
        this.nomeProduto = null;
        this.valorUnitario = null;
        this.codBarras = null;
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



  .theme--light.v-data-table .v-data-table-header th.sortable .v-data-table-header__icon {
    background-color: white !important;
  }

</style>
