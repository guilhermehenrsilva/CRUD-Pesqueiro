<?php
// A sessão já foi iniciada pelo index.php, então esta verificação é suficiente.

if (!isset($_SESSION['usuario_id'])) {
    // Define uma mensagem de erro para ser exibida na tela de login (opcional).
    $_SESSION['mensagem'] = "⚠️ Você precisa estar logado para acessar esta página.";
    
    // CORREÇÃO CRÍTICA:
    // Redireciona para a rota de login correta, usando a constante BASE_URL.
    header("Location: " . BASE_URL . "/login");
    exit();
}
?>