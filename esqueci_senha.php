<?php
require 'conexao.php'; // $connection deve estar definido com mysqli_connect

// Verifica conexão com o banco
if (mysqli_connect_errno()) {
    die("<script>
        alert('Erro ao conectar ao banco de dados.');
        window.location.href = 'login.html';
    </script>");
}

// Verifica se o método é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

// Captura e valida dados
$email = $_POST['email'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$senha1 = $_POST['senha1'] ?? '';
$senha2 = $_POST['senha2'] ?? '';

// Verifica se as senhas coincidem
if ($senha1 !== $senha2) {
    echo "<script>
        alert('As senhas não coincidem.');
        history.back();
    </script>";
    exit;
}

function validaCPF($cpf) {
 
    // Extrai somente os números
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
     
    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

// Inicia transação
mysqli_autocommit($connection, false);
$error = false;

// Consulta com prepared statement
$query = "SELECT id FROM usuarios WHERE email = ? AND cpf = ? FOR UPDATE";
$stmt = mysqli_prepare($connection, $query);

if (!$stmt) {
    $error = "Erro na preparação da consulta: " . mysqli_error($connection);
} else {
    mysqli_stmt_bind_param($stmt, "ss", $email, $cpf);

    if (!mysqli_stmt_execute($stmt)) {
        $error = "Erro na execução da consulta: " . mysqli_stmt_error($stmt);
    } else {
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 0) {
            $error = 'Combinação de e-mail e CPF não encontrada.';
        } else {
            $usuario = mysqli_fetch_assoc($result);

            // Atualiza a senha
            $novaSenha = $senha1;
            $update_query = "UPDATE usuarios SET senha = ? WHERE id = ?";
            $update_stmt = mysqli_prepare($connection, $update_query);

            if (!$update_stmt) {
                $error = "Erro na preparação do update: " . mysqli_error($connection);
            } else {
                mysqli_stmt_bind_param($update_stmt, "si", $novaSenha, $usuario['id']);

                if (!mysqli_stmt_execute($update_stmt)) {
                    $error = "Erro ao atualizar senha: " . mysqli_stmt_error($update_stmt);
                }

                mysqli_stmt_close($update_stmt);
            }
        }

        mysqli_free_result($result);
    }

    mysqli_stmt_close($stmt);
}

// Commit ou rollback
if ($error) {
    mysqli_rollback($connection);
    error_log("Erro na redefinição de senha: " . $error);

    echo "<script>
        alert('Erro ao processar sua solicitação. Tente novamente mais tarde.');
        history.back();
    </script>";
} else {
    mysqli_commit($connection);
    echo "<script>
        alert('Senha alterada com sucesso!');
        window.location.href = 'login.html';
    </script>";
}

// Finaliza
mysqli_autocommit($connection, true);
mysqli_close($connection);
?>