<?php
// Configurações de conexão com o banco de dados
define('HOST', '127.0.0.1');
define('USUARIO', 'root'); // Usuário do banco de dados
define('SENHA', '');
define('DB', 'canalti');

// Conexão com o banco
$conexao = mysqli_connect(HOST, USUARIO, SENHA, DB) or die('Não foi possível conectar ao banco de dados!');
?>
