# meu_projeto_docker - CRUD Pesqueiro

Este projeto configura uma aplicação PHP para gerenciamento de um Pesqueiro (CRUD para Estoque, Vendas, Usuários e Vendedores) utilizando Docker para o ambiente de desenvolvimento e o Supabase como backend de banco de dados.

## Estrutura do Projeto

meu_projeto_docker/
├── CRUD-Pesqueiro/                 # Contém todo o código-fonte da aplicação PHP
│   ├── config/                     # Configurações de conexão (ex: Supabase)
│   │   └── conexao.php
│   ├── controllers/                # Lógica de controle para diferentes módulos
│   │   ├── EstoqueController.php
│   │   ├── HomeController.php
│   │   ├── LoginController.php
│   │   ├── UsuarioController.php
│   │   ├── VendaController.php
│   │   └── VendedorController.php
│   ├── models/                     # Modelos de dados para interação com o Supabase
│   │   ├── EstoqueModel.php
│   │   ├── UsuarioModel.php
│   │   ├── VendaModel.php
│   │   └── VendedorModel.php
│   ├── system/                     # Arquivos de sistema (login, navbar, footer, mensagens, roteador)
│   │   ├── footer.php
│   │   ├── index.php               # Ponto de entrada e roteador principal
│   │   ├── mensagem.php
│   │   ├── navbar.php
│   │   └── verifica_login.php
│   └── views/                      # Arquivos de visualização (HTML/PHP)
│       ├── estoque/
│       ├── home.php
│       ├── login/
│       ├── usuarios/
│       ├── vendas/
│       └── vendedores/
├── docker-compose.yml              # Define os serviços Docker do projeto
├── Dockerfile                      # Configuração da imagem Docker da aplicação PHP
└── README.md                       # Este arquivo


## Serviços

### Serviço PHP (`app`)

* Utiliza a imagem Docker `php:8.2-apache`.
* Serve a aplicação PHP através do servidor Apache.
* É acessível na porta `8080` do seu host (máquina local).
* O diretório `./CRUD-Pesqueiro` do seu host é montado em `/var/www/html/CRUD-Pesqueiro` dentro do contêiner, tornando os arquivos da aplicação disponíveis para o Apache.

### Backend Supabase

* Este projeto utiliza o Supabase como serviço de banco de dados e backend de autenticação externo, e não um contêiner de banco de dados local como MySQL.
* As credenciais e a URL de conexão com o Supabase são configuradas no arquivo `CRUD-Pesqueiro/config/conexao.php`.

## Inicialização do Banco de Dados

Não há um script de inicialização de banco de dados local (`init.sql`), pois o Supabase gerencia o banco de dados e as tabelas externamente. Certifique-se de que sua instância Supabase esteja configurada com as tabelas necessárias (`usuarios`, `estoque`, `vendas`, `vendedores`) conforme o modelo de dados da aplicação para que ela funcione corretamente.

## Instruções de Configuração

Certifique-se de ter o Docker e o Docker Compose instalados em sua máquina.

1.  **Clone o repositório** ou baixe os arquivos do projeto para o seu computador.
2.  **Navegue até o diretório raiz do projeto** onde estão localizados `docker-compose.yml` e `Dockerfile`:
    ```bash
    cd meu_projeto_docker
    ```
3.  **Inicie os serviços** usando Docker Compose. O contêiner PHP será construído e iniciado:
    ```bash
    docker-compose up -d
    ```
    (O `-d` executa os contêineres em segundo plano, liberando seu terminal).
4.  **Acesse a aplicação PHP** em seu navegador web no endereço:
    ```
    http://localhost:8080/CRUD-Pesqueiro/
    ```
5.  **Atenção ao Login:** A aplicação possui um sistema de login. Certifique-se de ter um usuário cadastrado na sua base de dados Supabase (na tabela `usuarios`) para poder acessar as funcionalidades. Usuários marcados como `is_admin` terão acesso a funcionalidades administrativas, como gerenciamento de usuários.

## Uso

Após realizar o login, você pode interagir com os diferentes módulos da aplicação para gerenciar as operações do Pesqueiro:

* **Usuários:** Gerencie as contas de usuários do sistema, incluindo suas permissões de administrador.
* **Estoque:** Adicione novos produtos, atualize quantidades, edite preços e visualize o inventário disponível.
* **Vendas:** Registre novas vendas, o que automaticamente deduz a quantidade do estoque. Você também pode visualizar o histórico completo de vendas e totais por produto.
* **Vendedores:** Cadastre, edite e visualize as informações dos vendedores responsáveis pelas vendas.

Sinta-se à vontade para modificar os arquivos PHP na pasta `CRUD-Pesqueiro/` para expandir as funcionalidades e personalizar a lógica de negócios conforme suas necessidades.