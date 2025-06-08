<?php
session_start();
require __DIR__ . '/includes/db.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Buscar todos os carros do usuário
$sql = "SELECT * FROM carros WHERE usuario_id = :usuario_id ORDER BY data_cadastro DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->execute();
$carros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gerenciar Carros - CarHub</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              50: '#e6f0ff',
              100: '#cce0ff',
              200: '#99c2ff',
              300: '#66a3ff',
              400: '#3385ff',
              500: '#0066ff',
              600: '#0052cc',
              700: '#003d99',
              800: '#002966',
              900: '#001433',
            }
          }
        }
      }
    }
  </script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  <header class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
      <a href="index.php" class="flex items-center text-primary-600 font-bold text-2xl">
        <i class="fas fa-car mr-2 text-3xl"></i>
        <span>CarHub Ollama</span>
      </a>
      <div class="flex items-center space-x-4">
        <div class="relative group">
          <button class="flex items-center text-gray-700 hover:text-primary-600">
            <span class="mr-2"><?php echo $_SESSION['usuario_nome']; ?></span>
            <i class="fas fa-user-circle text-2xl"></i>
          </button>
          <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
            <a href="perfil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50">Meu Perfil</a>
            <a href="logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Sair</a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <main class="flex-grow container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Sidebar -->
      <div class="md:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center space-x-4 mb-6">
            <div class="bg-primary-100 rounded-full p-3">
              <i class="fas fa-user text-primary-600 text-xl"></i>
            </div>
            <div>
              <h2 class="text-xl font-semibold"><?php echo $_SESSION['usuario_nome']; ?></h2>
              <p class="text-gray-500 text-sm">Membro desde <?php echo date('d/m/Y'); ?></p>
            </div>
          </div>
          
          <nav class="space-y-2">
            <a href="area_principal.php" class="flex items-center space-x-2 text-gray-600 hover:text-primary-600 hover:bg-primary-50 p-3 rounded-md">
              <i class="fas fa-tachometer-alt"></i>
              <span>Dashboard</span>
            </a>
            <a href="gerenciar_carros.php" class="flex items-center space-x-2 text-primary-600 bg-primary-50 p-3 rounded-md font-medium">
              <i class="fas fa-car"></i>
              <span>Gerenciar Carros</span>
            </a>
            <a href="cadastrar_carro.php" class="flex items-center space-x-2 text-gray-600 hover:text-primary-600 hover:bg-primary-50 p-3 rounded-md">
              <i class="fas fa-plus-circle"></i>
              <span>Cadastrar Novo Carro</span>
            </a>
            <a href="perfil.php" class="flex items-center space-x-2 text-gray-600 hover:text-primary-600 hover:bg-primary-50 p-3 rounded-md">
              <i class="fas fa-user-cog"></i>
              <span>Meu Perfil</span>
            </a>
            <a href="logout.php" class="flex items-center space-x-2 text-red-600 hover:bg-red-50 p-3 rounded-md">
              <i class="fas fa-sign-out-alt"></i>
              <span>Sair</span>
            </a>
          </nav>
        </div>
      </div>

      <!-- Main Content -->
      <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gerenciar Carros</h1>
            <a href="cadastrar_carro.php" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 flex items-center">
              <i class="fas fa-plus mr-2"></i> Novo Carro
            </a>
          </div>
          
          <?php if (count($carros) > 0): ?>
            <div class="overflow-x-auto">
              <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                  <tr>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagem</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preço</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="carros-lista">
                  <?php foreach ($carros as $carro): ?>
                    <tr id="carro-<?php echo $carro['id']; ?>">
                      <td class="py-3 px-4">
                        <img src="<?= !empty($carro['imagem']) ? htmlspecialchars($carro['imagem']) : 'imagens/padrao.png'; ?>" 
                          alt="<?= htmlspecialchars($carro['modelo']) ?>"
                          class="h-12 w-16 object-cover rounded"/>
                    </td>

                      <td class="py-3 px-4 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($carro['modelo']); ?></td>
                      <td class="py-3 px-4 text-sm text-gray-500"><?php echo htmlspecialchars($carro['marca']); ?></td>
                      <td class="py-3 px-4 text-sm text-gray-500">R$ <?php echo number_format($carro['preco'], 2, ',', '.'); ?></td>
                      <td class="py-3 px-4 text-sm text-gray-500"><?php echo htmlspecialchars($carro['quantidade']); ?></td>
                      <td class="py-3 px-4 text-sm">
                        <a href="editar_carro.php?id=<?php echo $carro['id']; ?>" class="text-primary-600 hover:text-primary-800 mr-3">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="excluirCarro(<?php echo $carro['id']; ?>)" class="text-red-600 hover:text-red-800">
                          <i class="fas fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="bg-gray-50 rounded-lg p-6 text-center">
              <p class="text-gray-500">Você ainda não cadastrou nenhum carro.</p>
              <a href="cadastrar_carro.php" class="inline-block mt-4 bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                Cadastrar meu primeiro carro
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-gray-800 text-white py-6 mt-auto">
    <div class="container mx-auto px-4 text-center">
      <p>&copy; 2025 CarHub. Todos os direitos reservados.</p>
    </div>
  </footer>

  <script>
function excluirCarro(id) {
  if (confirm('Tem certeza que deseja excluir este carro?')) {
    fetch('api/excluir_carro.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${encodeURIComponent(id)}`
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Erro ao excluir carro');
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        document.getElementById(`carro-${id}`).remove();

        if (document.getElementById('carros-lista').children.length === 0) {
          location.reload();
        }
      } else {
        alert('Erro ao excluir carro: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Erro:', error);
      alert('Ocorreu um erro ao excluir o carro.');
    });
  }
}
  </script>
</body>
</html>
