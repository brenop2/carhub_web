<?php
session_start();
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Expires: 0');

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

// Simular estatísticas (em um ambiente real, buscar do banco de dados)
$estatisticas = [
    'total_carros' => rand(5, 20),
    'visualizacoes' => rand(100, 500),
    'mensagens' => rand(0, 10),
    'ultima_atividade' => date('Y-m-d H:i:s'),
    'interesses' => 42,
    'media_preco' => 58000
];

// Retornar as estatísticas em formato JSON
echo json_encode([
    'success' => true,
    'estatisticas' => $estatisticas
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
