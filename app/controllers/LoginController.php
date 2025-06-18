<?php
session_start();
require_once __DIR__ . '/../models/UsuarioModel.php';

class LoginController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index() {
        if (isset($_POST['entrar'])) {
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $usuario = $this->usuarioModel->getUsuarioByEmail($email);

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['is_admin'] = $usuario['is_admin'];
                header("Location: /home");
                exit;
            } else {
                $erro = "E-mail ou senha incorretos!";
            }
        }
        // Se a requisição não for POST ou se houver erro, carrega a view de login
        require_once __DIR__ . '/../views/login/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: /login");
        exit;
    }
}
?>