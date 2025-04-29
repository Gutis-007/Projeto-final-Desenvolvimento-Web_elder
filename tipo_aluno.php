<?php 
include_once("conexao.php");

if (isset($_POST['submit'])) {
    // Coleta e tratamento dos dados do formulário
    $nome = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $cpf = htmlspecialchars($_POST['cpf'], ENT_QUOTES, 'UTF-8');
    $senha = htmlspecialchars($_POST['senha'], ENT_QUOTES, 'UTF-8');
    $data_nascimento = htmlspecialchars($_POST['data_nascimento'], ENT_QUOTES, 'UTF-8');
    $turma = htmlspecialchars($_POST['turma'], ENT_QUOTES, 'UTF-8');
    $matricula = htmlspecialchars($_POST['matricula'], ENT_QUOTES, 'UTF-8');

    // Validação da data
    $data = DateTime::createFromFormat('d/m/Y', $data_nascimento);
    if (!$data) {
        die("Formato de data inválido. Use DD/MM/AAAA");
    }
    $hoje = new DateTime();
    $idade = $hoje->diff($data)->y;

    if (empty($matricula) || empty($turma)) {
        die("Matrícula e turma são obrigatórias para alunos");
    }

    // Inserção do aluno na tabela usuarios
    $sql_users = "INSERT INTO usuarios (nome, idade, email, cpf, senha, tipo) 
                  VALUES ('$nome', '$idade', '$email', '$cpf', '$senha', 'Aluno')";
    $result_users = mysqli_query($connection, $sql_users);

    if (!$result_users) {
        die("Erro ao cadastrar o usuário: " . mysqli_error($connection));
    }

    $user_id = mysqli_insert_id($connection);

    // Inserção do aluno na tabela alunos
    $sql_alunos = "INSERT INTO alunos (matricula, fk_user) 
                   VALUES ('$matricula', $user_id)";
    $result_alunos = mysqli_query($connection, $sql_alunos);

    if (!$result_alunos) {
        die("Erro ao cadastrar aluno: " . mysqli_error($connection));
    }

    $aluno_id = mysqli_insert_id($connection);

    // Vincular aluno à turma
    $sql_turma_alunos = "INSERT INTO turma_alunos (fk_aluno, fk_turma) 
                         VALUES ($aluno_id, $turma)";
    $result_turma_alunos = mysqli_query($connection, $sql_turma_alunos);

    if (!$result_turma_alunos) {
        die("Erro ao vincular aluno à turma: " . mysqli_error($connection));
    }

    // Inserir aluno com nota 0 para todas as disciplinas
    $sql_disciplinas = "SELECT 
                            d.id AS id_disciplina, 
                            p.id AS id_professor
                        FROM 
                            disciplinas d
                        INNER JOIN prof_disc_turma pdt ON d.id = pdt.fk_disc
                        INNER JOIN professores p ON pdt.fk_prof = p.id";

    $result_disciplinas = mysqli_query($connection, $sql_disciplinas);

    if ($result_disciplinas && mysqli_num_rows($result_disciplinas) > 0) {
        $disciplinas = mysqli_fetch_all($result_disciplinas, MYSQLI_ASSOC);

        foreach ($disciplinas as $disciplina) {
            $disciplina_id = $disciplina['id_disciplina'];
            $professor_id = $disciplina['id_professor'];

            $sql_notas = "INSERT INTO notas (nota, dataL, fk_aluno, fk_prof, fk_disc) 
                          VALUES (0, CURDATE(), $aluno_id, $professor_id, $disciplina_id)";
            mysqli_query($connection, $sql_notas);
        }
    } else {
        die("Nenhuma disciplina encontrada para associar notas.");
    }

    // Redirecionamento com sucesso
    header("Location: tipo_aluno.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Aluno</title>
    <link rel="stylesheet" href="Style/cad.css">
</head>
<body>
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <script>alert('Cadastro realizado com sucesso!');</script>
<?php endif; ?>

<h1>Cadastrar Novo Aluno</h1>
<form action="tipo_aluno.php" method="post">
    <label for="nome">Nome:</label>
    <input placeholder="Ex: João" class="inputCad" type="text" id="nome" name="nome" required>

    <label for="email">Email:</label>
    <input placeholder="Ex: aluno@Gmail.com" class="inputCad" type="email" id="email" name="email" required>

    <label for="data_nascimento">Data de Nascimento:</label>
    <input type="text" id="data_nascimento" class="inputCad" name="data_nascimento" placeholder="DD/MM/AAAA" pattern="\d{2}\/\d{2}\/\d{4}" required>

    <label for="cpf">CPF:</label> 
    <input placeholder="Ex: 000.000.000-00" class="inputCad" type="text" id="cpf" name="cpf" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" required>

    <label for="senha">Senha:</label>
    <input placeholder="Ex: Senha123" class="inputCad" type="password" id="senha" name="senha" required>

    <label for="matricula">Matrícula:</label>
    <input placeholder="Ex: 123456" class="inputCad" type="text" id="matricula" name="matricula" pattern="\d{6}" required>

    <label for="turma">Turma:</label>
    <select style="margin-left: 2em; margin-bottom: 1em; border-radius: 5px; border: 1px solid #ccc;" id="turma" name="turma" required>
        <option value="">Selecione:</option>
        <?php
        // Consulta para obter as turmas
        $sql_turmas = "SELECT id, nome FROM turmas";
        $result_turmas = mysqli_query($connection, $sql_turmas);

        if ($result_turmas && mysqli_num_rows($result_turmas) > 0) {
            while ($row = mysqli_fetch_assoc($result_turmas)) {
                echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
            }
        } else {
            echo "<option value=''>Nenhuma turma encontrada</option>";
        }
        ?>
    </select>

    <input class="inputCad" type="submit" name="submit" id="submit" value="Cadastrar">
</form>

<div style="text-align: center;">
    <button class="button-cadastro" onclick="window.location.href='selecione_tipo.php'">Voltar</button>
</div>
</body>
</html>
