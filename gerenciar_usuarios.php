<?php
include("conexao.php");
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit;
} else {
    $admin_id = $_SESSION['admin_id'];
}

// Processa a exclusão de usuário se houver requisição
if (isset($_POST['deletar'])) {
    $usuario_id = $_POST['usuario_id'];

    if ($usuario_id != $admin_id) {
        $sql_delete = "DELETE FROM usuarios WHERE id = $usuario_id";
        mysqli_query($connection, $sql_delete);
        header("Location: gerenciar_usuarios.php");
    }
}

$sql = "SELECT id, nome, email, idade, cpf, tipo FROM usuarios WHERE id != $admin_id ORDER BY nome";
$resultado = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="Style/gerUser.css">
</head>
<body>
    <div class="container">
        <h1>Gerenciamento de Usuários</h1>

        <!-- Botão de cadastrar novos usuários -->
       

        <?php if (mysqli_num_rows($resultado) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Idade</th>
                        <th>CPF</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($usuario = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['idade']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['cpf']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['tipo']); ?></td>
                            <td class="acoes">
                                <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-editar">Editar</a>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                                    <button type="submit" name="deletar" class="btn btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum usuário cadastrado.</p>
        <?php endif; ?>
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="selecione_tipo.php" class="btn btn-voltar">Cadastrar Novos Usuários</a>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <a href="administrador.php" class="btn btn-voltar">Voltar</a>
        </div>
    </div>
</body>
</html>

<?php mysqli_close($connection); ?>