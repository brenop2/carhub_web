<?php
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Expires: 0');

// Verificar se o método é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Obter os dados enviados via POST
$data = json_decode(file_get_contents('php://input'), true);

// Validar os dados recebidos
if (empty($data['nome']) || empty($data['email']) || empty($data['mensagem'])) {
    echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios']);
    exit;
}

// Simular o processamento (em um ambiente real, salvar no banco ou enviar email)
// sleep(1); // Simulação de atraso

// Retornar uma resposta de sucesso
echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso']);
?>
