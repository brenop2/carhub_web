<?php
session_start();
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Expires: 0');

// Verificar se o usuário está logado
if (isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'loggedIn' => true,
        'userId' => $_SESSION['usuario_id'],
        'userName' => $_SESSION['usuario_nome'] ?? 'Usuário'
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
?>
