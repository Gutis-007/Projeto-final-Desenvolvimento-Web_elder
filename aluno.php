<?php 
include_once("conexao.php");
session_start();
if (!isset($_SESSION['aluno_id'])) {
    header("Location: login.html");
    exit;
}
else{
$alunoId = ($_SESSION['aluno_id']); // segurança contra SQL injection
$sql = "SELECT nome, tipo, cpf FROM usuarios WHERE id = $alunoId";
$consulta = mysqli_query($connection, $sql);
if ($consulta) {
    $row = mysqli_fetch_assoc($consulta);
    $tipo = $row['tipo'];
    $nome = $row['nome'];
    $cpf = $row['cpf'];
    if ($tipo != 'Aluno') {
        header("Location: login.html");
        exit;
    }
    $sql_aluno = "SELECT id FROM alunos WHERE fk_user = $alunoId";
    $result_alun= mysqli_query($connection, $sql_aluno);

    $row_alun = mysqli_fetch_assoc($result_alun);
    if ($row_alun) {
        $aluno_id = $row_alun['id'];
    } else {
        echo "Erro: aluno não encontrado.";
        exit();
    }
    $sqlDadosAluno = "
    SELECT 
        a.matricula,
        t.nome AS turma
    FROM alunos a
    LEFT JOIN turma_alunos ta ON ta.fk_aluno = a.id
    LEFT JOIN turmas t ON t.id = ta.fk_turma
    WHERE a.id = $aluno_id
    LIMIT 1
";

$dadosAluno = mysqli_fetch_assoc(mysqli_query($connection, $sqlDadosAluno));
$matricula = $dadosAluno['matricula'];
$turma = $dadosAluno['turma'];

} else {
    die("Erro ao verificar tipo de usuário: " . mysqli_error($connection));
}
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página do Aluno</title>
    <link rel="stylesheet" href="Style/aluno.css">

</style>
</head>
<body>
    <h1>BOLETIM DO ALUNO:</h1>
    <h2 class="nome-aluno">Seja bem-vindo, <?= htmlspecialchars($nome) ?>!</h2>
    
   <div class="info-aluno" > 
    <p>Matrícula: <?= htmlspecialchars($matricula) ?></p> 
    <p>Turma: <?= htmlspecialchars($turma) ?></p> 
    <p>CPF: <?= htmlspecialchars($cpf) ?></p>
    </div>
    <div>
        <?php
        include("conexao.php");

        $sql_notas = "SELECT 
    d.nome AS disciplina, 
    n.nota, 
    DATE_FORMAT(n.dataL, '%d/%m/%Y') AS data_formatada,
    prof.nome AS professor
FROM 
    notas n
INNER JOIN disciplinas d ON n.fk_disc = d.id
INNER JOIN professores p ON n.fk_prof = p.id
INNER JOIN usuarios prof ON p.fk_user = prof.id
WHERE 
    n.fk_aluno = (
        SELECT a.id 
        FROM alunos a 
        INNER JOIN usuarios u ON a.fk_user = u.id 
        WHERE u.id = $alunoId
    )"; 

        $consulta = mysqli_query($connection, $sql_notas); 

        if ($consulta && mysqli_num_rows($consulta) > 0) {
            echo "<table class='tabela-boletim' border='1'>";
            echo "<tr>
                    <th>Disciplina</th>
                    <th>Nota</th>
                    <th>Data de Lançamento</th>
                    <th>Professor</th>  
                  </tr>";

            while ($row = mysqli_fetch_assoc($consulta)) {
                echo "<tr>";


 
                echo "<td>" . htmlspecialchars($row['disciplina']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nota']) . "</td>";
                echo "<td>" . htmlspecialchars($row['data_formatada']) . "</td>";
                echo "<td>" . htmlspecialchars($row['professor']) . "</td>";
                echo "</tr>";
            }

            echo "</table>"; 
        } else {
            echo "<p>Nenhuma nota encontrada para este aluno.</p>";
        }

        mysqli_close($connection);
        ?>
    </div>

    <br><br>
    <form class="deslogar" action="login.html" method="post">
        <button class="botao" type="submit">Deslogar</button> 
    </form>
</body>
</html>