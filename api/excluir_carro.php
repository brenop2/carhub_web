<?php
session_start();

// Inclui o arquivo de conexão com o banco de dados
require __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Expires: 0');

try {
    // Verifica se o usuário está autenticado
    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
        exit;
    }

    // Verifica se a conexão com o banco de dados está configurada
    if (!$conn) {
        error_log("Conexão com o banco de dados não inicializada.");
        echo json_encode(['success' => false, 'message' => 'Erro interno. Tente novamente mais tarde.']);
        exit;
    }

    // Verifica se o método é POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método não permitido']);
        exit;
    }

    // Valida o ID recebido
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if (!$id || $id <= 0) {
        error_log("ID inválido recebido: " . json_encode($_POST));
        echo json_encode(['success' => false, 'message' => 'ID inválido ou não fornecido']);
        exit;
    }

    $usuario_id = $_SESSION['usuario_id'];

    // Busca o carro no banco de dados
    $sql = "SELECT imagem FROM carros WHERE id = :id AND usuario_id = :usuario_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':usuario_id' => $usuario_id
    ]);
    $carro = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$carro) {
        error_log("Carro não encontrado ou não pertence ao usuário. ID: $id, Usuario ID: $usuario_id");
        echo json_encode(['success' => false, 'message' => 'Carro não encontrado ou não pertence ao usuário']);
        exit;
    }

    $imagem = $carro['imagem'];

    // Exclui o registro do banco de dados
    $sql = "DELETE FROM carros WHERE id = :id AND usuario_id = :usuario_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':usuario_id' => $usuario_id
    ]);

    if ($stmt->rowCount() > 0) {
        // Remove o arquivo físico, se necessário
        if (!empty($imagem) && $imagem !== 'uploads/default-car.jpg') {
            $path = realpath(__DIR__ . '/../' . $imagem);
            if ($path && file_exists($path)) {
                if (!unlink($path)) {
                    error_log("Erro ao excluir o arquivo: $path");
                }
            } else {
                error_log("Arquivo não encontrado para exclusão: $path");
            }
        }

        echo json_encode(['success' => true, 'message' => 'Carro excluído com sucesso']);
    } else {
        error_log("Erro ao excluir carro no banco de dados. ID: $id, Usuario ID: $usuario_id");
        echo json_encode(['success' => false, 'message' => 'Erro ao excluir carro']);
    }
} catch (PDOException $e) {
    // Registra o erro no log e retorna uma mensagem genérica
    error_log("[" . date('Y-m-d H:i:s') . "] Erro no banco de dados: " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../includes/error.log');
    echo json_encode(['success' => false, 'message' => 'Erro interno. Tente novamente mais tarde.']);
} catch (Exception $e) {
    // Captura outros erros inesperados
    error_log("[" . date('Y-m-d H:i:s') . "] Erro inesperado: " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../includes/error.log');
    echo json_encode(['success' => false, 'message' => 'Erro interno. Tente novamente mais tarde.']);
}
?>
