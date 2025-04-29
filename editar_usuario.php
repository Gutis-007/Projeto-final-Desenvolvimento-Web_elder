<?php
include("conexao.php");
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit;
}

// verifica se o ID do usuário a ser editado foi passado na URL
if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];

    // Consulta os dados do usuário
    $sql = "SELECT u.id, u.nome, u.email, u.senha, u.idade, u.cpf, u.tipo, a.matricula, p.id AS prof_id, ad.id AS admin_id 
            FROM usuarios u
            LEFT JOIN alunos a ON u.id = a.fk_user
            LEFT JOIN professores p ON u.id = p.fk_user
            LEFT JOIN administradores ad ON u.id = ad.fk_user
            WHERE u.id = $usuario_id";
    $resultado = mysqli_query($connection, $sql);
    $usuario = mysqli_fetch_assoc($resultado);

    $sql_aluno = "SELECT id FROM alunos WHERE fk_user = $usuario_id";
    $res_aluno = mysqli_query($connection, $sql_aluno);
    $usuario_aluno = mysqli_fetch_assoc($res_aluno);

    if (!$usuario) {
        // Se o usuário não for encontrado
        echo "Usuário não encontrado!";
        exit;
    }
}

        $todas_turmas = mysqli_query($connection, "SELECT * FROM turmas");
        $turmas_array = mysqli_fetch_all($todas_turmas, MYSQLI_ASSOC);

        $turma_atual_id = null;
        if ($usuario['tipo'] == 'Aluno' && $usuario_aluno) {
            $res_turma = mysqli_query($connection, "SELECT fk_turma FROM turma_alunos WHERE fk_aluno = {$usuario_aluno['id']}");
            $row_turma = mysqli_fetch_assoc($res_turma);
            $turma_atual_id = $row_turma['fk_turma'] ?? null;
        }
        
// Atualizar os dados do usuário
if (isset($_POST['atualizar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $idade = $_POST['idade'];
    $cpf = $_POST['cpf'];
    $tipo = $_POST['tipo'];

    // Se a senha foi alterada, criptografe-a
    if (!empty($senha)) {
        $senha = $senha;  // Criptografa a senha
    } else {
        // Se não foi alterada, mantém a senha antiga
        $senha = $usuario['senha'];
    }

    // Atualiza os dados na tabela de usuarios
    $sql_update = "UPDATE usuarios SET nome = '$nome', email = '$email', senha = '$senha', idade = '$idade', cpf = '$cpf', tipo = '$tipo' WHERE id = $usuario_id";
    mysqli_query($connection, $sql_update);

    // Verifica se o usuário é aluno e atualiza a matrícula
    if ($tipo == 'Aluno') {
        $matricula = $_POST['matricula'];


            $turma_id = $_POST['turma'] ?? null;

        if ($turma_id) {
            // Verifica se já existe uma entrada na tabela turma_alunos
            $verifica_turma = mysqli_query($connection, "SELECT * FROM turma_alunos WHERE fk_aluno = {$usuario_aluno['id']}");

            if (mysqli_num_rows($verifica_turma) > 0) {
                // Atualiza a turma
                $sql_update_turma = "UPDATE turma_alunos SET fk_turma = $turma_id WHERE fk_aluno = {$usuario_aluno['id']}";
                mysqli_query($connection, $sql_update_turma);
            } else {
                // Insere novo vínculo
                $sql_insert_turma = "INSERT INTO turma_alunos (fk_aluno, fk_turma) VALUES ({$usuario_aluno['id']}, $turma_id)";
                mysqli_query($connection, $sql_insert_turma);
            }
        }
        
        // Atualiza a tabela de alunos
        if (isset($usuario['matricula'])) {
            $sql_matricula = "UPDATE alunos SET matricula = '$matricula' WHERE fk_user = $usuario_id";
            mysqli_query($connection, $sql_matricula);
        } else {
            // Se o usuário ainda não é aluno, insere a matrícula
            $sql_matricula = "INSERT INTO alunos (fk_user, matricula) VALUES ($usuario_id, '$matricula')";
            mysqli_query($connection, $sql_matricula);
        }
    }
    else if($tipo == 'Professor') {
        $prof_id = $usuario['prof_id'] ?? null;
    
        if (empty($prof_id)) {
            // Inserir na tabela professores
            $sql_insert_prof = "INSERT INTO professores (fk_user) VALUES ($usuario_id)";
            mysqli_query($connection, $sql_insert_prof);
            $prof_id = mysqli_insert_id($connection); // pega o ID recém-criado
        } else {
            // Remove vínculos antigos se já existe
            $sql_delete = "DELETE FROM prof_disc_turma WHERE fk_prof = $prof_id";
            mysqli_query($connection, $sql_delete);
        }
    
        // Adiciona novos vínculos (caso existam)
        if (isset($_POST['disciplinas_turmas'])) {
            foreach ($_POST['disciplinas_turmas'] as $item) {
                list($disciplina, $turma) = explode('-', $item);
    
                if (!empty($disciplina) && !empty($turma)) {
                    $sql_insert = "INSERT INTO prof_disc_turma (fk_prof, fk_disc, fk_turma) VALUES ($prof_id, $disciplina, $turma)";
                    mysqli_query($connection, $sql_insert);
                }
            }
        }
    }

    // Redireciona após a atualização
    header("Location: gerenciar_usuarios.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="Style/editUser.css">
</head>
<body>
    <div class="container">
        <h1>Editar Usuário</h1>

        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" placeholder="Digite a nova senha (ou deixe em branco para manter a atual)">

            <label for="idade">Idade:</label>
            <input type="number" id="idade" name="idade" value="<?php echo htmlspecialchars($usuario['idade']); ?>" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($usuario['cpf']); ?>" required>

            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" required>
                <option value="Administrador" <?php echo $usuario['tipo'] == 'Administrador' ? 'selected' : ''; ?>>Administrador</option>
                <option value="Professor" <?php echo $usuario['tipo'] == 'Professor' ? 'selected' : ''; ?>>Professor</option>
                <option value="Aluno" <?php echo $usuario['tipo'] == 'Aluno' ? 'selected' : ''; ?>>Aluno</option>
            </select>

            <!-- Se for aluno, mostrar o campo para matrícula -->
            <?php if ($usuario['tipo'] == 'Aluno'): ?>
                <label for="matricula">Matrícula:</label>
                <input type="text" id="matricula" name="matricula" value="<?php echo htmlspecialchars($usuario['matricula']); ?>" required>
                <label for="turma">Turma:</label>
                <select name="turma" id="turma" required>
                    <option value="">Selecione a turma</option>
                    <?php foreach ($turmas_array as $turma): ?>
                        <option value="<?php echo $turma['id']; ?>" <?php echo ($turma['id'] == $turma_atual_id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($turma['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <?php if ($usuario['tipo'] == 'Professor'): ?>
    <label>Disciplinas e turmas que ministra:</label><br>

    <?php
    // Buscar todas as disciplinas
    $disciplinas = mysqli_query($connection, "SELECT * FROM disciplinas");
    $disciplinas_array = mysqli_fetch_all($disciplinas, MYSQLI_ASSOC);

    // Buscar todas as turmas
    $turmas = mysqli_query($connection, "SELECT * FROM turmas");
    $turmas_array = mysqli_fetch_all($turmas, MYSQLI_ASSOC);

    // Verificar se esse professor já tem disciplinas e turmas associadas
    $atuais = [];
    if (!empty($usuario['prof_id'])) { // se nao tiver vazio entao executa a consulta
        // Obter o ID do professor
        $prof_id = $usuario['prof_id'];
        $res = mysqli_query($connection, "SELECT fk_disc, fk_turma FROM prof_disc_turma WHERE fk_prof = $prof_id");
    
        while ($row = mysqli_fetch_assoc($res)) {
            $atuais[] = "{$row['fk_disc']}-{$row['fk_turma']}";
        }
    }
    if (empty($atuais)) { // se o array estiver vazio, significa que o professor não tem disciplinas e turmas associadas
        echo "<p style='color: #888;'>Este professor ainda não ministra nenhuma disciplina. Selecione abaixo para vinculá-lo:</p>";
    }

    foreach ($disciplinas_array as $disc) {
        echo "<h4>Disciplina: {$disc['nome']}</h4>";
        foreach ($turmas_array as $turma) {
            $valor = "{$disc['id']}-{$turma['id']}";
            $checked = in_array($valor, $atuais) ? 'checked' : '';
            echo "<label style='margin-left:20px;'>
                    <input type='checkbox' name='disciplinas_turmas[]' value='$valor' $checked> {$turma['nome']}
                  </label><br>";
        }
        echo "<br>";
    }
    ?>
<?php endif; ?>

            <button type="submit" name="atualizar">Atualizar</button>
        </form>

        <a href="gerenciar_usuarios.php" class="btn-voltar">Voltar para Gerenciamento de Usuários</a>
    </div>
</body>
</html>

<?php mysqli_close($connection); ?>