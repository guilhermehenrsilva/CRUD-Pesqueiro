<?php
// htdocs/index.php - Front Controller

session_start();

// Autoloader básico para carregar classes de modelos e controladores
spl_autoload_register(function ($class_name) {
    $folders = [
        'app/models/',
        'app/controllers/',
        // Adicione outras pastas que contêm classes, se houver
    ];

    foreach ($folders as $folder) {
        $file = __DIR__ . '/' . $folder . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});


require_once __DIR__ . '/config/conexao.php'; // Inclui as configurações do Supabase


// Obtém a URL requisitada e limpa
$requestUri = trim($_SERVER['REQUEST_URI'], '/');
$requestParts = explode('/', $requestUri);

$controllerName = 'LoginController'; // Controlador padrão
$actionName = 'index'; // Ação padrão
$params = [];

// Lógica de roteamento
if (!empty($requestParts[0])) {
    $potentialController = ucfirst($requestParts[0]) . 'Controller';
    $controllerPath = __DIR__ . '/app/controllers/' . $potentialController . '.php';

    if (file_exists($controllerPath)) {
        $controllerName = $potentialController;
        array_shift($requestParts); // Remove o nome do controller da URL

        if (!empty($requestParts[0])) {
            $potentialAction = $requestParts[0];
            if (method_exists($controllerName, $potentialAction)) {
                $actionName = $potentialAction;
                array_shift($requestParts); // Remove o nome da ação da URL
                $params = $requestParts; // O restante são os parâmetros
            }
        }
    } elseif ($requestParts[0] === 'home') {
        $controllerName = 'HomeController';
        array_shift($requestParts);
    } elseif ($requestParts[0] === 'login') {
        $controllerName = 'LoginController';
        array_shift($requestParts);
    } elseif ($requestParts[0] === 'logout') {
        $controllerName = 'LoginController';
        $actionName = 'logout';
        array_shift($requestParts);
    }
}

// Instancia o controlador e chama a ação
try {
    $controller = new $controllerName();
    if (method_exists($controller, $actionName)) {
        call_user_func_array([$controller, $actionName], [$params]);
    } else {
        // Ação não encontrada no controlador
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found - Ação não encontrada";
    }
} catch (Exception $e) {
    // Erro ao instanciar o controlador ou método
    header("HTTP/1.0 500 Internal Server Error");
    echo "500 Internal Server Error - " . $e->getMessage();
}
?>