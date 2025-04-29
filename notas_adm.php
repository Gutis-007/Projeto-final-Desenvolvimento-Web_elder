<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de gerência de notas do Administrador</title>
    <link rel="stylesheet" href="Style/notas.css">
</head>
<body>
    <h1>BOLETIM GERAL DOS ALUNOS</h1>
    <br> <br> 
    <div class="containerTable">
    <?php
    include("conexao.php");
    session_start();
    
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.html");
        exit();
    } else {
        $adminId = $_SESSION['admin_id'];
    }
    // Verifica se o usuário é um admin

    $sql = "SELECT 
    alunos.matricula,
    usuarios.nome AS aluno,
    disciplinas.nome AS disciplina,
    turmas.nome AS turma,
    notas.nota,
    notas.dataL,
    notas.id AS nota_id
FROM notas
INNER JOIN alunos ON notas.fk_aluno = alunos.id
INNER JOIN usuarios ON alunos.fk_user = usuarios.id
INNER JOIN disciplinas ON notas.fk_disc = disciplinas.id
INNER JOIN turma_alunos ON turma_alunos.fk_aluno = alunos.id
INNER JOIN turmas ON turma_alunos.fk_turma = turmas.id
ORDER BY turma, aluno, disciplina;
";



    $consulta = mysqli_query($connection, $sql); 

    if ($consulta) {    
        if (mysqli_num_rows($consulta) > 0) {
            echo "<table border='1'>";
            echo "<tr>
                    <th>Matrícula</th>
                    <th>Aluno</th>
                    <th>Disciplina</th>
                    <th>Nota</th>
                    <th>Data</th>
                    <th>Alterar Nota</th>
                  </tr>";
            
            while ($row = mysqli_fetch_assoc($consulta)) {
                
                $matricula = htmlspecialchars($row['matricula']);
                $aluno = htmlspecialchars($row['aluno']);
                $disciplina = htmlspecialchars($row['disciplina']);
                $nota = htmlspecialchars($row['nota']);
                $data = htmlspecialchars($row['dataL']);
                $nota_id = htmlspecialchars($row['nota_id']);

                echo "<tr>";
                echo "<td>$matricula</td>";
                echo "<td>$aluno</td>";
                echo "<td>$disciplina</td>";
                echo "<td>$nota</td>";
                echo "<td>$data</td>";
                echo "<td>
                        <form action='editar.php' method='post'>
                            <input type='hidden' name='nota_id' value='$nota_id'> 
                            <input type='text' name='nova_nota' value='$nota'>
                            <button type='submit'>Atualizar</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            
            echo "</table>";
        } else {
            echo "Nenhum resultado encontrado.";
        }
    } else {
        echo "Erro na consulta: " . htmlspecialchars(mysqli_errno($connection)) . " - " . htmlspecialchars(mysqli_error($connection));
    }

    mysqli_close($connection);
    ?>
    </div>
    <br><br>
    <div style="text-align: center;">
        <button onclick="window.location.href='administrador.php'">Voltar</button>
    </div>
</body>
</html>