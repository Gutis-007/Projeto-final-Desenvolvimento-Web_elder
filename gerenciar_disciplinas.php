<?php
include("conexao.php");
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit;
}

// Define limite de tamanho (2MB)
$tamanho_maximo = 2 * 1024 * 1024; // 2MB

// Função para processar o upload
function processarUploadImagem($inputName) {
    global $tamanho_maximo;
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == 0) {
        if ($_FILES[$inputName]['size'] > $tamanho_maximo) {
            echo "<script>alert('Imagem excede o tamanho máximo permitido de 2MB!');</script>";
            return false;
        }
        $imagem_nome = basename($_FILES[$inputName]['name']);
        $imagem_caminho = "img/" . $imagem_nome;
        $imagem_tmp = $_FILES[$inputName]['tmp_name'];

        if (move_uploaded_file($imagem_tmp, $imagem_caminho)) {
            return $imagem_caminho;
        } else {
            echo "<script>alert('Erro ao fazer upload da imagem.');</script>";
            return false;
        }
    }
    return false;
}

// Processa exclusão
if (isset($_POST['deletar'])) {
    $disciplina_id = $_POST['disciplina_id'];
    $sql_delete = "DELETE FROM disciplinas WHERE id = $disciplina_id";
    mysqli_query($connection, $sql_delete);
    header("Location: gerenciar_disciplinas.php");
    exit;
}

// Processa edição
if (isset($_POST['editar'])) {
    $disciplina_id = $_POST['disciplina_id'];

    // Busca os dados atuais
    $sql_buscar = "SELECT * FROM disciplinas WHERE id = $disciplina_id";
    $resultado_buscar = mysqli_query($connection, $sql_buscar);
    $disciplina_atual = mysqli_fetch_assoc($resultado_buscar);

    // Pega novos valores se enviados, senão usa o antigo
    $novo_nome = !empty($_POST['novo_nome']) ? $_POST['novo_nome'] : $disciplina_atual['nome'];
    $nova_descricao = !empty($_POST['nova_descricao']) ? $_POST['nova_descricao'] : $disciplina_atual['descricao'];

    // Monta o SQL de atualização
    $sql_update = "UPDATE disciplinas SET nome = '$novo_nome', descricao = '$nova_descricao'";

    $nova_imagem = processarUploadImagem('nova_imagem');
    if ($nova_imagem) {
        $sql_update .= ", imagem = '$nova_imagem'";
    }

    $sql_update .= " WHERE id = $disciplina_id";
    mysqli_query($connection, $sql_update);
    header("Location: gerenciar_disciplinas.php");
    exit;
}


// Processa adição
if (isset($_POST['adicionar'])) {
    $nome_disciplina = $_POST['nome_disciplina'];
    $descricao = $_POST['descricao'];

    $imagem = processarUploadImagem('imagem');
    if ($imagem) {
        $sql_insert = "INSERT INTO disciplinas (nome, descricao, imagem) VALUES ('$nome_disciplina', '$descricao', '$imagem')";
        mysqli_query($connection, $sql_insert);
        header("Location: gerenciar_disciplinas.php");
        exit;
    }
}

// Puxa todas as disciplinas
$sql = "SELECT * FROM disciplinas ORDER BY nome";
$resultado = mysqli_query($connection, $sql);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Disciplinas</title>
    <link rel="stylesheet" href="style/gerTurma.css">

    <style>
        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            width: 250px;
            padding: 15px;
            text-align: center;
        }

        .card img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .card h3 {
            margin-top: 10px;
            font-size: 1.2em;
        }

        .card p {
            font-size: 0.9em;
            color: #555;
            height: 60px;
            overflow: hidden;
        }

        .card form {
            margin-top: 10px;
        }

        .btn {
            padding: 8px 12px;
            margin: 5px;
            border: none;
            border-radius: 6px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            font-size: 0.9em;
        }

        .btn-excluir {
            background-color: #f44336;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Gerenciamento de Disciplinas</h1>

    <div class="cards">
        <?php while ($disciplina = mysqli_fetch_assoc($resultado)): ?>
            <div class="card">
                <?php if (!empty($disciplina['imagem'])): ?>
                    <img src="<?php echo $disciplina['imagem']; ?>" alt="Imagem da disciplina">
                <?php else: ?>
                    <img src="img/padrao.png" alt="Imagem padrão">
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($disciplina['nome']); ?></h3>
                <p><?php echo htmlspecialchars($disciplina['descricao']); ?></p>

                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="disciplina_id" value="<?php echo $disciplina['id']; ?>">
                    <input type="text" name="novo_nome" placeholder="Novo nome"><br><br>
                    <textarea name="nova_descricao" placeholder="Nova descrição" rows="2"></textarea><br><br>
                    <input type="file" name="nova_imagem" accept="image/*"><br><br>
                    <button type="submit" name="editar" class="btn">Editar</button>
                </form>

                <form method="post" onsubmit="return confirm('Tem certeza que deseja excluir esta disciplina?');">
                    <input type="hidden" name="disciplina_id" value="<?php echo $disciplina['id']; ?>">
                    <button type="submit" name="deletar" class="btn btn-excluir">Excluir</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="form-adicionar" style="margin-top: 40px; text-align: center;">
        <h2>Adicionar Nova Disciplina</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="nome_disciplina" placeholder="Nome da disciplina" required><br><br>
            <textarea name="descricao" placeholder="Descrição" rows="4" cols="40" required></textarea><br><br>
            <input type="file" name="imagem" accept="image/*" required><br><br>
            <button type="submit" name="adicionar" class="btn">Adicionar Disciplina</button>
        </form>

        <br>
        <a href="administrador.php" class="btn" style="background-color: gray;">Voltar</a>
    </div>
</div>

</body>
</html>

<?php mysqli_close($connection); ?>