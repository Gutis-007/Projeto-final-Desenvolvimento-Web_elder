<?php 
session_start();
include("conexao.php");

// método de requisição utilizado para acessar a página
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    if (isset($_POST['email']) &&  !empty($_POST['email']) && isset($_POST['senha']) && !empty($_POST['senha'])) { // isset verifica se uma variável é considerada definida. !Empty verifica se esta vazia
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consultando o banco de dados
    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
    $result = mysqli_query($connection, $sql);
    
    if (mysqli_num_rows($result) == 1) { // Returns the number of rows in the result set.
        $user = mysqli_fetch_assoc($result);

        
        if ($user['tipo'] === 'Aluno') {
                $_SESSION['aluno_id'] = $user['id']; // Guarda ID do aluno na sessão
            header("Location: aluno.php");// envia para pagina do aluno
            
        } elseif ($user['tipo'] === 'Professor') {
            $_SESSION['prof_id'] = $user['id']; //Guarda ID do professor
            header("Location: professor.php");// envia para pagina do prof
        }
     elseif ($user['tipo'] === 'Administrador') {
        $_SESSION['admin_id'] = $user['id']; //Guarda ID do administrador
        header("Location: administrador.php");// envia para pagina do admin
    }
    }
    else{
        header("Location: logout.php"); // Redireciona para a página de logout
        exit();
    }
    }
    else{
        header("Location: logout.php"); // Redireciona para a página de logout
        exit();
    }
}
?> 
