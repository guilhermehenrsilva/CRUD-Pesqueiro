<?php
// Inclui o verificador de login no topo do arquivo.
// Todas as funções abaixo só serão executadas se o usuário estiver logado.
require_once __DIR__ . '/../system/verifica_login.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        // Agora o construtor só precisa instanciar o modelo.
        $this->usuarioModel = new UsuarioModel();
    }

    public function index() {
        $usuarios = $this->usuarioModel->getAllUsuarios();
        require_once PROJECT_ROOT . '/views/usuarios/usuarios.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome']);
            $email = trim($_POST['email']);
            $data_nascimento = trim($_POST['data_nascimento']);
            $senha = !empty($_POST['senha']) ? password_hash(trim($_POST['senha']), PASSWORD_DEFAULT) : '';
            $is_admin = isset($_POST['is_admin']) ? true : false;

            if (empty($nome) || empty($email) || empty($data_nascimento) || empty($senha)) {
                $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
                header("Location: " . BASE_URL . "/usuario/create");
                exit;
            }

            if ($this->usuarioModel->createUsuario([
                'nome' => $nome,
                'email' => $email,
                'data_nascimento' => $data_nascimento,
                'senha' => $senha,
                'is_admin' => $is_admin
            ])) {
                $_SESSION['mensagem'] = "Usuário adicionado com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao adicionar usuário.";
            }
            header("Location: " . BASE_URL . "/usuario");
            exit;
        } else {
            require_once PROJECT_ROOT . '/views/usuarios/usuario-create.php';
        }
    }

    public function edit($params) {
        $usuario_id = $params[0] ?? $_POST['usuario_id'] ?? null;

        if (!$usuario_id) {
            $_SESSION['mensagem'] = "ID de usuário inválido.";
            header("Location: " . BASE_URL . "/usuario");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificação de permissão de admin para a ação de POST
            if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
                $_SESSION['mensagem'] = "Você não tem permissão para editar usuários.";
                header("Location: " . BASE_URL . "/usuario");
                exit;
            }

            $nome = trim($_POST['nome']);
            $email = trim($_POST['email']);
            $data_nascimento = trim($_POST['data_nascimento']);
            $is_admin = isset($_POST['is_admin']) ? true : false;

            $data = [
                'nome' => $nome,
                'email' => $email,
                'data_nascimento' => $data_nascimento,
                'is_admin' => $is_admin
            ];

            if (!empty($_POST['senha'])) {
                $data['senha'] = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT);
            }

            if ($this->usuarioModel->updateUsuario($usuario_id, $data)) {
                $_SESSION['mensagem'] = "Usuário atualizado com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao atualizar usuário.";
            }
            header("Location: " . BASE_URL . "/usuario");
            exit;
        } else { // Carrega a página de edição (GET request)
            $usuario = $this->usuarioModel->getUsuarioById($usuario_id);
            if (!$usuario) {
                $_SESSION['mensagem'] = "Usuário não encontrado.";
                header("Location: " . BASE_URL . "/usuario");
                exit;
            }
            require_once PROJECT_ROOT . '/views/usuarios/usuario-edit.php';
        }
    }

    public function view($params) {
        $usuario_id = $params[0] ?? null;
        if (!$usuario_id) {
            $_SESSION['mensagem'] = "ID de usuário inválido.";
            header("Location: " . BASE_URL . "/usuario");
            exit;
        }

        $usuario = $this->usuarioModel->getUsuarioById($usuario_id);
        if (!$usuario) {
            $_SESSION['mensagem'] = "Usuário não encontrado.";
            header("Location: " . BASE_URL . "/usuario");
            exit;
        }
        
        require_once PROJECT_ROOT . '/views/usuarios/usuario-view.php';
    }

    public function delete() {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            $_SESSION['mensagem'] = "Você não tem permissão para excluir usuários.";
            header("Location: " . BASE_URL . "/usuario");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = $_POST['delete_usuario'];
            if ($this->usuarioModel->deleteUsuario($usuario_id)) {
                $_SESSION['mensagem'] = "Usuário excluído com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao excluir usuário.";
            }
        }
        header("Location: " . BASE_URL . "/usuario");
        exit;
    }
}
?>