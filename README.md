# laravel_vue_project

Nome: Sidgley de Almeida Guedes

requisitos:

Docker: https://docs.docker.com/desktop/windows/install/

Laradock: https://github.com/laradock/laradock

Node: https://nodejs.org/en/download/

ATENÇÃO: DEVIDO A UTILIZAÇÃO DO DOCKER, A REQUISIÇÃO DE DADOS FICA UM POUCO MAIS LENTA, ENTÃO QUANDO EXECUTAR ALGUMA AÇÃO, AGUARDE ATÉ O CARREGAMENTO EX: (combo de clientes, mudança de pagina, add produto no carrinho).

PARA INSERÇÃO DE PRODUTOS NO CARRINHO, SELECIONE O PRODUTO E A QUANTIDADE, APÓS ISSO O PRODUTO SERÁ ADICIONADO AUTOMATICAMENTE AO CARRINHO.


1 - faça o clone do repositório

2 - clone o repositório do laradock dentro da pasta laradock da aplicação (laravel_vue_project/php_test_sidgley/laradock/arquivos laradock aqui

3 - navegue até a pasta laradock e faça uma copia do arquivo .env.example e renomeie para .env

4 - também faça uma copia do arquivo .env.example e renomeie para .env na pasta do projeto php (php_test_sidgley)

5 - abra o arquivo .env dentro de php_test_sidgley e deixe configurado da mesma forma que o exemplo abaixo:

	DB_CONNECTION=mysql
 	DB_HOST=mysql
	DB_PORT=3306
	DB_DATABASE=teste_db
	DB_USERNAME=root
	DB_PASSWORD=root

6 - execute o Docker que você instalou em sua maquina e deixe-o rodando em segundo plano.

7 - com um terminal (gitbash, cmdr etc...), navegue até a pasta do laradock e quando estiver dentro dela digite os comandos:
    
	docker-compose up -d mysql nginx phpmyadmin

 (isso levará um tempinho, caso você não tenha esses containers em sua maquina ainda)

8 - Quando finalizado rode o comando: 
	
	"docker ps"

 (para verificar se todos os containers estão rodando normalmente (ou abra o docker no desktop e verifique manualmente))

10 - após isso, ainda dentro da pasta laradock, digite o comando:

	"docker-compose exec workspace bash"

dessa forma entraremos dentro do ambiente linux para trabalhar com nosso projeto.(root@0601b3f98a48:/var/www#)

11 - dentro do workspace digite o comando:
	
	"composer install" e após finalizado "php artisan key:generate" 

para instalar as dependencias do nosso projeto PHP e criar nosso APP_KEY

12 - após isso vamos criar nosso database acessando o phpmyadmin ou qualquer gerenciador de banco de sua preferencia
	o phpmyadmin por padrão roda na porta 8081 no laradock então vamos acessar http://localhost:8081/
	servidor: mysql
	user: root
	password: root

13 - crie um database com o nome  "teste_db" e com o mesmo "collation" que seu projeto laravel => 'utf8mb4_unicode_ci',

14 - agora podemos rodar nossas migrations na seguinte ordem:

	- php artisan migrate --path=/database/migrations/2014_10_12_000000_create_users_table.php
	- php artisan migrate --path=/database/migrations/2022_06_11_143133_create_clientes_table.php
	- php artisan migrate --path=/database/migrations/2022_06_11_143938_create_produtos_table.php
	- php artisan migrate --path=/database/migrations/2022_06_11_172920_create_status_pedidos_table.php
	- php artisan migrate --path=/database/migrations/2022_06_11_145721_create_pedidos_table.php
	- php artisan migrate --path=/database/migrations/2022_06_11_150810_create_pedido_items_table.php

15 - rode o comando "php artisan db:seed" se atentando aos relacionamentos

16 - navegue até a pasta do layout (php_test_layout) e rode o comando
	
	"npm install"

  para instalar as dependências do projeto

17 - por fim rode o comando "npm run dev" para executar o projeto do front.

18 - Para tudo funcionar, os containers do docker devem estar todos sendo executados e o front não deve ser executado na porta 4000 que é a padrão
pois da conflito com o Node.


Considerações: Devido a falta de tempo no dia-a-dia não fiz muitas validações de campo ou formatei moeda por exemplo. são coisas simples de fazer.
Não estou familiarizado com laravel ainda e design patterns, então foi no modo salsichão mesmo. Também não fiz a parte de edição de PEDIDOS, pois o prazo que me foi
dado esgotou. Porém, a lógica é a mesma para inserção dos pedidos.
