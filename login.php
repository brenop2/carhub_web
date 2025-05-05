<?php
session_start();
require __DIR__ . '/includes/db.php';
$msg = "";

// Verificar se o usuário já está logado
if (isset($_SESSION['usuario_id'])) {
    header("Location: area_principal.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        if (password_verify($senha, $usuario['senha_hash'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header("Location: area_principal.php");
            exit;
        } else {
            $msg = "Senha incorreta.";
        }
    } else {
        $msg = "Usuário não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - CarHub</title>
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
      <a href="index.html" class="flex items-center text-primary-600 font-bold text-2xl">
        <i class="fas fa-car mr-2 text-3xl"></i>
        <span>CarHub</span>
      </a>
      <nav>
        <a href="cadastro.php" class="text-primary-600 hover:text-primary-800 font-medium">Cadastre-se</a>
      </nav>
    </div>
  </header>

  <main class="flex-grow flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-primary-600 mb-2">Bem-vindo de volta</h1>
        <p class="text-gray-600">Entre com suas credenciais para acessar sua conta</p>
      </div>

      <?php if ($msg): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
          <p><?php echo $msg; ?></p>
        </div>
      <?php endif; ?>

      <form method="post" class="space-y-6">
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
                  placeholder="Sua senha">
          </div>
        </div>

        <div>
          <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-md transition duration-300 shadow-md">
            Entrar
          </button>
        </div>
      </form>

      <div class="mt-6 text-center">
        <p class="text-gray-600">Não tem uma conta? <a href="cadastro.php" class="text-primary-600 hover:text-primary-800 font-medium">Cadastre-se</a></p>
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
