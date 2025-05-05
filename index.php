  <?php
  session_start();
  require __DIR__ . '/includes/db.php';

  // Buscar carros para exibição na página inicial
  $sql = "SELECT * FROM carros ORDER BY data_cadastro DESC";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $carros = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <!DOCTYPE html>
  <html lang="pt-BR">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarHub - Seu Portal de Carros</title>
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
        <nav class="hidden md:flex space-x-6">
          <a href="#carros" class="text-gray-700 hover:text-primary-600 font-medium">Carros</a>
          <a href="#sobre" class="text-gray-700 hover:text-primary-600 font-medium">Sobre</a>
          <a href="#contato" class="text-gray-700 hover:text-primary-600 font-medium">Contato</a>
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
            <a href="#carros" class="block text-gray-700 hover:text-primary-600 font-medium py-2">Carros</a>
            <a href="#sobre" class="block text-gray-700 hover:text-primary-600 font-medium py-2">Sobre</a>
            <a href="#contato" class="block text-gray-700 hover:text-primary-600 font-medium py-2">Contato</a>
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

    <main class="flex-grow">
      <!-- Hero Section -->
      <section class="bg-gradient-to-r from-primary-700 to-primary-500 text-white py-20">
        <div class="container mx-auto px-4">
          <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
              <h1 class="text-4xl md:text-5xl font-bold mb-4">Encontre o carro perfeito para você</h1>
              <p class="text-xl mb-8">O CarHub é a plataforma ideal para encontrar, comprar e vender carros de forma simples e segura.</p>
              <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="#carros" class="bg-white text-primary-600 hover:bg-gray-100 font-bold py-3 px-6 rounded-md transition duration-300 text-center">
                  Ver Carros
                </a>
                <?php if (!isset($_SESSION['usuario_id'])): ?>
                  <a href="cadastro.php" class="border-2 border-white text-white hover:bg-white hover:text-primary-600 font-bold py-3 px-6 rounded-md transition duration-300 text-center">
                    Cadastre-se
                  </a>
                <?php else: ?>
                  <a href="cadastrar_carro.php" class="border-2 border-white text-white hover:bg-white hover:text-primary-600 font-bold py-3 px-6 rounded-md transition duration-300 text-center">
                    Anunciar Carro
                  </a>
                <?php endif; ?>
              </div>
            </div>
            <div class="md:w-1/2">
              <img src="./uploads/ChatGPT Image 4 de mai. de 2025, 22_06_21.png" alt="Carro de luxo" class="rounded-lg shadow-2xl">
            </div>
          </div>
        </div>
      </section>

<!-- Carros Section -->
<section id="carros" class="py-16 bg-gray-50">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Carros Disponíveis</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="carros-container">
      <?php if (count($carros) > 0): ?>
        <?php foreach ($carros as $carro): ?>
          <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-transform duration-300 hover:-translate-y-2">
            <img src="uploads/<?php echo htmlspecialchars($carro['imagem']); ?>" alt="Imagem do carro" class="w-full max-h-96 object-contain rounded-lg mb-4">
            <div class="p-6">
              <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($carro['modelo']); ?></h3>
              <p class="text-gray-600 mb-4">Marca: <?php echo htmlspecialchars($carro['marca']); ?></p>
              <div class="flex justify-between items-center">
                <span class="text-xl font-bold text-primary-600">R$ <?php echo number_format($carro['preco'], 2, ',', '.'); ?></span>
                <a href="detalhes_carro.php?id=<?php echo $carro['id']; ?>" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                  Ver Detalhes
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-span-3 text-center py-8">
          <p class="text-gray-500 text-lg">Nenhum carro disponível no momento.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>



      <!-- Sobre Section -->
      <section id="sobre" class="py-16">
        <div class="container mx-auto px-4">
          <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
              <img src="./uploads/ChatGPT Image 4 de mai. de 2025, 22_15_22.png" alt="Showroom de carros" class="rounded-lg shadow-xl">
            </div>
            <div class="md:w-1/2 md:pl-12">
              <h2 class="text-3xl font-bold text-gray-800 mb-6">Sobre o CarHub</h2>
              <p class="text-gray-600 mb-6">O CarHub é uma plataforma inovadora que conecta compradores e vendedores de carros. Nossa missão é tornar o processo de compra e venda de veículos mais simples, seguro e transparente.</p>
              <p class="text-gray-600 mb-6">Fundado em 2025, o CarHub já ajudou milhares de pessoas a encontrarem o carro dos seus sonhos e vendedores a alcançarem o público certo para seus veículos.</p>
              <div class="grid grid-cols-2 gap-4">
                <div class="bg-primary-50 p-4 rounded-lg">
                  <div class="text-primary-600 text-2xl font-bold">1000+</div>
                  <div class="text-gray-600">Carros vendidos</div>
                </div>
                <div class="bg-primary-50 p-4 rounded-lg">
                  <div class="text-primary-600 text-2xl font-bold">500+</div>
                  <div class="text-gray-600">Clientes satisfeitos</div>
                </div>
                <div class="bg-primary-50 p-4 rounded-lg">
                  <div class="text-primary-600 text-2xl font-bold">50+</div>
                  <div class="text-gray-600">Marcas disponíveis</div>
                </div>
                <div class="bg-primary-50 p-4 rounded-lg">
                  <div class="text-primary-600 text-2xl font-bold">24/7</div>
                  <div class="text-gray-600">Suporte ao cliente</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Contato Section -->
      <section id="contato" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
          <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Entre em Contato</h2>
          <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="md:flex">
              <div class="md:w-1/2 bg-primary-600 text-white p-8">
                <h3 class="text-2xl font-semibold mb-4">Informações de Contato</h3>
                <div class="space-y-4">
                  <div class="flex items-start">
                    <i class="fas fa-map-marker-alt mt-1 mr-3"></i>
                    <p>Av. Paulista, 1000<br>São Paulo, SP</p>
                  </div>
                  <div class="flex items-center">
                    <i class="fas fa-phone mr-3"></i>
                    <p>(11) 99999-9999</p>
                  </div>
                  <div class="flex items-center">
                    <i class="fas fa-envelope mr-3"></i>
                    <p>contato@carhub.com</p>
                  </div>
                </div>
                <div class="mt-8">
                  <h4 class="text-lg font-semibold mb-3">Siga-nos</h4>
                  <div class="flex space-x-4">
                    <a href="#" class="text-white hover:text-primary-200 transition duration-300">
                      <i class="fab fa-facebook-f text-xl"></i>
                    </a>
                    <a href="#" class="text-white hover:text-primary-200 transition duration-300">
                      <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-white hover:text-primary-200 transition duration-300">
                      <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-white hover:text-primary-200 transition duration-300">
                      <i class="fab fa-linkedin-in text-xl"></i>
                    </a>
                  </div>
                </div>
              </div>
              <div class="md:w-1/2 p-8">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Envie uma Mensagem</h3>
                <form class="space-y-4">
                  <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                    <input type="text" id="nome" name="nome" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                  </div>
                  <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                  </div>
                  <div>
                    <label for="mensagem" class="block text-sm font-medium text-gray-700 mb-1">Mensagem</label>
                    <textarea id="mensagem" name="mensagem" rows="4" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required></textarea>
                  </div>
                  <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                    Enviar Mensagem
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer class="bg-gray-800 text-white py-12">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <a href="index.php" class="flex items-center text-white font-bold text-2xl mb-4">
              <i class="fas fa-car mr-2 text-3xl"></i>
              <span>CarHub</span>
            </a>
            <p class="text-gray-400">Sua plataforma completa para compra e venda de carros.</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold mb-4">Links Rápidos</h3>
            <ul class="space-y-2">
              <li><a href="#carros" class="text-gray-400 hover:text-white transition duration-300">Carros</a></li>
              <li><a href="#sobre" class="text-gray-400 hover:text-white transition duration-300">Sobre</a></li>
              <li><a href="#contato" class="text-gray-400 hover:text-white transition duration-300">Contato</a></li>
              <li><a href="login.php" class="text-gray-400 hover:text-white transition duration-300">Login</a></li>
              <li><a href="cadastro.php" class="text-gray-400 hover:text-white transition duration-300">Cadastro</a></li>
            </ul>
          </div>
          <div>
            <h3 class="text-lg font-semibold mb-4">Categorias</h3>
            <ul class="space-y-2">
              <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Carros Novos</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Carros Usados</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">SUVs</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Sedans</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Hatches</a></li>
            </ul>
          </div>
          <div>
            <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
            <p class="text-gray-400 mb-4">Inscreva-se para receber as últimas novidades e ofertas.</p>
            <form class="space-y-2">
              <input type="email" placeholder="Seu email" class="w-full bg-gray-700 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
              <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                Inscrever-se
              </button>
            </form>
          </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center">
          <p class="text-gray-400">&copy; 2025 CarHub. Todos os direitos reservados.</p>
        </div>
      </div>
    </footer>

    <script>
      // Mobile Menu Toggle
      const mobileMenuBtn = document.getElementById('mobile-menu-btn');
      const closeMenuBtn = document.getElementById('close-menu-btn');
      const mobileMenu = document.getElementById('mobile-menu');

      mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
      });

      closeMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.add('translate-x-full');
        document.body.style.overflow = '';
      });

      // Fetch para carregar carros (demonstração de uso do fetch API)
      document.addEventListener('DOMContentLoaded', function() {
        // Já temos os carros carregados pelo PHP, mas vamos usar fetch para demonstrar a funcionalidade
        fetch('api/listar_carros.php')
          .then(response => response.json())
          .then(data => {
            console.log('Carros carregados via fetch:', data);
            // Aqui poderíamos atualizar a interface com os dados recebidos
          })
          .catch(error => {
            console.error('Erro ao carregar carros:', error);
          });
      });

      // Formulário de contato
      const contatoForm = document.querySelector('#contato form');
      if (contatoForm) {
        contatoForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          const nome = document.getElementById('nome').value;
          const email = document.getElementById('email').value;
          const mensagem = document.getElementById('mensagem').value;
          
          // Simulação de envio usando fetch
          fetch('api/enviar_contato.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              nome: nome,
              email: email,
              mensagem: mensagem
            })
          })
          .then(response => {
            // Simulação de resposta bem-sucedida
            alert(`Obrigado ${nome}! Sua mensagem foi enviada com sucesso.`);
            contatoForm.reset();
          })
          .catch(error => {
            console.error('Erro:', error);
            alert('Ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente.');
          });
        });
      }
    </script>
  </body>
  </html>
