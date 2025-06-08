<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

try {
    $stmt = $conn->prepare("SELECT nome, email, data_cadastro FROM usuarios WHERE id = :id");
    $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch();

    if (!$usuario) {
        echo "Usuário não encontrado.";
        exit();
    }

    // Buscar carros do usuário
    $stmtCarros = $conn->prepare("SELECT id, marca, modelo, ano, preco, imagem FROM carros WHERE usuario_id = :usuario_id");
    $stmtCarros->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmtCarros->execute();
    $carros = $stmtCarros->fetchAll();
} catch (PDOException $e) {
    die("Erro ao carregar dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - CarHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="max-w-5xl mx-auto py-10 px-4">
        <!-- Dados do usuário -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-10">
            <h2 class="text-3xl font-bold mb-4">Meu Perfil</h2>
            <p><strong>Nome:</strong> <?= htmlspecialchars($usuario['nome']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
            <p><strong>Membro desde:</strong> <?= date('d/m/Y', strtotime($usuario['data_cadastro'])) ?></p>
            <a href="editar_perfil.php" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Editar Perfil</a>
        </div>

        <!-- Lista de Carros -->
        <section id="carros" class="py-10 bg-gray-50 rounded-lg shadow-inner">
            <div class="mb-6 text-center">
                <h2 class="text-3xl font-bold text-gray-800">Meus Carros Cadastrados</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="carros-container">
                <?php if (count($carros) > 0): ?>
                    <?php foreach ($carros as $carro): ?>
                        <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-transform duration-300 hover:-translate-y-2">
                            <img src="<?= htmlspecialchars($carro['imagem']) ?>" alt="Imagem do carro" class="w-full max-h-60 object-contain bg-gray-200 p-2">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($carro['modelo']) ?></h3>
                                <p class="text-gray-600 mb-1">Marca: <?= htmlspecialchars($carro['marca']) ?></p>
                                <p class="text-gray-600 mb-4">Ano: <?= htmlspecialchars($carro['ano']) ?></p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-blue-700">R$ <?= number_format($carro['preco'], 2, ',', '.') ?></span>
                                    <a href="detalhes_carro.php?id=<?= $carro['id'] ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                                        Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-500 text-lg">Você ainda não cadastrou nenhum carro.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <div class="mt-10 text-center">
            <a href="area_principal.php" class="text-blue-600 hover:underline">← Voltar ao Dashboard</a>
        </div>
    </div>

</body>
</html>
