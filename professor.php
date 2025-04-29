<?php 
include_once("conexao.php");
session_start();
if (!isset($_SESSION['prof_id'])) {
    
    header("Location: login.html");
}
else {
    $profId = $_SESSION['prof_id'];
    $sql = "SELECT nome, tipo FROM usuarios WHERE id = $profId";
    $consulta = mysqli_query($connection, $sql);
    if ($consulta) {
        $row = mysqli_fetch_assoc($consulta);
        $tipo = $row['tipo'];
        $nome = $row['nome'];
        if ($tipo != 'Professor') {
            header("Location: login.html");
            exit();
        }
    } else {
        die("Erro ao verificar tipo de usuário: " . mysqli_error($connection));
    }
}
// Verifica se o usuário é um professor
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página do Professor</title>
    <link rel="stylesheet" href="Style/professor.css">
</head>
<body>
    <h1>Página do Professor</h1>
    <p>Bem-vindo, <?= htmlspecialchars($nome) ?>! Aqui você pode gerenciar suas atividades.</p>

    <form action="notas.php" method="post">
        <button type="submit">Ver Notas dos Alunos</button>
    </form>
    <form action="login.html" method="post">
        <button type="submit">Deslogar</button> 
    </form>
</body>
</html>
