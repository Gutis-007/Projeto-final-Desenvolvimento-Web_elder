<?php
session_start();
include("conexao.php");

// Verifica se os dados foram enviados via POST
if (isset($_POST['nota_id']) && isset($_POST['nova_nota']) && isset($_POST['aluno_id']) && isset($_POST['disciplina_id']) && isset($_POST['prof_id'])) {
    $nota_id = $_POST['nota_id'];
    $nova_nota = $_POST['nova_nota'];
    $aluno_id = $_POST['aluno_id'];
    $disciplina_id = $_POST['disciplina_id'];
    $prof_id = $_POST['prof_id'];

    $sql_prof = "SELECT id FROM professores WHERE fk_user = $prof_id";
    $result_prof = mysqli_query($connection, $sql_prof);

    $row_prof = mysqli_fetch_assoc($result_prof);
    if ($row_prof) {
        $professor_id = $row_prof['id'];
    } else {
        echo "Erro: professor não encontrado.";
        exit();
    }

    if (!empty($nota_id)) {
        // Atualiza a nota existente
        $sql = "UPDATE notas SET nota = $nova_nota, dataL = NOW() WHERE id = $nota_id";
    } else {
        // Insere uma nova nota
        $sql = "INSERT INTO notas (nota, dataL, fk_aluno, fk_disc, fk_prof)
                VALUES ($nova_nota, NOW(), $aluno_id, $disciplina_id, $professor_id)";
    }

    if (mysqli_query($connection, $sql)) {
        header("Location: notas.php");
        exit();
    } else {
        echo "Erro ao salvar nota: " . htmlspecialchars(mysqli_error($connection));
    }
} else {
    echo "Dados incompletos para salvar nota.";
}

mysqli_close($connection);
?>
