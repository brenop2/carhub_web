<?php
session_start();
require __DIR__ . '/includes/db.php';

// Verificar se o ID do carro foi fornecido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Buscar informações do carro
$sql = "SELECT * FROM carros WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$carro = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar se o carro existe
if (!$carro) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $carro['modelo']; ?> - CarHub</title>
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
      <nav class="hidden md:flex space-x-6">
        <a href="index.php#carros" class="text-gray-700 hover:text-primary-600 font-medium">Carros</a>
        <a href="index.php#sobre" class="text-gray-700 hover:text-primary-600 font-medium">Sobre</a>
        <a href="index.php#contato" class="text-gray-700 hover:text-primary-600 font-medium">Contato</a>
        <?php if (!isset($_SESSION['usuario_id'])): ?>
          <a href="login.php" class="text-primary-600 hover:text-primary-800 font-medium">Entrar</a>
          <a href="cadastro.php" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition duration-300">Cadastre-se</a>
        <?php else: ?>
          <a href="area_principal.php" class="text-primary-600 hover:text-primary-800 font-medium">Minha Conta</a>
          <a href="logout.php" class="text-red-600 hover:text-red-800 font-medium">Sair</a>
        <?php endif; ?>
      </nav>
      <button class="md:hidden text-gray-700 hover:text-primary-600" id="mobile-menu-btn">
        <i class="fas fa-bars text-2xl"></i>
      </button>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="fixed top-0 right-0 h-full w-64 bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50">
      <div class="p-6">
        <button id="close-menu-btn" class="absolute top-4 right-4 text-gray-700 hover:text-primary-600">
          <i class="fas fa-times text-xl"></i>
        </button>
        <div class="mt-8 space-y-4">
          <a href="index.php#carros" class="block text-gray-700 hover:text-primary-600 font-medium py-2">Carros</a>
          <a href="index.php#sobre" class="block text-gray-700 hover:text-primary-600 font-medium py-2">Sobre</a>
          <a href="index.php#contato" class="block text-gray-700 hover:text-primary-600 font-medium py-2">Contato</a>
          <?php if (!isset($_SESSION['usuario_id'])): ?>
            <a href="login.php" class="block text-primary-600 hover:text-primary-800 font-medium py-2">Entrar</a>
            <a href="cadastro.php" class="block bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 mt-4 text-center">Cadastre-se</a>
          <?php else: ?>
            <a href="area_principal.php" class="block text-primary-600 hover:text-primary-800 font-medium py-2">Minha Conta</a>
            <a href="logout.php" class="block text-red-600 hover:text-red-800 font-medium py-2">Sair</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>

  <main class="flex-grow container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="md:flex">
        <div class="md:w-1/2">
          <img src="uploads/<?php echo htmlspecialchars($carro['imagem']); ?>" alt="Imagem do carro" class="w-full max-h-96 object-contain rounded-lg mb-4">
        </div>
        <div class="md:w-1/2 p-8">
          <h1 class="text-3xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($carro['modelo']); ?></h1>
          
          <div class="flex items-center mb-6">
            <span class="text-2xl font-bold text-primary-600">R$ <?php echo number_format($carro['preco'], 2, ',', '.'); ?></span>
            <span class="ml-2 bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
              <?php echo $carro['quantidade'] > 0 ? 'Disponível' : 'Indisponível'; ?>
            </span>
          </div>
          
          <div class="space-y-4 mb-8">
            <div class="flex items-center">
              <div class="w-32 text-gray-600">Marca:</div>
              <div class="font-medium"><?php echo htmlspecialchars($carro['marca']); ?></div>
            </div>
            <div class="flex items-center">
              <div class="w-32 text-gray-600">Código:</div>
              <div class="font-medium"><?php echo $carro['id']; ?></div>
            </div>
            <div class="flex items-center">
              <div class="w-32 text-gray-600">Quantidade:</div>
              <div class="font-medium"><?php echo htmlspecialchars($carro['quantidade']); ?> unidade(s)</div>
            </div>
            <div class="flex items-center">
              <div class="w-32 text-gray-600">Cadastrado em:</div>
              <div class="font-medium"><?php echo date('d/m/Y', strtotime($carro['data_cadastro'])); ?></div>
            </div>
          </div>
          
          <div class="space-y-4">
            <button id="btn-contato" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-6 rounded-md transition duration-300 flex items-center justify-center">
              <i class="fas fa-envelope mr-2"></i> Entrar em Contato
            </button>
            <button id="btn-interesse" class="w-full border-2 border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white font-bold py-3 px-6 rounded-md transition duration-300 flex items-center justify-center">
              <i class="fas fa-star mr-2"></i> Tenho Interesse
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Descrição</h2>
        <p class="text-gray-600">
          Este <?php echo $carro['modelo']; ?> da marca <?php echo $carro['marca']; ?> está em excelente estado. 
          Um veículo confiável, econômico e com ótimo custo-benefício. Ideal para uso na cidade e viagens.
        </p>
      </div>
      
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Características</h2>
        <ul class="space-y-2">
          <li class="flex items-center text-gray-600">
            <i class="fas fa-check text-green-500 mr-2"></i> Ar condicionado
          </li>
          <li class="flex items-center text-gray-600">
            <i class="fas fa-check text-green-500 mr-2"></i> Direção hidráulica
          </li>
          <li class="flex items-center text-gray-600">
            <i class="fas fa-check text-green-500 mr-2"></i> Vidros elétricos
          </li>
          <li class="flex items-center text-gray-600">
            <i class="fas fa-check text-green-500 mr-2"></i> Travas elétricas
          </li>
          <li class="flex items-center text-gray-600">
            <i class="fas fa-check text-green-500 mr-2"></i> Airbag
          </li>
        </ul>
      </div>
      
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Vendedor</h2>
        <div class="flex items-center mb-4">
          <div class="bg-primary-100 rounded-full p-3 mr-3">
            <i class="fas fa-user text-primary-600 text-xl"></i>
          </div>
          <div>
            <h3 class="font-medium">CarHub</h3>
            <p class="text-gray-500 text-sm">Vendedor Oficial</p>
          </div>
        </div>
        <div class="space-y-2">
          <div class="flex items-center text-gray-600">
            <i class="fas fa-phone mr-2 text-primary-600"></i> (11) 99999-9999
          </div>
          <div class="flex items-center text-gray-600">
            <i class="fas fa-envelope mr-2 text-primary-600"></i> vendas@carhub.com
          </div>
          <div class="flex items-center text-gray-600">
            <i class="fas fa-map-marker-alt mr-2 text-primary-600"></i> São Paulo, SP
          </div>
        </div>
      </div>
    </div>
    
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
      <h2 class="text-xl font-semibold text-gray-800 mb-6">Envie uma Mensagem</h2>
      <form id="form-interesse" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="nome-contato" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
            <input type="text" id="nome-contato" name="nome" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
          </div>
          <div>
            <label for="email-contato" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" id="email-contato" name="email" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
          </div>
        </div>
        <div>
          <label for="telefone-contato" class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
          <input type="tel" id="telefone-contato" name="telefone" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
        </div>
        <div>
          <label for="mensagem-contato" class="block text-sm font-medium text-gray-700 mb-1">Mensagem</label>
          <textarea id="mensagem-contato" name="mensagem" rows="4" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required></textarea>
        </div>
        <div>
          <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition duration-300">
            Enviar Mensagem
          </button>
        </div>
      </form>
    </div>
  </main>

  <footer class="bg-gray-800 text-white py-6 mt-auto">
    <div class="container mx-auto px-4 text-center">
      <p>&copy; 2025 CarHub. Todos os direitos reservados.</p>
    </div>
  </footer>

  <script>
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const closeMenuBtn = document.getElement('close-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuBtn.addEventListener('click', () => {
      mobileMenu.classList.remove('translate-x-full');
      document.body.style.overflow = 'hidden';
    });

    closeMenuBtn.addEventListener('click', () => {
      mobileMenu.classList.add('translate-x-full');
      document.body.style.overflow = '';
    });

    // Botões de contato e interesse
    const btnContato = document.getElementById('btn-contato');
    const btnInteresse = document.getElementById('btn-interesse');
    const formInteresse = document.getElementById('form-interesse');

    btnContato.addEventListener('click', () => {
      formInteresse.scrollIntoView({ behavior: 'smooth' });
      document.getElementById('mensagem-contato').value = 'Olá, tenho interesse no carro <?php echo $carro['modelo']; ?> (Código: <?php echo $carro['id']; ?>). Por favor, entre em contato comigo.';
    });

    btnInteresse.addEventListener('click', () => {
      formInteresse.scrollIntoView({ behavior: 'smooth' });
      document.getElementById('mensagem-contato').value = 'Olá, gostaria de agendar uma visita para ver o carro <?php echo $carro['modelo']; ?> (Código: <?php echo $carro['id']; ?>).';
    });

    // Formulário de interesse
    formInteresse.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const nome = document.getElementById('nome-contato').value;
      const email = document.getElementById('email-contato').value;
      const telefone = document.getElementById('telefone-contato').value;
      const mensagem = document.getElementById('mensagem-contato').value;
      
      // Simulação de envio usando fetch
      fetch('enviar_interesse.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          carro_id: <?php echo $carro['id']; ?>,
          nome: nome,
          email: email,
          telefone: telefone,
          mensagem: mensagem
        })
      })
      .then(response => {
        // Simulação de resposta bem-sucedida
        alert(`Obrigado ${nome}! Sua mensagem foi enviada com sucesso. Entraremos em contato em breve.`);
        formInteresse.reset();
      })
      .catch(error => {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente.');
      });
    });
  </script>
</body>
</html>
