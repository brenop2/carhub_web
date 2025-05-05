<?php
include '../includes/db.php';
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Expires: 0');

try {
    // Buscar todos os carros
    $sql = "SELECT id, modelo, marca, preco, imagem, data_cadastro FROM carros ORDER BY data_cadastro DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Obter os resultados como um array associativo
    $carros = $stmt->fetchAll();

    // Retornar os dados em formato JSON
    echo json_encode($carros, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    // Registrar o erro e retornar uma mensagem genérica
    error_log("[" . date('Y-m-d H:i:s') . "] Erro ao listar carros: " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../includes/error.log');
    echo json_encode(['erro' => 'Erro ao buscar os carros. Tente novamente mais tarde.']);
}
?>
