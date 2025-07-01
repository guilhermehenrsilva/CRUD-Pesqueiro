<?php
// A sessão já é iniciada pelo index.php
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

                // --- ALTERAÇÃO CRÍTICA AQUI ---
                // Vamos usar um caminho absoluto para garantir o redirecionamento correto.
                header("Location: /CRUD-Pesqueiro/home");
                exit;
            } else {
                $erro = "E-mail ou senha incorretos!";
            }
        }
        
        // Carrega a view de login
        require_once PROJECT_ROOT . '/views/login/login.php';
    }

   /**
    * Destrói a sessão e redireciona para a página de login.
    */
    public function logout() {
        session_destroy();
        // --- ALTERAÇÃO CRÍTICA AQUI ---
        // Também usando caminho absoluto para o logout.
        header("Location: /CRUD-Pesqueiro/login");
        exit;
    }
}
?>