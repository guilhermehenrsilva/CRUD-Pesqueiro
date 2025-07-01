<?php

require_once __DIR__ . '/../system/verifica_login.php';
require_once __DIR__ . '/../models/VendedorModel.php';

class VendedorController {
    private $vendedorModel;

    public function __construct() {
        $this->vendedorModel = new VendedorModel();
    }

    public function index() {
        $vendedores = $this->vendedorModel->getAllVendedores();
        require_once __DIR__ . '/../views/vendedores/vendedores.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome_vendedor']);
            $email = trim($_POST['email']);
            $telefone = trim($_POST['telefone']);

            if (empty($nome) || empty($email)) {
                $_SESSION['mensagem'] = "Nome e e-mail são obrigatórios.";
                header("Location: /vendedores/create");
                exit;
            }

            if ($this->vendedorModel->createVendedor([
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone
            ])) {
                $_SESSION['mensagem'] = "Vendedor cadastrado com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao cadastrar o vendedor.";
            }
            header("Location: /vendedores");
            exit;
        } else {
            require_once __DIR__ . '/../views/vendedores/vendedores-create.php';
        }
    }

    public function edit($params) {
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
            $_SESSION['mensagem'] = "Você não tem permissão para acessar essa página.";
            header("Location: /vendedores");
            exit;
        }

        $vendedor_id = $params[0] ?? null;

        if (!$vendedor_id) {
            $_SESSION['mensagem'] = "ID de vendedor inválido.";
            header("Location: /vendedores");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome_vendedor = trim($_POST['nome_vendedor']);
            $email = trim($_POST['email']);
            $telefone = trim($_POST['telefone']);

            $data = [
                'nome' => $nome_vendedor,
                'email' => $email,
                'telefone' => $telefone
            ];

            if ($this->vendedorModel->updateVendedor($vendedor_id, $data)) {
                $_SESSION['mensagem'] = "Vendedor atualizado com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao atualizar vendedor.";
            }
            header("Location: /vendedores");
            exit;
        } else {
            $vendedor = $this->vendedorModel->getVendedorById($vendedor_id);
            if (!$vendedor) {
                $_SESSION['mensagem'] = "Vendedor não encontrado.";
                header("Location: /vendedores");
                exit;
            }
            require_once __DIR__ . '/../views/vendedores/vendedores-edit.php';
        }
    }

    public function view($params) {
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
            $_SESSION['mensagem'] = "Você não tem permissão para acessar essa página.";
            header("Location: /vendedores");
            exit;
        }

        $vendedor_id = $params[0] ?? null;

        if (!$vendedor_id) {
            $_SESSION['mensagem'] = "ID de vendedor inválido.";
            header("Location: /vendedores");
            exit;
        }

        $vendedor = $this->vendedorModel->getVendedorById($vendedor_id);
        if (!$vendedor) {
            $_SESSION['mensagem'] = "Vendedor não encontrado.";
            header("Location: /vendedores");
            exit;
        }
        require_once __DIR__ . '/../views/vendedores/vendedores-view.php';
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vendedor_id = $_POST['vendedor_id'];
            // Em um cenário real, você verificaria se existem vendas associadas a este vendedor antes de excluir.
            // Para simplificar, estamos excluindo diretamente.
            if ($this->vendedorModel->deleteVendedor($vendedor_id)) {
                $_SESSION['mensagem'] = "Vendedor excluído com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao excluir o vendedor.";
            }
        }
        header("Location: /vendedores");
        exit;
    }
}
?>