<?php
// Script para configurar o banco de dados automaticamente

// Obter variáveis de ambiente
$hostname = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$port = getenv('DB_PORT') ?: '3306';
$database = getenv('DB_NAME') ?: 'sistema_esc';

echo "Iniciando configuração do banco de dados...\n";

// Conectar ao servidor MySQL
$conn = new mysqli($hostname, $username, $password, $database, $port);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

echo "Conexão ao servidor MySQL estabelecida.\n";

// Verificar se o banco de dados existe
$result = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'");

if ($result->num_rows == 0) {
    echo "Criando banco de dados '$database'...\n";
    
    // Criar o banco de dados
    if ($conn->query("CREATE DATABASE IF NOT EXISTS $database") === TRUE) {
    } else {
        die("Erro ao criar banco de dados: " . $conn->error . "\n");
    }
    
    // Selecionar o banco de dados
    $conn->select_db($database);
    
    // Carregar o arquivo SQL
    echo "Importando estrutura e dados...\n";
    $sql_file = file_get_contents('sistema_esc.sql');
    
    // Dividir o conteúdo do arquivo em consultas individuais
    $queries = explode(';', $sql_file);
    
    // Executar cada consulta
    $success = true;
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            if ($conn->query($query) === FALSE) {
                $success = false;
                break;
            }
        }
    }
}

// Verificar se todas as tabelas necessárias existem
$conn->select_db($database);
$required_tables = ['usuarios', 'disciplinas', 'administradores', 'professores', 'alunos', 'turmas', 'prof_disc_turma', 'turma_alunos', 'notas'];
$missing_tables = [];

foreach ($required_tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows == 0) {
        $missing_tables[] = $table;
    }
}

if (!empty($missing_tables)) {
    echo "Algumas tabelas estão faltando: " . implode(', ', $missing_tables) . "\n";
    echo "Importando estrutura e dados...\n";
    
    // Carregar o arquivo SQL
    $sql_file = file_get_contents('sistema_esc.sql');
    
    // Dividir o conteúdo do arquivo em consultas individuais
    $queries = explode(';', $sql_file);
    
    // Executar cada consulta
    $success = true;
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            if ($conn->query($query) === FALSE) {
                $success = false;
                break;
            }
        }
    }
}

echo "Configuração do banco de dados concluída.\n";
?>