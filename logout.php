<?php
session_start();

// Limpa todas as variáveis de sessão
$_SESSION = [];

// Remove o cookie de sessão, se existir
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Destroi a sessão
session_destroy();

// Evita cache da página
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Pragma: no-cache");

// Redireciona para a página inicial
header("Location: index.php");
exit;
?>
