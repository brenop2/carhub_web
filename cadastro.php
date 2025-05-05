<?php
session_start();
require __DIR__ . '/includes/db.php';
$msg = "";
$tipo = "";

// Verificar se o usuário já está logado
if (isset($_SESSION['usuario_id'])) {
    header("Location: area_principal.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Verificar se as senhas coincidem
    if ($senha !== $confirmar_senha) {
        $msg = "As senhas não coincidem.";
        $tipo = "erro";
    } else {
        // Verificar se o email já existe
        $sql = "SELECT id FROM usuarios WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $msg = "Este email já está cadastrado.";
            $tipo = "erro";
        } else {
            // Criptografar a senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // Verificar se o diretório 'uploads' existe, caso contrário, criá-lo
            $uploadDir = __DIR__ . '/uploads';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    $msg = "Erro ao criar o diretório de uploads.";
                    $tipo = "erro";
                }
            }

            // Verificar se o arquivo foi enviado e movê-lo para o diretório correto
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $nomeArquivo = uniqid() . '-' . basename($_FILES['imagem']['name']);
                $caminhoArquivo = $uploadDir . '/' . $nomeArquivo;

                if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoArquivo)) {
                    $msg = "Erro ao salvar o arquivo enviado.";
                    $tipo = "erro";
                } else {
                    // Salvar o caminho do arquivo no banco de dados
                    $imagem = 'uploads/' . $nomeArquivo;
                }
            } else {
                $imagem = null; // Caso nenhuma imagem seja enviada
            }

            // Inserir o novo usuário
            $sql = "INSERT INTO usuarios (nome, email, senha_hash) VALUES (:nome, :email, :senha)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha_hash);

            if ($stmt->execute()) {
                $msg = "Cadastro realizado com sucesso! Você já pode fazer login.";
                $tipo = "sucesso";
            } else {
                $msg = "Erro ao cadastrar: " . $stmt->errorInfo()[2];
                $tipo = "erro";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - CarHub</title>
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
<body class="bg-gradient-to-br from-primary-50 to-white min-h-screen flex flex-col">
  <header class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
      <a href="index.php" class="flex items-center text-primary-600 font-bold text-2xl">
        <i class="fas fa-car mr-2 text-3xl"></i>
        <span>CarHub</span>
      </a>
      <nav>
        <a href="login.php" class="text-primary-600 hover:text-primary-800 font-medium">Entrar</a>
      </nav>
    </div>
  </header>

  <main class="flex-grow flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-primary-600 mb-2">Crie sua conta</h1>
        <p class="text-gray-600">Preencha os dados abaixo para se cadastrar</p>
      </div>

      <?php if ($msg): ?>
        <div class="<?php echo $tipo === 'erro' ? 'bg-red-100 border-red-500 text-red-700' : 'bg-green-100 border-green-500 text-green-700'; ?> border-l-4 p-4 mb-6" role="alert">
          <p><?php echo $msg; ?></p>
        </div>
      <?php endif; ?>

      <form method="post" class="space-y-6">
        <div>
          <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome completo</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-user text-gray-400"></i>
            </div>
            <input type="text" id="nome" name="nome" required 
                  class="pl-10 w-full border border-gray-300 rounded-md py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                  placeholder="Seu nome completo">
          </div>
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-envelope text-gray-400"></i>
            </div>
            <input type="email" id="email" name="email" required 
                  class="pl-10 w-full border border-gray-300 rounded-md py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                  placeholder="seu@email.com">
          </div>
        </div>

        <div>
          <label for="senha" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input type="password" id="senha" name="senha" required 
                  class="pl-10 w-full border border-gray-300 rounded-md py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                  placeholder="Crie uma senha forte">
          </div>
        </div>

        <div>
          <label for="confirmar_senha" class="block text-sm font-medium text-gray-700 mb-1">Confirmar senha</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required 
                  class="pl-10 w-full border border-gray-300 rounded-md py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                  placeholder="Confirme sua senha">
          </div>
        </div>

        <div>
          <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-md transition duration-300 shadow-md">
            Cadastrar
          </button>
        </div>
      </form>

      <div class="mt-6 text-center">
        <p class="text-gray-600">Já tem uma conta? <a href="login.php" class="text-primary-600 hover:text-primary-800 font-medium">Faça login</a></p>
      </div>
    </div>
  </main>

  <footer class="bg-gray-800 text-white py-6">
    <div class="container mx-auto px-4 text-center">
      <p>&copy; 2025 CarHub. Todos os direitos reservados.</p>
    </div>
  </footer>
</body>
</html>
