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
    <title>Página de seleção do tipo</title>
    <link rel="stylesheet" href="Style/admin.css">
</head>
<body>
    <h1>Selecione o tipo do novo usuário: </h1>

    <form action="tipo_aluno.php" method="post">
        <button type="submit">Aluno</button>
    </form>
    <form action="tipo_prof.php" method="post">
        <button type="submit">Professor</button>
    </form>

    <form action="tipo_adm.php" method="post">
        <button type="submit">Administrador</button>
    </form>

    <form action="gerenciar_usuarios.php" method="post">
        <button type="submit">Voltar</button>
    </form>
   
</body>
</html>
