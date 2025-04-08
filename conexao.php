<?php
define('HOST','127.0.0.1');
define('USARIO', 'root');
define('SENHA', '');
define('DB', 'canalti');

$conexao = mysqli_connect(HOST, USARIO, SENHA, DB) or die('Não foi possível conectar ao banco de dados!');






?>