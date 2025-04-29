<?php

$hostname = $_ENV['DB_HOST'];
$bancodedados = $_ENV['DB_NAME'];
$usuario = $_ENV["DB_USER"];
$senha = $_ENV["DB_PASS"];
$port = $_ENV["DB_PORT"];
/*
$hostname = "localhost";
$bancodedados = "sistema_esc";
$usuario = "root";
$senha = "";
*/
// conectando:

$connection = mysqli_connect($hostname, $usuario, $senha, $bancodedados, $port);
if (!$connection) { //condição de falha
    die("Falha na conexão: " . mysqli_connect_errno() . " - " . mysqli_connect_error()); // mostrando cod do erro e erro
}

