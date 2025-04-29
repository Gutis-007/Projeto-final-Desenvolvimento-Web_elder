<?php 
include_once("conexao.php");
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
} else {
    $adminId = $_SESSION['admin_id'];
    $sql = "SELECT nome, tipo FROM usuarios WHERE id = $adminId";
    $consulta = mysqli_query($connection, $sql);
    if ($consulta) {
        $row = mysqli_fetch_assoc($consulta);
        $tipo = $row['tipo'];
        $nome = $row['nome'];
        if ($tipo != 'Administrador') {
            header("Location: login.html");
            exit();
        }
    } else {
        die("Erro ao verificar tipo de usuário: " . mysqli_error($connection));
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página do Professor</title>
    <link rel="stylesheet" href="Style/admin.css">
</head>
<body>
    <h1>Página do Administrador</h1>
    <p>Bem-vindo, <?= htmlspecialchars($nome) ?>! Aqui você pode gerenciar suas atividades.</p>

    <form action="notas_adm.php" method="post">
        <button type="submit">Gerenciar Notas dos Alunos</button>
    </form>
    <form action="gerenciar_usuarios.php" method="post">
        <button type="submit">Gerenciar Usuários</button>
    </form>

    <form action="gerenciar_turma.php" method="post">
        <button type="submit">Gerenciar Turmas</button>
    </form>

    <form action="gerenciar_disciplinas.php" method="post">
        <button type="submit">Gerenciar Disciplinas</button>
    </form>

    <form action="relatorio.php" method="post"> 
        <button type="submit">Gerar Relatório</button>
    </form>

    <form action="login.html" method="post">
        <button type="submit">Deslogar</button> 
    </form>
   
</body>
</html>
