<?php
define('PROJECT_ROOT', __DIR__); 
define('BASE_URL', '/CRUD-Pesqueiro');

// --- INÍCIO DO CÓDIGO DE SESSÃO ---
$session_path = __DIR__ . '/sessions';

if (!is_dir($session_path)) {
    if (!mkdir($session_path, 0777, true)) {
        die("ERRO CRÍTICO: Não foi possível criar a pasta de sessões em '{$session_path}'.");
    }
}

if (!is_writable($session_path)) {
    die("ERRO CRÍTICO: A pasta de sessões '{$session_path}' não tem permissão de escrita.");
}

ini_set('session.save_path', $session_path);
session_start();
// --- FIM DO CÓDIGO DE SESSÃO ---


// Autoloader básico para carregar classes de modelos e controladores
spl_autoload_register(function ($class_name) {
    $folders = [
        'models/',
        'controllers/',
    ];

    foreach ($folders as $folder) {
        $file = __DIR__ . '/' . $folder . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});


require_once __DIR__ . '/config/conexao.php';

// =========================================================
// --- AJUSTE MÍNIMO E PRECISO NO ROTEADOR ---
// Remove o caminho base da URL antes de processá-la
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($requestUri, BASE_URL) === 0) {
    $requestUri = substr($requestUri, strlen(BASE_URL));
}

$requestParts = explode('/', trim($requestUri, '/'));
// --- FIM DO AJUSTE ---
// =========================================================


$controllerName = 'LoginController'; // Controlador padrão
$actionName = 'index'; // Ação padrão
$params = [];

// Sua lógica de roteamento original, que agora funcionará corretamente
if (!empty($requestParts[0])) {
    $potentialController = ucfirst($requestParts[0]) . 'Controller';
    $controllerPath = __DIR__ . '/controllers/' . $potentialController . '.php';

    if (file_exists($controllerPath)) {
        $controllerName = $potentialController;
        array_shift($requestParts);

        if (!empty($requestParts[0])) {
            $potentialAction = $requestParts[0];
            if (method_exists($controllerName, $potentialAction) && $potentialAction[0] !== '_') {
                $actionName = $potentialAction;
                array_shift($requestParts);
                $params = $requestParts;
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
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found - Ação não encontrada";
    }
} catch (Exception $e) {
    header("HTTP/1.0 500 Internal Server Error");
    echo "500 Internal Server Error - " . $e->getMessage();
}
?>