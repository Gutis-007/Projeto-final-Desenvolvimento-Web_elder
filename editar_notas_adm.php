<?php
session_start();
include("conexao.php");

// Verifica se o usuário é admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

// Verifica dados mínimos necessários (apenas nota_id e nova_nota)
if (isset($_POST['nota_id']) && isset($_POST['nova_nota'])) {
    $nota_id = $_POST['nota_id'];
    $nova_nota = $_POST['nova_nota'];

    // Apenas atualiza nota e data (mantém outros campos originais)
    $sql = "UPDATE notas SET nota = $nova_nota, dataL = NOW() WHERE id = $nota_id";

    if (mysqli_query($conn, $sql)) {
        header("Location: notas_adm.php");
        exit();
    } else {
        echo "Erro ao salvar nota: " . htmlspecialchars(mysqli_error($conn));
    }
} else {
    echo "Dados incompletos para salvar nota. Campos obrigatórios: nota_id, nova_nota";
}

mysqli_close($conn);
?>