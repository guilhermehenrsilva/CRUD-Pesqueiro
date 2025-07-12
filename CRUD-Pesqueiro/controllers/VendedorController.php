<?php
require_once __DIR__ . '/../system/verifica_login.php';
require_once __DIR__ . '/../models/VendedorModel.php';
require_once __DIR__ . '/../models/VendaModel.php';

class VendedorController {
    private $vendedorModel;
    private $vendaModel;

    public function __construct() {
        $this->vendedorModel = new VendedorModel();
        $this->vendaModel = new VendaModel();
    }

    public function index() {
        $vendedores = $this->vendedorModel->getAllVendedores();
        require_once PROJECT_ROOT . '/views/vendedores/vendedores.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome']);
            $email = trim($_POST['email']);
            $telefone = trim($_POST['telefone']);

            if (empty($nome) || empty($email)) {
                $_SESSION['mensagem'] = "Nome e e-mail são obrigatórios.";
                // CORRIGIDO
                header("Location: " . BASE_URL . "/vendedor/create");
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
            // CORRIGIDO
            header("Location: " . BASE_URL . "/vendedor");
            exit;
        } else {
            require_once PROJECT_ROOT . '/views/vendedores/vendedores-create.php';
        }
    }

    public function edit($params) {
        $vendedor_id = $params[0] ?? $_POST['vendedor_id'] ?? null;

        if (!$vendedor_id) {
            $_SESSION['mensagem'] = "ID de vendedor inválido.";
            // CORRIGIDO
            header("Location: " . BASE_URL . "/vendedor");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome']);
            $email = trim($_POST['email']);
            $telefone = trim($_POST['telefone']);

            $data = [
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone
            ];

            if ($this->vendedorModel->updateVendedor($vendedor_id, $data)) {
                $_SESSION['mensagem'] = "Vendedor atualizado com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao atualizar vendedor.";
            }
            // CORRIGIDO
            header("Location: " . BASE_URL . "/vendedor");
            exit;
        } else {
            $vendedor = $this->vendedorModel->getVendedorById($vendedor_id);
            if (!$vendedor) {
                $_SESSION['mensagem'] = "Vendedor não encontrado.";
                // CORRIGIDO
                header("Location: " . BASE_URL . "/vendedor");
                exit;
            }
            require_once PROJECT_ROOT . '/views/vendedores/vendedores-edit.php';
        }
    }

    public function view($params) {
        $vendedor_id = $params[0] ?? null;

        if (!$vendedor_id) {
            $_SESSION['mensagem'] = "ID de vendedor inválido.";
            // CORRIGIDO
            header("Location: " . BASE_URL . "/vendedor");
            exit;
        }

        $vendedor = $this->vendedorModel->getVendedorById($vendedor_id);
        if (!$vendedor) {
            $_SESSION['mensagem'] = "Vendedor não encontrado.";
            // CORRIGIDO
            header("Location: " . BASE_URL . "/vendedor");
            exit;
        }
        require_once PROJECT_ROOT . '/views/vendedores/vendedores-view.php';
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vendedor_id = $_POST['vendedor_id'];

            // Verifica se existem vendas associadas
            $vendasVinculadas = $this->vendaModel->getAllVendas("?id_vendedor=eq.$vendedor_id");
            if (!empty($vendasVinculadas)) {
                $_SESSION['mensagem'] = "Não é possível excluir este vendedor, pois ele possui vendas vinculadas.";
                // CORRIGIDO
                header("Location: " . BASE_URL . "/vendedor");
                exit;
            }

            if ($this->vendedorModel->deleteVendedor($vendedor_id)) {
                $_SESSION['mensagem'] = "Vendedor excluído com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao excluir o vendedor.";
            }
        }
        // CORRIGIDO
        header("Location: " . BASE_URL . "/vendedor");
        exit;
    }
}
?>