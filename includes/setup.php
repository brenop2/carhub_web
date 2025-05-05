<?php
// Este arquivo cria o banco de dados e as tabelas necessárias
// Executar uma vez para configurar o sistema

// Conectar ao MySQL sem selecionar um banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";

$conn = new mysqli($host, $usuario, $senha);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Criar o banco de dados
$sql = "CREATE DATABASE IF NOT EXISTS carhub CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Banco de dados criado com sucesso ou já existente<br>";
} else {
    echo "Erro ao criar banco de dados: " . $conn->error . "<br>";
}

// Selecionar o banco de dados
$conn->select_db("carhub");

// Criar tabela de usuários
$sql = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "Tabela 'usuarios' criada com sucesso ou já existente<br>";
} else {
    echo "Erro ao criar tabela 'usuarios': " . $conn->error . "<br>";
}

// Criar tabela de carros
$sql = "CREATE TABLE IF NOT EXISTS carros (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    modelo VARCHAR(100) NOT NULL,
    marca VARCHAR(50) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    codigo_produto VARCHAR(50) NOT NULL UNIQUE,
    quantidade INT(11) NOT NULL DEFAULT 1,
    imagem VARCHAR(255) NOT NULL,
    usuario_id INT(11),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
)";
if ($conn->query($sql) === TRUE) {
    echo "Tabela 'carros' criada com sucesso ou já existente<br>";
} else {
    echo "Erro ao criar tabela 'carros': " . $conn->error . "<br>";
}

echo "Configuração concluída!";
$conn->close();
?>
