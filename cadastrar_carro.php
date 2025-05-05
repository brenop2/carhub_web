<?php
session_start();
require __DIR__ . '/includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$msg = "";
$tipo = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $marca = $_POST['marca'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $ano = $_POST['ano'] ?? 0;
    $cor = $_POST['cor'] ?? '';
    $preco = $_POST['preco'] ?? 0.0;
    $descricao = $_POST['descricao'] ?? '';
    $quantidade = $_POST['quantidade'] ?? 1;
    $usuario_id = $_SESSION['usuario_id'];

    // Upload de imagem
    $imagem = "uploads/default-car.jpg";
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $diretorio_upload = "uploads/";
        if (!file_exists($diretorio_upload)) {
            mkdir($diretorio_upload, 0777, true);
        }

        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $nome_arquivo = uniqid() . '.' . $extensao;
        $caminho_arquivo = $diretorio_upload . $nome_arquivo;

        if (in_array($extensao, ['jpg', 'jpeg', 'png', 'gif']) && $_FILES['imagem']['size'] <= 5 * 1024 * 1024) {
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo)) {
                $imagem = $caminho_arquivo;
            } else {
                $msg = "Erro ao fazer upload da imagem.";
                $tipo = "erro";
            }
        } else {
            $msg = "Formato de imagem inválido ou tamanho excedido (máximo 5MB).";
            $tipo = "erro";
        }
    }

    if ($tipo !== "erro") {
        $sql = "INSERT INTO carros (marca, modelo, ano, cor, preco, descricao, imagem, usuario_id, quantidade) 
                VALUES (:marca, :modelo, :ano, :cor, :preco, :descricao, :imagem, :usuario_id, :quantidade)";
        $stmt = $conn->prepare($sql);
        $executado = $stmt->execute([
            ':marca' => $marca,
            ':modelo' => $modelo,
            ':ano' => $ano,
            ':cor' => $cor,
            ':preco' => $preco,
            ':descricao' => $descricao,
            ':imagem' => $imagem,
            ':usuario_id' => $usuario_id,
            ':quantidade' => $quantidade
        ]);

        if ($executado) {
            $msg = "Carro cadastrado com sucesso!";
            $tipo = "sucesso";
        } else {
            $msg = "Erro ao cadastrar carro: " . $stmt->errorInfo()[2];
            $tipo = "erro";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar Carro - CarHub</title>
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
        <span>CarHub</span>
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
            <a href="gerenciar_carros.php" class="flex items-center space-x-2 text-gray-600 hover:text-primary-600 hover:bg-primary-50 p-3 rounded-md">
              <i class="fas fa-car"></i>
              <span>Gerenciar Carros</span>
            </a>
            <a href="cadastrar_carro.php" class="flex items-center space-x-2 text-primary-600 bg-primary-50 p-3 rounded-md font-medium">
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
          <h1 class="text-2xl font-bold text-gray-800 mb-6">Cadastrar Novo Carro</h1>
          
          <?php if ($msg): ?>
            <div class="<?php echo $tipo === 'erro' ? 'bg-red-100 border-red-500 text-red-700' : 'bg-green-100 border-green-500 text-green-700'; ?> border-l-4 p-4 mb-6" role="alert">
              <p><?php echo $msg; ?></p>
            </div>
          <?php endif; ?>

          <form method="post" enctype="multipart/form-data" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome do Carro</label>
                <input type="text" id="nome" name="nome" required 
                      class="w-full border border-gray-300 rounded-md py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                      placeholder="Ex: Toyota Corolla XEi">
              </div>

              <div>
                <label for="marca" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                <input type="text" id="marca" name="marca" required 
                      class="w-full border border-gray-300 rounded-md py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                      placeholder="Ex: Toyota">
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label for="preco" class="block text-sm font-medium text-gray-700 mb-1">Preço (R$)</label>
                <input type="number" id="preco" name="preco" step="0.01" required 
                      class="w-full border border-gray-300 rounded-md py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                      placeholder="Ex: 85000.00">
              </div>

              <div>
                <label for="codigo_produto" class="block text-sm font-medium text-gray-700 mb-1">Código do Produto</label>
                <input type="text" id="codigo_produto" name="codigo_produto" required 
                      class="w-full border border-gray-300 rounded-md py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                      placeholder="Ex: CAR-12345">
              </div>

              <div>
                <label for="quantidade" class="block text-sm font-medium text-gray-700 mb-1">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" required 
                      class="w-full border border-gray-300 rounded-md py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                      placeholder="Ex: 1" min="1" value="1">
              </div>
            </div>

            <div>
              <label for="imagem" class="block text-sm font-medium text-gray-700 mb-1">Imagem do Carro</label>
              <input type="file" id="imagem" name="imagem" accept="image/*" required
                    class="w-full border border-gray-300 rounded-md py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
              <p class="text-sm text-gray-500 mt-1">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 5MB.</p>
            </div>

            <div class="flex justify-end space-x-4">
              <a href="area_principal.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-3 px-6 rounded-md transition duration-300">
                Cancelar
              </a>
              <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 px-6 rounded-md transition duration-300">
                Cadastrar Carro
              </button>
            </div>
          </form>
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
    // Validação do formulário com JavaScript
    document.querySelector('form').addEventListener('submit', function(e) {
      const preco = document.getElementById('preco').value;
      const quantidade = document.getElementById('quantidade').value;
      const imagem = document.getElementById('imagem').files[0];
      
      let isValid = true;
      let errorMessage = '';
      
      // Validar preço
      if (preco <= 0) {
        isValid = false;
        errorMessage = 'O preço deve ser maior que zero.';
      }
      
      // Validar quantidade
      if (quantidade <= 0) {
        isValid = false;
        errorMessage = 'A quantidade deve ser maior que zero.';
      }
      
      // Validar imagem
      if (imagem) {
        const fileSize = imagem.size / 1024 / 1024; // em MB
        if (fileSize > 5) {
          isValid = false;
          errorMessage = 'A imagem deve ter no máximo 5MB.';
        }
      }
      
      if (!isValid) {
        e.preventDefault();
        alert(errorMessage);
      }
    });
  </script>
</body>
</html>
