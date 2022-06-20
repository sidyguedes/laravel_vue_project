<template>
  <v-row justify="center" align="center">
    <v-col cols="12" sm="8" md="12" class="mt-16">
      <v-btn @click="modalPedido = !modalPedido" color="#06283D" class="ma-3 white--text d-flex ml-auto">
        Novo Pedido
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
        <v-data-table class="mt-10" :hide-default-footer="true" sort-by="nomeCliente" must-sort
          :loading="false"
          loading-text="Carregando... Por favor aguarde" item-key="name" :headers="headers" :items="pedidos"
          :search="search">
          <template v-slot:item="{ item }">
            <tr>
              <td class="pa-4">Sidy Guedes</td>
              <td class="pa-4">084.134.969-00</td>
              <td class="pa-4">sidgley_guedes@hotmail.com</td>
              <td class="pa-0">11/07/2022</td>
              <td class="pa-0">Em aberto</td>
              <td class="pa-4"><a href="#">Ver Produtos</a></td>
              <td class="pa-0">
                <v-btn class="mx-2" icon dark small color="primary" @click.prevent="getPedido(item.id)">
                  <v-icon dark>mdi-pencil</v-icon>
                </v-btn>
                <v-btn class="mx-2" icon dark small color="red" @click.prevent="deletarPedido(item.id)">
                  <v-icon dark>mdi-delete</v-icon>
                </v-btn>
              </td>
            </tr>
          </template>
        </v-data-table>
        <v-pagination class="mt-5" v-model="pagination.current" :length="pagination.total" @input="onPageChange">
        </v-pagination>
      </v-card>
      <v-dialog :scrollable="false" transition="dialog-top-transition" v-model="modalPedido" max-width="600px">
        <v-card>
          <v-card-title>
            <span class="text-h5 pa-5">{{modalTitle}}</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-progress-circular class="d-flex mx-auto" v-if="dadosEnviados" :size="50" color="primary" indeterminate></v-progress-circular>
              <v-row v-if="!dadosEnviados">
                <v-col cols="12" sm="6" md="6">
                  <v-select :items="comboClientes" item-text="nomeCliente" v-model="selectedCliente" item-value="id" label="Selecione o Cliente" required></v-select>
                </v-col>
                <v-col cols="12" sm="6" md="6">
                  <v-select :items="statusPedido"  item-text="status" v-model="selectedStatus"  item-value="id" label="Selecione o status do pedido" required></v-select>
                </v-col>
                <v-col cols="12" sm="6" md="6">
                  <v-select :items="comboProdutos" item-text="nomeProduto" v-model="selectedProduto" item-value="id" label="Selecione o Produto" required ></v-select>
                </v-col>
                <v-col cols="12" sm="6" md="6" class="d-flex"> 
                  <v-select :items="qtdProdutos" v-model="selectedqtd" label="Quantidade" required @change="addProduto(selectedProduto)"></v-select>
                </v-col> 
                <v-col cols="10" sm="6" md="12" class="d-flex">
                  <ul id="listaItens">
                    <li v-for="pedido in pedidoItens" :key="pedido.id">
                      <span>{{pedido.nomeProduto}} | Quantidade: {{pedido.quantidade}}</span>
                      <v-btn icon dark small color="red" @click.prevent="removerProduto(pedido.idProduto)">
                        <v-icon dark>mdi-delete</v-icon>
                      </v-btn>
                       <v-divider></v-divider>
                    </li>
                  </ul>
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
            <v-btn color="blue darken-1" text @click="inserirPedido(btnModalTitle)">
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
            <v-btn @click.prevent="deletarPedido('excluir')" color="error" text>
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
    data: () => ({
      title: 'Lista Pedidos',
      selectedCliente: null,
      selectedStatus: null,
      selectedProduto: null,
      selectedqtd: null,
      modalPedido: false,
      modalTitle: 'Novo Pedido',
      btnModalTitle: 'Cadastrar',
      modalConfirmaExclusao: false,
      dadosEnviados: false,
      dadosExcluidos: false,
      search: null,
      pedidos: [],
      pedidoItens: [],
      comboClientes: [],
      comboProdutos: [],
      comboIdClientes: [],
      comboIdProdutos: [],
      statusPedido: [
        { id: 1, status: "Em aberto" },
        { id: 2, status: "pago" },
        { id: 3, status: "Cancelado" },
      ],
      pagination: {
        current: 1,
        total: 0
      },
      clientes: [],
      headers: [{
            text: 'Cliente',
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
            text: 'Data Pedido',
            value: 'dtPedido',
            sortable: true,
          },
          {
            text: 'Status',
            value: 'status',
            sortable: true,
          },
          {
            text: 'Produtos',
            value: 'idPedido',
            sortable: true,
          },
          {
            text: 'Ações',
            sortable: false,
          },
        ],
      qtdProdutos:['1','2','3','4','5','6','7','8','9','10'],

    }),

    mounted(){
      this.getClientes();
      this.getProdutos();
    },

    methods: {
      addProduto(id){
        var context = this;
        axios.get('http://localhost/api/produtos/'+id)
        .then(function (response) {

          context.produto = response.data;

          context.pedidoItens.push({nomeProduto : context.produto.nomeProduto, idProduto : context.selectedProduto, numeroPedido: Math.floor(Math.random() * 10), quantidade: parseInt(context.selectedqtd)});

        })
        .catch(function (error) {
          // handle error
          console.log(error);
        })
        .then(function () {
          
        });
      },

      removerProduto(idProduto){

        var index = this.pedidoItens.map(x => {
          return x.id;
        }).indexOf(idProduto);

        this.pedidoItens.splice(index, 1);

      },

      getClientes() {
        var context = this;
        axios.get('http://localhost/api/clientes?page='+ context.pagination.current)
          .then(function (response) {
          context.clientes = response.data.data;
          
          context.clientes.forEach(dados => {

            context.comboClientes.push({nomeCliente : dados.nomeCliente + ' ' + dados.sobrenomeCliente, id: dados.id});

          });

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

       getProdutos() {
        var context = this;
        axios.get('http://localhost/api/produtos?page='+ context.pagination.current)
          .then(function (response) {
          context.produtos = response.data.data;
          
          context.produtos.forEach(dados => {

            context.comboProdutos.push({nomeProduto : dados.nomeProduto, id: dados.id });
              
          });

          //console.log(context.comboIdProdutos);

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

      getDateTime(){
        var today = new Date();
        var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
        var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        var dateTime = date+' '+time;

        return dateTime;
      },
      getPedido() {
        console.log('ss');
      },
      deletarPedido(){
        console.log('sss');
      },
      onPageChange() {
        this.getProdutos();
      },
      clearVariaveis(){
        console.log('sss')
      },
      inserirPedido(btnTitle){
        this.btnModalTitle = 'Cadastrar';
        this.dadosEnviados = true;

        if (btnTitle == 'Cadastrar') {
          const dados = {
            idCliente: this.selectedCliente,
            idStatusPedido: this.selectedStatus,
            dtPedido: this.getDateTime(),
            itens: this.pedidoItens,
          };
          axios.post("http://localhost/api/pedidos/", dados)
          .then(response => {
            console.log(response)

            this.dadosEnviados = false;
            this.modalPedido = false;
            alert('Dados inseridos com sucesso!');

            this.selectedCliente = '';
            this.selectedStatus = '';
            this.pedidoItens = '';
            this.selectedqtd = '';
            this.selectedProduto = '';

            

          })
          .catch(error => {
            this.errorMessage = error.message;
            console.error("Verifique os dados e tente novamente", error);
          });
        }

        // if (btnTitle == 'Atualizar') {
        //   const dados = {
        //     nomeProduto: this.nomeProduto,
        //     valorUnitario: this.valorUnitario,
        //     codBarras: this.codBarras,

        //   };
        //   axios.put("http://localhost/api/produtos/"+this.idProduto, dados)
        //   .then(response => {
        //     this.modalProduto = !this.modalProduto;
        //     alert('Dados atualizados com sucesso!');
        //     console.log(response);
        //     this.getProdutos();
        //   })
        //   .catch(error => {
        //     this.errorMessage = error.message;
        //     console.error("Verifique os dados e tente novamente", error);
            
        //   });
        // }
      }

    }
  }

</script>
