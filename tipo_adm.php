<?php 
include_once("conexao.php");

// CADASTRAR Admnistrador
if (isset($_POST['submit'])) {
    // Coletar dados do formulário
    $nome = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $cpf = htmlspecialchars($_POST['cpf'], ENT_QUOTES, 'UTF-8');
    $senha = htmlspecialchars($_POST['senha'], ENT_QUOTES, 'UTF-8');
    $data_nascimento = htmlspecialchars($_POST['data_nascimento'], ENT_QUOTES, 'UTF-8');

    // Validar data
    $data = DateTime::createFromFormat('d/m/Y', $data_nascimento);
    if (!$data) {
        die("Formato de data inválido. Use DD/MM/AAAA");
    }
    $hoje = new DateTime();
    $idade = $hoje->diff($data)->y;

    // Inserir usuário
    $sql_users = "INSERT INTO usuarios (nome, idade, email, cpf, senha, tipo) 
                  VALUES ('$nome', '$idade', '$email', '$cpf', '$senha', 'Administrador')";
    $result_users = mysqli_query($connection, $sql_users);

    if (!$result_users) {
        die("Erro ao cadastrar usuário: " . mysqli_error($connection));
    }

    $user_id = mysqli_insert_id($connection);

    // Inserir Admnistrador
    $sql_Admnistradores = "INSERT INTO Administradores (fk_user) 
                        VALUES ($user_id)";
    $result_Admnistradores = mysqli_query($connection, $sql_Admnistradores);

    if (!$result_Admnistradores) {
        die("Erro ao cadastrar Administrador: " . mysqli_error($connection));
    }

    $Admnistrador_id = mysqli_insert_id($connection);



    header("Location: tipo_adm.php?success=1");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Administrador</title>
    <link rel="stylesheet" href="Style/cad.css">
</head>
<body>
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <script>alert('Admnistrador cadastrado com sucesso!');</script>
<?php endif; ?>

<h1>Cadastrar Novo Administrador</h1>

<form action="tipo_adm.php" method="post">
    <label for="nome">Nome:</label>
    <input placeholder="Ex: Maria Souza" class="inputCad" type="text" id="nome" name="nome" required>

    <label for="email">Email:</label>
    <input placeholder="Ex: Administradora@gmail.com" class="inputCad" type="text" id="email" name="email" required>

    <label for="data">Data de Nascimento:</label>
    <input type="text" id="data_nascimento" class="inputCad" name="data_nascimento" placeholder="DD/MM/AAAA" pattern="\d{2}\/\d{2}\/\d{4}" required>

    <label for="cpf">CPF:</label> 
    <input placeholder="Ex: 000.000.000-00" class="inputCad" type="text" id="cpf" name="cpf" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" required>

    <label for="senha">Senha:</label>
    <input placeholder="Ex: Senha123" class="inputCad" type="password" id="senha" name="senha" required>


    <input class="inputCad" type="submit" name="submit" id="submit" value="Cadastrar">
</form>

<div style="text-align: center;">
    <button class="button-cadastro" onclick="window.location.href='selecione_tipo.php'">Voltar</button>
</div>
</body>
</html>
