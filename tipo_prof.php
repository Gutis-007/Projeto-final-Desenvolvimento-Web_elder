<?php 
include_once("conexao.php");

// CADASTRAR PROFESSOR
if (isset($_POST['submit'])) {
    // Coletar dados do formulário
    $nome = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $cpf = htmlspecialchars($_POST['cpf'], ENT_QUOTES, 'UTF-8');
    $senha = htmlspecialchars($_POST['senha'], ENT_QUOTES, 'UTF-8');
    $data_nascimento = htmlspecialchars($_POST['data_nascimento'], ENT_QUOTES, 'UTF-8');
    
    $disciplinasTurmasSelecionadas = isset($_POST['disciplinas_turmas']) ? $_POST['disciplinas_turmas'] : [];

    // Validar data
    $data = DateTime::createFromFormat('d/m/Y', $data_nascimento);
    if (!$data) {
        die("Formato de data inválido. Use DD/MM/AAAA");
    }
    $hoje = new DateTime();
    $idade = $hoje->diff($data)->y;

    // Inserir usuário
    $sql_users = "INSERT INTO usuarios (nome, idade, email, cpf, senha, tipo) 
                  VALUES ('$nome', '$idade', '$email', '$cpf', '$senha', 'Professor')";
    $result_users = mysqli_query($connection, $sql_users);

    if (!$result_users) {
        die("Erro ao cadastrar usuário: " . mysqli_error($connection));
    }

    $user_id = mysqli_insert_id($connection);

    // Inserir professor
    $sql_professores = "INSERT INTO professores (fk_user) 
                        VALUES ($user_id)";
    $result_professores = mysqli_query($connection, $sql_professores);

    if (!$result_professores) {
        die("Erro ao cadastrar professor: " . mysqli_error($connection));
    }

    $professor_id = mysqli_insert_id($connection);

    // Associar disciplinas e turmas
    foreach ($disciplinasTurmasSelecionadas as $item) {
        list($disciplina_id, $turma_id) = explode('-', $item);
        
        $sql = "INSERT INTO prof_disc_turma (fk_prof, fk_disc, fk_turma) 
                VALUES ($professor_id, $disciplina_id, $turma_id)";
        mysqli_query($connection, $sql);
    }

    header("Location: tipo_prof.php?success=1");
    exit;
}

// Buscar todas disciplinas
$disciplinas_query = mysqli_query($connection, "SELECT id, nome FROM disciplinas");
$disciplinas_array = mysqli_fetch_all($disciplinas_query, MYSQLI_ASSOC);

// Buscar todas turmas
$turmas_query = mysqli_query($connection, "SELECT id, nome FROM turmas");
$turmas_array = mysqli_fetch_all($turmas_query, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Professor</title>
    <link rel="stylesheet" href="Style/cad.css">
</head>
<body>
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <script>alert('Professor cadastrado com sucesso!');</script>
<?php endif; ?>

<h1>Cadastrar Novo Professor</h1>

<form action="tipo_prof.php" method="post">
    <label for="nome">Nome:</label>
    <input placeholder="Ex: Maria Souza" class="inputCad" type="text" id="nome" name="nome" required>

    <label for="email">Email:</label>
    <input placeholder="Ex: professora@gmail.com" class="inputCad" type="text" id="email" name="email" required>

    <label for="data">Data de Nascimento:</label>
    <input type="text" id="data_nascimento" class="inputCad" name="data_nascimento" placeholder="DD/MM/AAAA" pattern="\d{2}\/\d{2}\/\d{4}" required>

    <label for="cpf">CPF:</label> 
    <input placeholder="Ex: 000.000.000-00" class="inputCad" type="text" id="cpf" name="cpf" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" required>

    <label for="senha">Senha:</label>
    <input placeholder="Ex: Senha123" class="inputCad" type="password" id="senha" name="senha" required>

    <h3>Associar Disciplinas e Turmas:</h3>

<?php 
foreach ($disciplinas_array as $disc) {
    echo "<h4>Disciplina: {$disc['nome']}</h4>";
    foreach ($turmas_array as $turma) {
        $valor = "{$disc['id']}-{$turma['id']}";
        echo "<label style='margin-left:20px;'><input type='checkbox' name='disciplinas_turmas[]' value='$valor'> {$turma['nome']}</label><br>";
    }
    echo "<br>";
}
?>

    <input class="inputCad" type="submit" name="submit" id="submit" value="Cadastrar">
</form>

<div style="text-align: center;">
    <button class="button-cadastro" onclick="window.location.href='selecione_tipo.php'">Voltar</button>
</div>
</body>
</html>
