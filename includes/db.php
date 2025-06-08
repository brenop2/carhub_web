<?php
$host = 'localhost';
$port = '3307'; // adicionamos isso pois a porta default 3006 não estava funcionando
$db = 'carhub';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Registra o erro em um arquivo de log
    error_log("[" . date('Y-m-d H:i:s') . "] Erro na conexão com o banco de dados: " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/error.log');
    // Exibe uma mensagem genérica ao usuário
    die("Erro na conexão com o banco de dados. Tente novamente mais tarde.");
}
?>
