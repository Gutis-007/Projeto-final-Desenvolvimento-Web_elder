<?php
include("conexao.php");
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit;
}

// Processa a exclusão de turma
if (isset($_POST['deletar'])) {
    $turma_id = $_POST['turma_id'];
    $sql_delete = "DELETE FROM turmas WHERE id = $turma_id";
    mysqli_query($connection, $sql_delete);
    header("Location: gerenciar_turma.php");
    exit;
}

// Processa atualização de turma
if (isset($_POST['editar'])) {
    $turma_id = $_POST['turma_id'];
    $novo_nome = $_POST['novo_nome'];
    $sql_update = "UPDATE turmas SET nome = '$novo_nome' WHERE id = $turma_id";
    mysqli_query($connection, $sql_update);
    header("Location: gerenciar_turma.php");
    exit;
}

// Processa adição de nova turma
if (isset($_POST['adicionar'])) {
    $nome_turma = $_POST['nome_turma'];
    $sql_insert = "INSERT INTO turmas (nome) VALUES ('$nome_turma')";
    mysqli_query($connection, $sql_insert);
    header("Location: gerenciar_turma.php");
    exit;
}

// Consulta todas as turmas
$sql = "SELECT * FROM turmas ORDER BY nome";
$resultado = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Turmas</title>
    <link rel="stylesheet" href="style/gerTurma.css">
</head>
<body>
<div class="container">
    <h1>Gerenciamento de Turmas</h1>

    <?php if (mysqli_num_rows($resultado) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nome da Turma</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($turma = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td>
                            <form method="post" class="inline-form">
                                <input type="hidden" name="turma_id" value="<?php echo $turma['id']; ?>">
                                <input type="text" name="novo_nome" value="<?php echo htmlspecialchars($turma['nome']); ?>">
                                <button type="submit" name="editar" class="btn btn-editar">Salvar</button>
                            </form>
                        </td>
                        <td class="acoes">
                            <form method="post" class="inline-form" onsubmit="return confirm('Tem certeza que deseja excluir esta turma?');">
                                <input type="hidden" name="turma_id" value="<?php echo $turma['id']; ?>">
                                <button type="submit" name="deletar" class="btn btn-excluir">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma turma cadastrada.</p>
    <?php endif; ?>

    <div class="form-adicionar">
        <h3>Adicionar Nova Turma</h3>
        <form method="post">
            <input type="text" name="nome_turma" placeholder="Nome da nova turma" required>
            <button type="submit" name="adicionar" class="btn btn-editar">Adicionar</button>
        </form>
    </div>

    <div style="text-align: center;">
        <a href="administrador.php" class="btn btn-voltar">Voltar</a>
    </div>
</div>
</body>
</html>

<?php mysqli_close($connection); ?>