<?php
require_once __DIR__ . '/../system/verifica_login.php';

class HomeController {
    public function index() {
        // Aqui você pode carregar dados para a home, se necessário
        // Por exemplo, informações de dashboard

        // Carrega a view da home
        require_once __DIR__ . '/../views/home.php';
    }
}
?>